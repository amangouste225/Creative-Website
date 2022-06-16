<?php
if (!class_exists('ChatSystem')) {
    /**
     * One to One Chat System
     * 
     * @package Workreap
     */
    class ChatSystem
    {
        
        /**
         * DB Variable
         * 
         * @var [string]
         */
        protected static $wpdb;
        /**
         * Initialize Singleton
         *
         * @var [void]
         */
        private static $_instance = null;

        /**
         * Call this method to get singleton
         *
         * @return ChatSystem Instance
         */
        public static function instance()
        {
            if (self::$_instance === null) {
                self::$_instance = new ChatSystem();
            }
            return self::$_instance;
        }

        /**
         * PRIVATE CONSTRUCTOR
         */
        private function __construct()
        {
            global $wpdb;
            self::$wpdb = $wpdb;
            add_action('after_setup_theme', array(__CLASS__, 'createChatTable'));
            add_action('fetch_users_threads', array(__CLASS__, 'fetchUserThreads'), 11, 1);
            add_action('wp_ajax_fetchUserConversation', array(__CLASS__, 'fetchUserConversation'));
            add_action('wp_ajax_sendUserMessage', array(__CLASS__, 'sendUserMessage'));
            add_action('wp_ajax_sendUserAttachment', array(__CLASS__, 'sendUserAttachment'));
			add_action('wp_ajax_deleteChatMessage', array(__CLASS__, 'deleteChatMessage'));
            add_action('wp_ajax_getIntervalChatHistoryData', array(__CLASS__, 'getIntervalChatHistoryData'));
            add_filter('get_user_info', array(__CLASS__, 'getUserInfoData'), 10, 3);
            add_action('workreap_chat_modal', array(__CLASS__, 'workreap_chat_modal'),11,3);
            add_action('fetch_single_users_threads', array(__CLASS__, 'fetchSingleUserThreads'), 11, 2);
			add_action('workreap_chat_count', array(__CLASS__, 'countUnreadMessages'),11,1);
			
        }

        /**
         * Create Chat Table
         *
         * @return void
         */
        public static function createChatTable()
        {
            $privateChat = self::$wpdb->prefix . 'private_chat';

            if (self::$wpdb->get_var("SHOW TABLES LIKE '$privateChat'") != $privateChat) {
                $charsetCollate = self::$wpdb->get_charset_collate();            
                $privateChat = "CREATE TABLE $privateChat (
                    id int(11) NOT NULL AUTO_INCREMENT,
                    sender_id int(20) UNSIGNED NOT NULL,
                    receiver_id int(20) UNSIGNED NOT NULL,
                    chat_message text NULL,
                    status tinyint(1) NOT NULL,
                    timestamp varchar(20) NOT NULL,
                    time_gmt datetime NOT NULL,
                    PRIMARY KEY (id)                           
                    ) $charsetCollate;";   
                                        
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($privateChat);     
            }
			
			$earnings = self::$wpdb->prefix . 'wt_earnings';

            if (self::$wpdb->get_var("SHOW TABLES LIKE '$earnings'") != $earnings) {
                $charsetCollate = self::$wpdb->get_charset_collate();            
                $earnings = "CREATE TABLE $earnings (
                    id int(11) NOT NULL AUTO_INCREMENT,
                    user_id int(20) NOT NULL,
					currency_symbol varchar(200) NULL,
                    amount decimal(13,2)  NOT NULL,
                    freelancer_amount decimal(13,2) NULL,
					admin_amount decimal(13,2) NULL,
                    order_id int(12) NOT NULL,
                    milestone_id int(12) NOT NULL,
                    project_id int(20) NOT NULL,
                    process_date datetime NOT NULL,
					date_gmt datetime NOT NULL,
					timestamp int(50) NOT NULL,
					year year(4) NOT NULL,
					month varchar(12) NOT NULL,
					status enum('hired','completed','cancelled','processed') NOT NULL,
					project_type enum('project','service','milestone') NOT NULL,
                    PRIMARY KEY (id)                           
                    ) $charsetCollate;";   
                                        
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($earnings);     
            }
			
			$withdrawal = self::$wpdb->prefix . 'wt_payouts_history';

            if (self::$wpdb->get_var("SHOW TABLES LIKE '$withdrawal'") != $withdrawal) {
                $charsetCollate = self::$wpdb->get_charset_collate();            
                $withdrawal = "CREATE TABLE $withdrawal (
                    id int(11) NOT NULL AUTO_INCREMENT,
                    user_id int(20) NOT NULL,
                    amount decimal(13,2)  NOT NULL,
                    currency_symbol varchar(200) NULL,
					payment_method varchar(200) NULL,
					payment_details text NULL,
					paypal_email varchar(256)  NOT NULL,
                    processed_date datetime NOT NULL,
					date_gmt datetime NOT NULL,
					timestamp int(50) NOT NULL,
					year year(4) NOT NULL,
					month varchar(12) NOT NULL,
					status enum('completed','inprogress') NOT NULL,
					project_type enum('project','service') NOT NULL,
                    PRIMARY KEY (id)                           
                    ) $charsetCollate;";   
                                        
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($withdrawal);     
            } else{
				$theme_version = wp_get_theme('workreap_core');
				$version	= str_replace( '.','',$theme_version->get('Version'));

				if( $version > 112 && $version < 131 ){
					$row = self::$wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$withdrawal' AND column_name = 'payment_details'"  );
					if(empty($row)){
					  	self::$wpdb->query("ALTER TABLE $withdrawal ADD payment_details longtext NULL");
					}
				} else if( $version > 131 ){
					$row = self::$wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$earnings' AND column_name = 'milestone_id'"  );
                    if(empty($row)){
					  	self::$wpdb->query("ALTER TABLE $earnings ADD milestone_id int(11) NOT NULL");
						self::$wpdb->query("ALTER TABLE
							$earnings
							MODIFY COLUMN
							`project_type` enum('project','service','milestone')
							 NOT NULL AFTER `status`");
						
						
					}
				}
			}
        }

        /**
         * Get Chat Users List Threads
         *
         * @return array
         */
        public static function getUsersThreadListData(
            $userId = '',
            $receiverID = '',
            $type = 'list',
            $data = array(),
            $msgID = '',
			$offset 		= ''
        ) {
            $privateChat = self::$wpdb->prefix . 'private_chat';
            $userTable = self::$wpdb->prefix . 'users';
            $fetchResults = array();

            switch ($type) {
            case "list":
                $fetchResults = self::$wpdb->get_results(
                    "SELECT * FROM $privateChat
                    WHERE id IN ( 
                        SELECT MAX(id) AS id 
                        FROM ( 
                            SELECT id, sender_id AS chat_sender 
                            FROM $privateChat 
                            WHERE receiver_id = $userId OR sender_id = $userId 
                        UNION ALL 
                            SELECT id, receiver_id AS chat_sender 
                            FROM $privateChat 
                            WHERE sender_id = $userId OR receiver_id = $userId ) t GROUP BY chat_sender ) ORDER BY id DESC", ARRAY_A
                );
                break;
                case "list_receivers":
                    $privateChat = self::$wpdb->prefix . 'private_chat';
                    $fetchResults = self::$wpdb->get_results(
                        "SELECT *
                            FROM  (SELECT sender_id AS userId
                                FROM   $privateChat
                                WHERE  ( receiver_id = $userId
                                            OR sender_id = $userId )
                                UNION ALL
                                SELECT receiver_id AS userId
                                FROM   $privateChat
                                WHERE  ( sender_id = $userId
                                            OR receiver_id = $userId )) AS t
                            WHERE  userid <> $userId
                            GROUP BY userid ORDER BY userid DESC"
                    );
                    break;
            case "fetch_thread":
                $fetchResults = self::$wpdb->get_results(
                    "SELECT * FROM $privateChat
                    WHERE 
                        ($privateChat.sender_id = $userId 
                    AND 
                        $privateChat.receiver_id = $receiverID) 
                    OR 
                        ($privateChat.sender_id = $receiverID 
                    AND 
                        $privateChat.receiver_id = $userId) 
                    ", ARRAY_A
                );
                break;
			case "fetch_thread_last_items":
					
				$total	= 10;
				$limit	= $offset*$total;
					
                $fetchResults = self::$wpdb->get_results(
                    "SELECT * FROM ( SELECT * FROM $privateChat 
					
                    WHERE 
                        ($privateChat.sender_id = $userId 
                    AND 
                        $privateChat.receiver_id = $receiverID) 
                    OR 
                        ($privateChat.sender_id = $receiverID 
                    AND 
                        $privateChat.receiver_id = $userId) 

					ORDER BY id DESC LIMIT $limit , $total
					
					)  sub ORDER BY id ASC
						
                    ", ARRAY_A
                );
                break;
            case "fetch_interval_thread":
                $fetchResults = self::$wpdb->get_results(
                    "SELECT * FROM $privateChat
                    WHERE ($privateChat.sender_id = $userId 
                        AND $privateChat.receiver_id = $receiverID)
                    OR ($privateChat.sender_id = $receiverID 
                        AND $privateChat.receiver_id = $userId)
                    ORDER BY $privateChat.id ASC
                    ", ARRAY_A
                );
                break;
            case "set_thread_status":
                self::$wpdb->update(
                    $privateChat,
                    array("status" => intval(0)),
                    array(
                        "sender_id" => stripslashes_deep($receiverID),
                        "receiver_id" => stripslashes_deep($userId),
                        "status" => intval(1)
                    )
                );
                break;
            case "insert_msg":
                self::$wpdb->insert($privateChat, $data);
                return self::$wpdb->insert_id;
                break;
            case "fetch_recent_thread":
                $fetchResults = self::$wpdb->get_row(
                    "SELECT * FROM
                    $privateChat
                    WHERE $privateChat.id = $msgID", ARRAY_A
                );
                break;
			case "fetch_conversation_list":
				$total = 10;
                $fetchResults = self::$wpdb->get_results(
					"SELECT * from $privateChat where timestamp IN (
							SELECT  MAX(timestamp) 
							FROM $privateChat
							GROUP BY sender_id
					)  
					ORDER BY `$privateChat`.`id`  DESC LIMIT ${offset}, ${total}", ARRAY_A
				);
                break;
			case "fetch_conversation_get_total":
                $fetchResults = self::$wpdb->get_var(
					"SELECT COUNT(*) as total from $privateChat where timestamp IN (
							SELECT  MAX(timestamp) 
							FROM $privateChat
							GROUP BY sender_id
					)  
					ORDER BY `$privateChat`.`id`  DESC"
				);
                break;
			case "fetch_conversation":
				$total = 10;
                $fetchResults = self::$wpdb->get_results(
					
					"SELECT * FROM $privateChat ORDER BY id DESC LIMIT ${offset}, ${total}", ARRAY_A
				);
                break;
			case "fetch_conversation_total":
                $fetchResults = self::$wpdb->get_var(
					"SELECT COUNT(*) as total  FROM $privateChat ORDER BY id DESC"
				);
                break;
            case "count_unread_msgs":
                $fetchResults = self::$wpdb->get_var(
                    "SELECT count(*) AS unread FROM $privateChat where $privateChat.receiver_id = $userId and status = 1"
                );
                break;
			 case "count_unread_msgs_by_user":
                $fetchResults = self::$wpdb->get_var(
                    "SELECT count(*) AS unread FROM $privateChat 
                    WHERE $privateChat.sender_id =  $userId
                    AND $privateChat.receiver_id = $receiverID
                    AND $privateChat.status = 1"
                );
                break;
            case "delete_thread_msg":
                self::$wpdb->delete($privateChat, $data);
                break;
			
			case "delete_conversation_msg":
				global $wpdb;
                $wpdb->query("DELETE FROM $privateChat WHERE ( sender_id=$data[0] AND receiver_id=$data[1] ) || ( sender_id=$data[1] AND receiver_id=$data[0] )" ) ;
                break;
					
			case "delete_all_threads":
                self::$wpdb->query('TRUNCATE TABLE '.$privateChat);
                break;
            }
            
            return $fetchResults;
        }
		
		/**
         * display chat model on detail page
         *
         * @param string $userId
         * @return void
         */
        public static function workreap_chat_modal($userId = '', $type = '', $show_jobs = 'yes')
        {
			global $current_user;
			if( is_user_logged_in() ) {
                $user_type		= apply_filters('workreap_get_user_type', $current_user->ID );

				if( $user_type === 'employer' && $show_jobs == 'yes' ) {
					$args = array(
						'author'        	=>  $current_user->ID, 
						'orderby'       	=>  'post_date',
						'post_type' 		=> 'projects',
						'post_status' 		=> array('publish'),
						'order'         	=>  'ASC',
						'posts_per_page' 	=> -1 // no limit
                    );
                    
                    
                    $projects = get_posts( $args );
                    
				}else {
					$projects	= '';
                }

                $profile_id = workreap_get_linked_profile_id($userId);
			}
			?>
			<div class="modal fade wt-offerpopup" tabindex="-1" role="dialog" id="chatmodal">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="wt-modalcontent modal-content">
						<div class="wt-popuptitle">
							<h2><?php esc_html_e('Send a message','workreap_core');?></h2>
							<a href="#" onclick="event_preventDefault(event);" class="wt-closebtn close"><i class="fa fa-close" data-dismiss="modal"></i></a>
						</div>
						<div class="modal-body">
							<form class=" chat-form">
								<?php if( apply_filters('workreap_system_access','job_base') === true && !empty($projects) ){?>
									<div class="wt-projectdropdown-hold">
										<div class="wt-projectdropdown">
											<div class="wt-select">
												<select name="project_id" class="project_id" id="project_id">
													<option value=""><?php esc_html_e('Select a project','workreap_core');?></option>
													<?php foreach( $projects as $project ) {?>
														<option value="<?php echo intval( $project->ID );?>">
															<?php echo esc_attr( $project->post_title );?>
														</option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
								<?php }?>
								<div class="wt-formtheme wt-formpopup">
									<fieldset class="wt-replaybox">
										<div class="form-group">
											<textarea class="form-control reply_msg" name="reply" placeholder="<?php esc_html_e('Type message here', 'workreap_core'); ?>"></textarea>
										</div>
										<div class="form-group wt-btnarea">
											<p><?php esc_html_e('Click the button to send the message to this freelancer','workreap_core');?></p>
											<a href="#" onclick="event_preventDefault(event);" class="wt-btn wt-send-offer" data-invitetype="<?php echo esc_attr($type); ?>" data-status="unread" data-msgtype="modal" data-projectid="" data-receiver_id="<?php echo esc_attr($userId);?>"><?php esc_html_e('Send message','workreap_core');?></a>
										</div>
									</fieldset>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		<?php
		}
		
        /**
         * Undocumented function
         *
         * @param string $userId
         * @return void
         */
        public static function fetchUserThreads($userId = '')
        {
            ob_start();
            $usersThreadUserList = self::getUsersThreadListData($userId, '', 'list', array(), '');
            $chat_active_user   = isset($_GET['user_id']) ? $_GET['user_id'] : '';
            ?>
            <ul>
                <li>							
                    <div class="wt-formtheme wt-formsearch">
                        <fieldset>
                            <div class="form-group">
                                <input type="text" name="fullname" class="form-control wt-filter-users" placeholder="<?php esc_html_e('Search chat users', 'workreap_core'); ?>">
                                <a href="#" onclick="event_preventDefault(event);" class="wt-searchgbtn"><i class="fa fa-filter"></i></a>
                            </div>
                        </fieldset>
                    </div>
                    <div class="wt-verticalscrollbar wt-dashboardscrollbar">
                        <?php
						if (!empty($usersThreadUserList)) { 
                        foreach ($usersThreadUserList as $userVal) {
							
                            $unreadNotifyClass  = '';
                            $chat_user_id 		= '';
							
                            if ($userVal['status'] == 1) {
                                $unreadNotifyClass = 'wt-dotnotification';
                            }
							
                            if ($userId === intval($userVal['sender_id'])) {
                                $chat_user_id = intval($userVal['receiver_id']);
                            } else {
                                $chat_user_id = intval($userVal['sender_id']);
                            }

                            $userAvatar = self::getUserInfoData('avatar', $chat_user_id, array('width' => 100, 'height' => 100));
                            $userName 	= self::getUserInfoData('username', $chat_user_id, array());
							$userUrl 	= self::getUserInfoData('url', $chat_user_id, array());
							$count 		= self::getUsersThreadListData($chat_user_id,$userId,'count_unread_msgs_by_user');
							$unread		= !empty( $count ) ? $count : 0;
                            ?>
                            <div class="wt-ad wt-load-chat <?php echo esc_attr($unreadNotifyClass); ?>" id="load-user-chat-<?php echo intval($chat_user_id); ?>" data-userid="<?php echo intval($chat_user_id); ?>" data-currentid="<?php echo intval($userId); ?>" data-msgid="<?php echo intval($userVal['id']); ?>" data-img="<?php echo esc_url($userAvatar); ?>" data-name="<?php echo esc_attr($userName); ?>" data-url="<?php echo esc_url($userUrl); ?>">
                                <figure>
                                    <img src="<?php echo esc_url($userAvatar); ?>" alt="<?php echo esc_attr($userName); ?>">
                                    <?php echo do_action('workreap_print_user_status',$chat_user_id);?>
                                </figure>
                                <div class="wt-adcontent">
                                    <h3><?php echo esc_attr($userName); ?></h3>
                                    <span class="list-last-message">
                                        <?php
                                            if (!is_serialized($userVal['chat_message'])) {
                                                echo stripslashes($userVal['chat_message']);
                                            } else {
                                                $files_data = unserialize($userVal['chat_message']);
                                                echo html_entity_decode( stripslashes($files_data['file_name']), ENT_QUOTES );
                                            }
                                        ?>
                                    </span>
                                </div>
                                <em class="wtunread-count"><?php echo intval( $unread );?></em>
                            </div>	
                        <?php }}?>
                    </div>
                </li>
                <li>
                    <div class="wt-chatarea load-wt-chat-message">
                        <div class="wt-chatarea wt-chatarea-empty">
                            <figure class="wt-chatemptyimg">
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/images/message-img.png'); ?>" alt="<?php esc_attr_e('No Message Selected', 'workreap_core'); ?>">
                                <figcaption>
                                    <h3><?php esc_html_e('No message selected to display', 'workreap_core'); ?></h3>
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                </li>
            </ul>
            
            <?php
                if(!empty($chat_active_user)){
                    $script = "
                    jQuery(window).on('load', function() {
                            jQuery('#load-user-chat-".esc_js($chat_active_user)."').trigger('click'); 
                        }); 
                        
                        ";
                    wp_add_inline_script( 'workreap-user-dashboard', $script, 'before' );
                }
                echo ob_get_clean();
        }

        /**
         * Fetch single user conversation
         *
         * @param string $userId
         * @return void
         */
        public static function fetchSingleUserThreads($userId = '',$recived_id = '') {
            ob_start();
            $usersThreadUserList 	= self::getUsersThreadListData( $userId, $recived_id, 'fetch_thread', array(), '');

            $upload_dir = wp_upload_dir();
            $upload_dir_path = $upload_dir['baseurl'];
            
			if ( !empty( $usersThreadUserList ) ) { 
				foreach ( $usersThreadUserList as $userVal ) { 
					$unreadNotifyClass  = 'wt-offerermessage';
					$chat_user_id 		= '';
					if ( $recived_id === intval($userVal['sender_id']) ) {
						$unreadNotifyClass = 'wt-memessage wt-readmessage';
					} 

					$chat_user_id = intval($userVal['sender_id']);

					$message_date	= !empty($userVal['time_gmt']) ?  date(get_option('date_format'), strtotime($userVal['time_gmt'])) : '';

					$userAvatar = self::getUserInfoData('avatar', $chat_user_id, array('width' => 100, 'height' => 100));
					$userName 	= self::getUserInfoData('username', $chat_user_id, array());
					$userUrl 	= self::getUserInfoData('url', $chat_user_id, array());
                    $count 		= self::getUsersThreadListData($chat_user_id,$userId,'count_unread_msgs_by_user');
                    

                    if (!is_serialized($userVal['chat_message'])) {
                        $chat_message['text_msg']			= html_entity_decode( stripslashes($userVal['chat_message']),ENT_QUOTES );
                    } else {
                        $files_data = unserialize($userVal['chat_message']);
                        
						$chat_message['chat_filename'] 		= esc_attr($files_data['file_name']);
                        $chat_message['chat_filesize'] 		= esc_attr($files_data['file_size']);
                        $chat_message['chat_filetype'] 		= esc_attr($files_data['file_type']);
						
						if(!empty($files_data['chat_hashname'])){
							$chat_message['chat_attachment']    = esc_url($upload_dir_path.'/chat_attachments/'.esc_attr($files_data['chat_hashname']));
						}else{
							$chat_message['chat_attachment']    = esc_url($upload_dir_path.'/chat_attachments/'.esc_attr($files_data['file_name']));
						}
                    }

					?>
					<div class="<?php echo esc_attr( $unreadNotifyClass );?>" id="load-user-chat-<?php echo intval($chat_user_id); ?>" data-userid="<?php echo intval($chat_user_id); ?>" data-currentid="<?php echo intval($userId); ?>" data-msgid="<?php echo intval($userVal['id']); ?>" data-img="<?php echo esc_url($userAvatar); ?>" data-name="<?php echo esc_attr($userName); ?>" data-url="<?php echo esc_url($userUrl); ?>">
						<?php if (!empty($userAvatar)) { ?>
                            <figure><img src="<?php echo esc_url( $userAvatar );?>" alt="<?php echo esc_attr( $userName );?>"></figure>
                        <?php } ?>
                        <div class="wt-description">
                            <?php if (!is_serialized($userVal['chat_message'])) {?>
                                <p><?php echo html_entity_decode( stripslashes($chat_message['text_msg']), ENT_QUOTES ); ?></p>
                            <?php } else { ?>
                                <div class="wt-messagesfile">
                                    <div class="wt-messagescontent">
                                        <figure class="wt-meassagesfig <?php echo esc_html($chat_message['chat_filetype']); ?>">
                                            <img src="<?php echo esc_url(get_template_directory_uri() . '/images/file-ext-sprite.jpg'); ?>">
                                        </figure>
                                        <div class="wt-messagesfile__title">
                                            <a href="<?php echo esc_url($chat_message['chat_attachment']); ?>" download><?php echo esc_html($chat_message['chat_filename']); ?></a>
                                            <em><?php esc_html_e('File Size: ', 'workreap_core').esc_html($chat_message['chat_filesize']); ?></em>
                                        </div>
                                        <a class="wt-messagesfile__uploader" href="<?php echo esc_url($chat_message['chat_attachment']); ?>" download></a>
                                    </div> 
                                </div>
                            <?php } ?>
                            <div class="clearfix"></div>
                            <time datetime="<?php echo esc_attr($userVal['time_gmt']);?>"><?php echo esc_html($message_date);?></time>
                        </div>
					</div>
			<?php }
			}
            echo ob_get_clean();
        }

        /**
         * Fetch User Conversation
         *
         * @return void
         */
        public static function fetchUserConversation()
        {
			$do_check = check_ajax_referer('ajax_nonce', 'security', false);
			if ( $do_check == false ) {
				$json['type'] = 'error';
				$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap_core');
				wp_send_json( $json );
			}
			
            $json = array();
			$senderID 		= !empty( $_POST['current_id'] ) ? intval( $_POST['current_id'] ) : '';
            $receiverID 	= !empty( $_POST['user_id'] ) ? intval( $_POST['user_id'] ) : '';
            $lastMsgId 		= !empty( $_POST['msg_id'] ) ? intval( $_POST['msg_id'] ) : '';
			$thread_page 		= !empty( $_POST['thread_page'] ) ? intval( $_POST['thread_page'] ) : 0;
			
            if (!empty($_POST) && $receiverID != $senderID ) {

                $usersThreadData = self::getUsersThreadListData($senderID, $receiverID, 'fetch_thread_last_items', array(), '',$thread_page);
                //Update Chat Status in DB
                self::getUsersThreadListData($senderID, $receiverID, 'set_thread_status', array(), '');
                //Prepare Chat Nodes
                $chat_nodes = array();

                if (!empty($usersThreadData)) {
                    foreach ($usersThreadData as $key => $val) {

                        $chat_nodes[$key]['chat_is_sender'] = 'no';
                        if ($val['sender_id'] == $senderID) {
                            $chat_nodes[$key]['chat_is_sender'] = 'yes';
                        }

                        
						
						$offset 	= (float) get_option( 'gmt_offset' );
						$seconds 	= intval( $offset * HOUR_IN_SECONDS );
						$timestamp 	= strtotime( $val['time_gmt'] ) + $seconds;
						
                        $date = !empty($val['time_gmt']) ?  human_time_diff( $timestamp,current_time( 'timestamp' ) ).' '.esc_html__('ago','workreap_core') : '';
                        $chat_nodes[$key]['chat_avatar'] 			= self::getUserInfoData('avatar', $val['sender_id'], array('width' => 100, 'height' => 100));
                        $chat_nodes[$key]['chat_username'] 			= self::getUserInfoData('username', $val['sender_id'], array());
						
                        //Check if meesage is serialized
                        if (!is_serialized($val['chat_message'])) {
                            $chat_nodes[$key]['chat_message'] 			= html_entity_decode( stripslashes($val['chat_message']),ENT_QUOTES );
                        } else {
                            $files_data = unserialize($val['chat_message']);
                            $chat_nodes[$key]['chat_hashname'] 		= !empty($files_data['chat_hashname']) ? esc_attr($files_data['chat_hashname']) :'';
							$chat_nodes[$key]['chat_filename'] 		= esc_attr($files_data['file_name']);
                            $chat_nodes[$key]['chat_filesize'] 		= esc_attr($files_data['file_size']);
                            $chat_nodes[$key]['chat_filetype'] 		= esc_attr($files_data['file_type']);
                        }
						
                        $chat_nodes[$key]['chat_date'] 				= $date;
                        $chat_nodes[$key]['chat_id'] 				= intval($val['id']);
                        $chat_nodes[$key]['chat_current_user_id'] 	= intval($senderID);
						$chat_nodes[$key]['chat_receiver_id'] 		= intval($receiverID);
                    }
                    

                    //Create Chat Sidebar Data
                    $chat_sidebar = array();
                    $chat_sidebar['avatar'] 		= self::getUserInfoData('avatar', $receiverID, array('width' => 100, 'height' => 100));
                    $chat_sidebar['username'] 		= self::getUserInfoData('username', $receiverID, array());
                    $chat_sidebar['user_register']  = self::getUserInfoData('user_register', $receiverID, array());
					
					
					$chat_sidebar_second = array();
                    $chat_sidebar_second['avatar'] 			= self::getUserInfoData('avatar', $senderID, array('width' => 100, 'height' => 100));
                    $chat_sidebar_second['username'] 		= self::getUserInfoData('username', $senderID, array());
                    $chat_sidebar_second['user_register']  	= self::getUserInfoData('user_register', $senderID, array());

                    //Chat Sender Data
                    $chat_sender = array();
                    $chat_sender['avatar'] 		= self::getUserInfoData('avatar', $senderID, array('width' => 100, 'height' => 100));
                    $chat_sender['username'] 		= self::getUserInfoData('username', $senderID, array());
                    $chat_sender['user_register']  = self::getUserInfoData('user_register', $senderID, array());

                    $json['type'] 					= 'success';
                    $json['chat_nodes'] 			= $chat_nodes;
                    $json['chat_receiver_id'] 		= intval($receiverID);
					$json['chat_sender_id'] 		= intval($senderID);
                    $json['chat_sidebar'] 			= $chat_sidebar;
					$json['chat_sidebar_second'] 	= $chat_sidebar_second;
                    $json['chat_sender'] 			= $chat_sender;
                    $json['current_date'] 			= date_i18n(get_option('date_format'), time());
                    $json['message'] 				= esc_html__('Chat Messages Found!', 'workreap_core');
                    wp_send_json($json);
					
                } else {
                    $json['type']       = 'error';
                    $json['message']    = esc_html__('No more messages...', 'workreap_core');
                    wp_send_json($json);
                }
            } else {
                $json['type']       = 'error';
                $json['message'] = esc_html__('Some error occur, please try again later', 'workreap_core');
                wp_send_json($json);
            }
            
        }

        /**
         * Send user attachment function
         *
         * @return void
         */
        public static function sendUserAttachment()
        {
            global $current_user;
            $json = array();

            $receiver_id	= !empty( $_POST['receiver_id'] ) ? intval($_POST['receiver_id']) : '';
            $msg_type		= !empty( $_POST['msg_type'] ) && esc_attr( $_POST['msg_type'] ) ? $_POST['msg_type'] : '';
            $file_data		= !empty( $_POST['file_info'] ) ? $_POST['file_info'] : '';
            
            if (empty($receiver_id)) {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Receiver user ID is missing', 'workreap_core');
                wp_send_json($json);
            }

            if ( intval( $receiver_id ) === intval( $current_user->ID ) ) {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Something went wrong.', 'workreap_core');
                wp_send_json($json);
            }

			/*$file_detail   = pathinfo($name_name);
            $extension 	   = $file_detail['extension']; 
			$file 	       = explode('^^',base64_decode(strrev($new_file)));
			$filename      = strrev($file[0]).'.png'; */

            if (empty($file_data)) {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Attachment is empty.', 'workreap_core');
                wp_send_json($json);
            }
			
			//rename file to server
			$upload 			= wp_upload_dir();
            $upload_url 		= $upload['baseurl'];
			$upload_dir 		= $upload['basedir'];
			$filename			= $file_data['file_name']; 
			$file 				= $upload_url . '/chat_attachments/'.$filename;

			$file_detail            = pathinfo($file);
			$extension 			    = $file_detail['extension'];
            $filename 			    = $file_detail['filename'];
			$reverse_file_name      = strrev($file_detail['filename']);
			$timestamp				= current_time( 'timestamp' );
			$new_file          		= strrev(base64_encode($reverse_file_name.'^^wtkeychat_attachment^^'.$timestamp));
			$new_file_name          = $new_file. '.' . $extension;
			
			$old_name				= $upload_dir . '/chat_attachments/'.$file_data['file_name'];
			$name_name				= $upload_dir . '/chat_attachments/'.$new_file_name;
			
			rename ($old_name, $name_name);
			
			$file_data['chat_hashname']	= $new_file_name;
			$file_data['timestamp']		= $timestamp;
			
			
            $senderId   = $current_user->ID;
            $receiverId = intval($receiver_id);

            //Prepare Insert Message Data Array
            $current_time  = current_time('mysql');
            $gmt_time      = get_gmt_from_date($current_time);

            $insert_data = array(
                'sender_id' 		=> $senderId,
                'receiver_id' 		=> $receiverId,
                'chat_message' 		=> serialize($file_data),
                'status' 			=> 1,
                'timestamp' 		=> time(),
                'time_gmt' 			=> $gmt_time,
            );

            $msg_id = self::getUsersThreadListData($senderId, $receiverId, 'insert_msg', $insert_data, '');

            if (!empty($msg_id)) {
				
                $fetchRecentThread = self::getUsersThreadListData('', '', 'fetch_recent_thread', array(), $msg_id);

                $message = !empty( $fetchRecentThread['chat_message'] ) ?  unserialize($fetchRecentThread['chat_message']) : array();
                $date = !empty($fetchRecentThread['time_gmt']) ?  date_i18n(get_option('date_format'), strtotime($fetchRecentThread['time_gmt'])) : '';
                $chat_nodes[0]['chat_avatar'] 		    = self::getUserInfoData('avatar', $fetchRecentThread['sender_id'], array('width' => 100, 'height' => 100));
                $chat_nodes[0]['chat_username'] 	    = self::getUserInfoData('username', $fetchRecentThread['sender_id'], array());
                $chat_nodes[0]['chat_filename'] 		= esc_attr($message['file_name']);
				$chat_nodes[0]['chat_hashname'] 		= esc_attr($new_file_name);
                $chat_nodes[0]['chat_filesize'] 		= esc_attr($message['file_size']);
                $chat_nodes[0]['chat_filetype'] 		= esc_attr($message['file_type']);
                $chat_nodes[0]['chat_date'] 		    = $date;
                $chat_nodes[0]['chat_id'] 			    = intval($fetchRecentThread['id']);
                $chat_nodes[0]['chat_current_user_id']  = intval($senderId);
                $chat_nodes[0]['chat_is_sender']        = 'yes';
				
				//excerpt
				if (strlen($message['file_name']) > 40) {
                    $message['file_name'] = substr($message['file_name'], 0, 40);
                }
                
                $json['type'] 			= 'success';
                $json['msg_type']       = $msg_type;
                $json['chat_nodes'] 	= $chat_nodes;
				
				$chat_nodes[0]['chat_is_sender']   =  'no';
                $json['chat_nodes_receiver'] 	= $chat_nodes;
				$json['chat_receiver_id'] 		= intval($receiverId);
				$json['chat_sender_id'] 		= intval($senderId);
                $json['last_id'] 				= intval($msg_id);
                $json['mime_type']              = esc_html($message['file_type']);
                $json['chat_attachment'] 		= esc_url($upload_url.'/chat_attachments/'.esc_html($message['file_name']));
				
                $json['replace_recent_msg_user'] = self::getUserInfoData('username', $fetchRecentThread['receiver_id']);
                $json['replace_recent_msg'] = !empty($message['file_name']) ? 'Filename: '.html_entity_decode( stripslashes($message['file_name']),ENT_QUOTES ) : '';
                $json['message'] = esc_html__('Message sent!', 'workreap_core');
                wp_send_json($json);
            }
        }

        /**
         * Send user message function
         *
         * @return void
         */
        public static function sendUserMessage() 
		{
            global $current_user;
            $json = array();
            $chat_nodes = array();
			$receiver_id	= !empty( $_POST['receiver_id'] ) ? intval($_POST['receiver_id']) : '';
            $project_id		= !empty( $_POST['project_id'] ) ? intval($_POST['project_id']) : '';
            $invite_type	= !empty( $_POST['invite_type'] ) ? esc_attr($_POST['invite_type']) : '';
            $status			= !empty( $_POST['status'] ) && esc_attr( $_POST['status'] ) === 'read' ? 0 : 1;
            $msg_type		= !empty( $_POST['msg_type'] ) && esc_attr( $_POST['msg_type'] ) === 'modal' ? 'modal' : 'normals';
			$message		= !empty( $_POST['message'] ) ? esc_textarea( $_POST['message'] ) : '';
			
			$do_check = check_ajax_referer('ajax_nonce', 'security', false);
			if ( $do_check == false ) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap_core');
				wp_send_json( $json );
			}
			
            if (empty($receiver_id)) {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Receiver is not a valid user.', 'workreap_core');
                wp_send_json($json);
            }

            if ( intval( $receiver_id ) === intval( $current_user->ID ) ) {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Something went wrong.', 'workreap_core');
                wp_send_json($json);
            }

            if (empty($message)) {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Message field is required.', 'workreap_core');
                wp_send_json($json);
            }

            if (function_exists('fw_get_db_settings_option')) {
                $chat_api = fw_get_db_settings_option('chat');
            }

            $is_cometchat 	= false;
			$is_wpguppy 	= false;
            $comet_apikey = '';
            if (!empty($chat_api['gadget']) && $chat_api['gadget'] === 'cometchat') {
                $is_cometchat = true;
                $comet_apikey = get_option('atomchat_api_key');
            }elseif (!empty($chat_api['gadget']) && $chat_api['gadget'] === 'guppy') {
				$is_wpguppy = true;
			}

            $senderId   = $current_user->ID;
            $receiverId = intval($receiver_id);

            //Prepare Insert Message Data Array
            $current_time  = current_time('mysql');
            $gmt_time      = get_gmt_from_date($current_time);
			
			if (!empty($project_id)) {
				$link		= get_the_permalink($project_id);
                $message	= $message.' '.$link;
            }


            if ($is_cometchat) {
                //Prepare Params
                $params_array = array(
                    'senderId' 		=> $senderId,
                    'receiverId' 	=> $receiverId,
                    'message' 		=> $message,
                    'comet_api' 	=> $comet_apikey
                );
				
				$api_msg_response = self::createCometChatUser($params_array);
                $api_msg_response = self::initCometChatRequest($params_array);
                
                if ($api_msg_response && $api_msg_response->success->status == 2000) {
                    
                    //Update Invitation Data to receiver Meta
                    if(!empty($invite_type) && $invite_type === 'db_invite') {
                        update_post_meta( $project_id, '_project_invite', intval($receiver_id));
                    }

                    $json['type'] 		= 'success';
                    $json['msg_type'] 	= 'normal_comet';
                    $json['message'] 	= $api_msg_response->success->message;
                } else {
                    $json['type'] = 'error';
                    $json['msg_type'] = 'normal_comet';
                    $json['message'] = !empty($api_msg_response->failed->message) ? $api_msg_response->failed->message : esc_html__('Something went wrong with cometchat API.');
                }
            } else if ($is_wpguppy) {

				do_action('wpguppy_send_message_to_user',$senderId,$receiverId,$message);
				$json['type'] 			= 'success';
				$json['message'] 		= esc_html__('Message sent!', 'workreap_core');
			}else {
                $insert_data = array(
                    'sender_id' 		=> $senderId,
                    'receiver_id' 		=> $receiverId,
                    'chat_message' 		=> $message,
                    'status' 			=> $status,
                    'timestamp' 		=> time(),
                    'time_gmt' 			=> $gmt_time,
                );

                $msg_id = self::getUsersThreadListData($senderId, $receiverId, 'insert_msg', $insert_data, '');
            }

           
            $message_data	= $message;
			
			//Send offer email
			if( $msg_type ===  'modal' ){
				
				if (class_exists('Workreap_Email_helper') && !empty($project_id)) {
					if (class_exists('WorkreapSendOffer')) {
						$email_helper = new WorkreapSendOffer();
						$emailData 	  = array();
						
						$employer_id	= workreap_get_linked_profile_id($senderId);
						$freelancer_id	= workreap_get_linked_profile_id($receiverId);
                        //update invitation
                        $invitation_count 	= get_user_meta(intval($freelancer_id), '_invitation_count', true);
                        $invitation_count	= !empty($invitation_count) ? $invitation_count + 1 : 1;
                        update_post_meta( $freelancer_id, '_invitation_count', $invitation_count);
                        
						$emailData['freelancer_link'] 		= get_the_permalink( $freelancer_id );
						$emailData['freelancer_name'] 		= get_the_title($freelancer_id);
						$emailData['employer_link']       	= get_the_permalink( $employer_id );
						$emailData['employer_name'] 		= get_the_title($employer_id);
						$emailData['project_link']        	= !empty( $project_id ) ?  get_the_permalink( $project_id ) : '';
						$emailData['project_title']      	= !empty( $project_id ) ?  get_the_title( $project_id ) : '';
						$emailData['project_id']      		= $project_id;
						$emailData['employer_id']      		= $employer_id;
						$emailData['freelancer_id']      	= $freelancer_id;
						$emailData['message']      			= $message;
						$emailData['email_to']      		= get_userdata( $receiverId )->user_email;

						$email_helper->send_offer($emailData);
						
						
						//Push notification
						$push	= array();
						$push['freelancer_id']		= $receiverId;
						$push['employer_id']		= $senderId;
						$push['project_id']			= $project_id;
						$push['type']				= 'send_offer';
						
						$push['%freelancer_link%']	= $emailData['freelancer_link'];
						$push['%freelancer_name%']	= $emailData['freelancer_name'];
						$push['%employer_name%']	= $emailData['employer_name'] ;
						$push['%employer_link%']	= $emailData['employer_link'];
						$push['%project_title%']	= $emailData['project_title'] ;
						$push['%project_link%']		= $emailData['project_link'];
						
						$push['%replace_message%']	= $emailData['message'];

						do_action('workreap_user_push_notify',array($receiverId),'','pusher_frl_sendoffer_content',$push);
					}
				}
			}
			
			//Receiver chat notification
			$receiver_chat_notify = 'disable';
			if (function_exists('fw_get_db_settings_option')) {
				$receiver_chat_notify = fw_get_db_settings_option('receiver_chat_notify');
			}

			if (class_exists('Workreap_Email_helper') && $receiver_chat_notify === 'enable') {
				if (class_exists('WorkreapRecChatNotification')) {
					$email_helper = new WorkreapRecChatNotification();
					$emailData 	  = array();

					$sender_id  	= workreap_get_linked_profile_id($senderId);
					$receiver_id	= workreap_get_linked_profile_id($receiverId);

					$emailData['username'] 		        = get_the_title($receiver_id);
					$emailData['sender_name'] 		    = get_the_title($sender_id);
					$emailData['message']      			= !empty($message) ? $message : '';
					$emailData['email_to']      		= get_userdata($receiverId)->user_email;

					$email_helper->send_chat_notification($emailData);
				}
			}
			
            if (!empty($msg_id)) {

                $fetchRecentThread = self::getUsersThreadListData('', '', 'fetch_recent_thread', array(), $msg_id);
				
				$offset 	= (float) get_option( 'gmt_offset' );
				$seconds 	= intval( $offset * HOUR_IN_SECONDS );
				$timestamp 	= strtotime( $fetchRecentThread['time_gmt'] ) + $seconds;
				
                $message = !empty( $fetchRecentThread['chat_message'] ) ?  $fetchRecentThread['chat_message'] : '';
                $date = !empty($fetchRecentThread['time_gmt']) ?  human_time_diff($timestamp,current_time( 'U' ) ).' '.esc_html__('ago','workreap_core') : '';
                $chat_nodes[0]['chat_avatar'] 		= self::getUserInfoData('avatar', $fetchRecentThread['sender_id'], array('width' => 100, 'height' => 100));
                $chat_nodes[0]['chat_username'] 	= self::getUserInfoData('username', $fetchRecentThread['sender_id'], array());
                $chat_nodes[0]['chat_message'] 		= html_entity_decode( stripslashes($fetchRecentThread['chat_message']),ENT_QUOTES );
                $chat_nodes[0]['chat_date'] 		= $date;
                $chat_nodes[0]['chat_id'] 			= intval($fetchRecentThread['id']);
                $chat_nodes[0]['chat_current_user_id'] = intval($senderId);
                $chat_nodes[0]['chat_is_sender'] = 'yes';
				
				//excerpt
				if (strlen($message) > 40) {
                    $message = substr($message, 0, 40);
                }
                

                $json['type'] 			= 'success';
                $json['msg_type']       = $msg_type;
                $json['chat_nodes'] 	= $chat_nodes;
                
                $chat_nodes[0]['chat_is_sender']   =  'no';
                $json['chat_nodes_receiver'] 	= $chat_nodes;
                $json['chat_receiver_id'] 		= intval($receiverId);
                $json['chat_sender_id'] 		= intval($senderId);
                $json['last_id'] 				= intval($msg_id);
                
                $json['replace_recent_msg_user'] = self::getUserInfoData('username', $fetchRecentThread['receiver_id']);
                $json['replace_recent_msg'] = !empty($message) ? html_entity_decode( stripslashes($message),ENT_QUOTES ) : '';
                $json['message'] = esc_html__('Message sent!', 'workreap_core');
               
            }
			
			if( empty($project_id) ){
				//Push notification
				$push	= array();
				$push['sender_id']		= $senderId;
				$push['receiver_id']	= $receiverId;
				$push['%username%']		= workreap_get_username($receiverId);
				$push['%sender_name%']	= workreap_get_username($senderId);
				$push['%message%']		= $message_data;
				$push['type']			= 'inbox_message';

				$push['%replace_message%']		= $message_data;

				do_action('workreap_user_push_notify',array($receiverId),'','pusher_rec_chat_content',$push);
			}

            wp_send_json($json);
            
        }

        /**
         * Send Message to CometChat using REST API
         *
         * @param array $params
         * @return void
         */
        public static function initCometChatRequest($params = array())
        {

            extract($params);

            $endpoint = 'https://api.cometondemand.net/api/v2/sendMessage';
            $body = [
                'senderUID'  => intval($senderId),
                'receiverUID' => intval($receiverId),
                'message' => $message
            ];
            $options = array(
                'body'        => $body,
                'headers'     => array(
                    'api-key' => trim($comet_api)
                ),
                'timeout'     => 60,
                'redirection' => 5,
                'blocking'    => true,
                'httpversion' => '1.0',
                'sslverify'   => false,
                'data_format' => 'body',
            );
            
            $response	= wp_remote_post( esc_url($endpoint), $options );
            $response	= wp_remote_retrieve_body($response);
                
            return json_decode($response);
        }
		
		 /**
         * Send Message to CometChat using REST API
         *
         * @param array $params
         * @return void
         */
        public static function createCometChatUser($params = array())
        {

            extract($params);
			
			if(!empty($senderId)){
				$employer_id		= workreap_get_linked_profile_id($senderId);
				$endpoint 			= 'https://api.cometondemand.net/api/v2/createUser';
				$profileURL      	= get_the_permalink( $employer_id );
				$employer_name 		= get_the_title($employer_id);


				$body = [
					'UID'   		  => intval($senderId),
					'name' 			  => $employer_name,
					'avatarURL' 	  => '',
					'profileURL' 	  => $profileURL,
					'role' 	  	  	  => 'employers'
				];
				
				$options = array(
					'body'        => $body,
					'headers'     => array(
						'api-key' => trim($comet_api)
					),
					'timeout'     => 60,
					'redirection' => 5,
					'blocking'    => true,
					'httpversion' => '1.0',
					'sslverify'   => false,
					'data_format' => 'body',
				);

				$response	= wp_remote_post( esc_url($endpoint), $options );
			}
			
			if(!empty($receiverId)){
				$freelancer_id		= workreap_get_linked_profile_id($receiverId);
				$endpoint 			= 'https://api.cometondemand.net/api/v2/createUser';
				$profileURL      	= get_the_permalink( $freelancer_id );
				$freelancer_name 	= get_the_title($freelancer_id);


				$body = [
					'UID'   		  => intval($receiverId),
					'name' 			  => $freelancer_name,
					'avatarURL' 	  => '',
					'profileURL' 	  => $profileURL,
					'role' 	  	  	  => 'freelancers'
				];
				
				$options = array(
					'body'        => $body,
					'headers'     => array(
						'api-key' => trim($comet_api)
					),
					'timeout'     => 60,
					'redirection' => 5,
					'blocking'    => true,
					'httpversion' => '1.0',
					'sslverify'   => false,
					'data_format' => 'body',
				);

				$response	= wp_remote_post( esc_url($endpoint), $options );
			}
			
                
            return json_decode($response);
        }

        /**
         * Get Interval Chat History
         *
         * @return void
         */
        public static function getIntervalChatHistoryData()
        {
            $json = array();
			
			$senderID	= !empty( $_POST['sender_id'] ) ? intval($_POST['sender_id']) : '';
			$receiverID	= !empty( $_POST['receiver_id'] ) ? intval($_POST['receiver_id']) : '';
			$lastMsgId	= !empty( $_POST['last_msg_id'] ) ? intval($_POST['last_msg_id']) : '';
			
			$do_check = check_ajax_referer('ajax_nonce', 'security', false);
			if ( $do_check == false ) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap_core');
				wp_send_json( $json );
			}
			
            if ( !empty($_POST) && $senderID != $receiverID ) {
                $usersThreadData = self::getUsersThreadListData($senderID, $receiverID, 'fetch_interval_thread', array(), '');

                $chat_nodes = array();
				
                $last_id 	= '';
				$newchat    = false; 
				$last_message = '';
                if (!empty($usersThreadData)) {
                    foreach ($usersThreadData as $key => $val) {
                        $last_id = intval( $val['id'] );
						$newchat = true;

						//Update Chat Status in DB
						self::getUsersThreadListData($senderID, $receiverID, 'set_thread_status', array(), '');

						$chat_nodes[$key]['chat_is_sender'] 	= 'no';

						if ($val['sender_id'] == $senderID) {
							$chat_nodes[$key]['chat_is_sender'] = 'yes';
						}

						$date = !empty($val['time_gmt']) ?  date_i18n(get_option('date_format'), strtotime($val['time_gmt'])) : '';
						$chat_nodes[$key]['chat_avatar'] 		= self::getUserInfoData('avatar', $val['sender_id'], array('width' => 100, 'height' => 100));
						$chat_nodes[$key]['chat_username'] 		= self::getUserInfoData('username', $val['sender_id'], array());
						$chat_nodes[$key]['chat_message'] 		= html_entity_decode( stripslashes( $val['chat_message'] ),ENT_QUOTES) ;
						$chat_nodes[$key]['chat_date'] 			= $date;
						$chat_nodes[$key]['chat_id'] 			= intval($val['id']);
						$chat_nodes[$key]['chat_current_user_id'] = intval($senderID);

						$last_message = html_entity_decode( stripslashes($val['chat_message']),ENT_QUOTES);
                    }
					
					if( $newchat ){
						$json['type'] 		= 'success';
					} else{
						$json['type']       = 'error';
					}
                    
					//excerpt
					if (strlen($last_message) > 40) {
						$last_message = substr($last_message, 0, 40);
					}
					
					
                    $json['chat_nodes'] 	= $chat_nodes;
                    $json['last_id'] 		= intval( $last_id );
                    $json['receiver_id'] 	= $receiverID;
					$json['last_message'] 	= $last_message;
                    $json['message'] 		= esc_html__('Chat messages found!', 'workreap_core');
                    wp_send_json($json);
                }

            } else {
                $json['type']       = 'error';
                $json['message'] = esc_html__('Some error occur, please try again later', 'workreap_core');
                wp_send_json($json);
            }
        }

        /**
         * Delete chat message function
         *
         * @return void
         */
        public static function deleteChatMessage()
        {
            global $current_user;  
            $json = array();
            $messageID      = !empty( $_POST['msg_id'] ) ? intval($_POST['msg_id']) : '';
			$sender    	 	= !empty( $_POST['userid'] ) ? intval($_POST['userid']) : '';
			$receiver       = !empty( $_POST['currentid'] ) ? intval($_POST['currentid']) : '';
			$key     	    = !empty( $_POST['key'] ) ? $_POST['key'] : '';
			
			$do_check = check_ajax_referer('ajax_nonce', 'security', false);
			if ( $do_check == false ) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap_core');
				wp_send_json( $json );
			}
			
            //Validation
			if(!empty($key) && $key === 'single'){
				if ( empty($messageID) ) {
					$json['type'] = 'error';
					$json['message'] = esc_html__('Something went wrong.', 'workreap_core');
					wp_send_json($json);
				}
				
				 //Delete Thread Message
				$delete_array_data = array(
					"id"            => $messageID,              
				);

				self::getUsersThreadListData('', '', 'delete_thread_msg', $delete_array_data, $messageID);

			} else if(!empty($key) && $key === 'conversation'){
				
				if ( empty($messageID) ) {
					$json['type'] = 'error';
					$json['message'] = esc_html__('Something went wrong.', 'workreap_core');
					wp_send_json($json);
				}
				
				 //Delete Thread Message
				$delete_array_data = array($sender, $receiver);

				self::getUsersThreadListData('', '', 'delete_conversation_msg', $delete_array_data, '');

			} else{
				$delete_array_data = array();

				self::getUsersThreadListData('', '', 'delete_all_threads', $delete_array_data, '');
			}

            //Response
            $json['type']    = 'success';
            $json['message'] = esc_html__('Message deleted.', 'workreap_core');
            wp_send_json($json); 
        }

        /**
         * Get User Information
         *
         * @param string $type
         * @param string $userID
         * @return void
         */
        public static function getUserInfoData($type = '', $userID = '', $sizes = array()) 
        {
            $userinfo = '';
            $user_data = get_userdata($userID);
			$postId = workreap_get_linked_profile_id($userID);

            switch ($type) {
				case "avatar":
					
					if ( apply_filters('workreap_get_user_type', $userID) === 'employer' ) {
						$userinfo = apply_filters('workreap_employer_avatar_fallback', workreap_get_employer_avatar($sizes, $postId), $sizes);
					} else {
						$userinfo = apply_filters('workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar($sizes, $postId), $sizes);
					}

					break;
				case "username":
					$userinfo = workreap_get_username($userID);
					break;
				case "user_register":
					$userinfo = esc_html__('Member Since','workreap_core').'&nbsp;'.date_i18n(get_option('date_format'), strtotime($user_data->user_registered));
					break;
				case "url":
					$userinfo = get_the_permalink($postId);
					break;
            }

            return $userinfo;
        }
		
		/**
         * Get User Information
         *
         * @param string $type
         * @param string $userID
         * @return void
         */
        public static function countUnreadMessages($userID = '') 
        {
			
            $users_unread = self::getUsersThreadListData($userID,'','count_unread_msgs');
            echo !empty( $users_unread ) ? $users_unread  : '0';
        }
    }
	
    ChatSystem::instance();
}