<?php
if (!class_exists('Workreap_MailChimp')) {

    class Workreap_MailChimp {

        function __construct() {
            add_action('wp_ajax_nopriv_workreap_subscribe_mailchimp', array(&$this, 'workreap_subscribe_mailchimp'));
            add_action('wp_ajax_workreap_subscribe_mailchimp', array(&$this, 'workreap_subscribe_mailchimp'));
        }

        public function workreap_mailchimp_form($class = '') {
            $counter = 0;
            if (function_exists('fw_get_db_settings_option')) {
                $footer_text = fw_get_db_settings_option('mailchimp_title');
            };
			
            $footer_text = !empty($footer_text) ? $footer_text : esc_html__('Join Our Free Newsletter:', 'workreap_core');
            $counter++;
            ?>
            <form class="wt-formtheme wt-formnewslettervtwo">
                <fieldset>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="<?php esc_attr_e('Add your email', 'workreap_core'); ?>">
                    </div>
                    <button type="submit" class="wt-btn subscribe_me" data-counter="<?php echo intval($counter); ?>"><?php esc_html_e('Subscribe', 'workreap_core'); ?></button>
                </fieldset>
            </form>
            <?php
        }

        /**
         * @get Mail chimp list
         *
         */
        public function workreap_mailchimp_list($apikey) {
			if ( $apikey <> '' && $apikey !== 'Add your key here' ) {
				$apikey	= $apikey;
			} else{
				return '';
			}
			
            $MailChimp = new Workreap_OATH_MailChimp($apikey);
            $mailchimp_list = $MailChimp->workreap_call('lists/list');
            return $mailchimp_list;
        }

        /**
         * @get Mail chimp list
         *
         */
        public function workreap_subscribe_mailchimp() {
            global $counter;
			
			//security check
			$do_check = check_ajax_referer('ajax_nonce', 'security', false);
			if ( $do_check == false ) {
				$json['type'] = 'error';
				$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'workreap_core');
				wp_send_json( $json );
			}
			
            $mailchimp_key = '';
            $mailchimp_list = '';
            $json = array();

            if (function_exists('fw_get_db_settings_option')) :
                $mailchimp_key = fw_get_db_settings_option('mailchimp_key');
                $mailchimp_list = fw_get_db_settings_option('mailchimp_list');
            endif;

            if (empty($_POST['email'])) {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Email address is required.', 'workreap_core');
                echo json_encode($json);
                die();
            }

            if (isset($_POST['email']) && !empty($_POST['email']) && $mailchimp_key != '') {
                if (!empty( $mailchimp_key )) {
                    $MailChimp = new Workreap_OATH_MailChimp($mailchimp_key);
                } else{
					$json['type'] = 'error';
                	$json['message'] = esc_html__('Some error occur,please try again later.', 'workreap_core');
					echo json_encode($json);
					die();
				}

                $email = $_POST['email'];
                if (isset($_POST['fname']) && !empty($_POST['fname'])) {
                    $fname = $_POST['fname'];
                } else {
                    $fname = '';
                }

                if (isset($_POST['lname']) && !empty($_POST['lname'])) {
                    $lname = $_POST['lname'];
                } else {
                    $lname = '';
                }

                if (trim($mailchimp_list) == '') {
                    $json['type'] = 'error';
                    $json['message'] = esc_html__('No list selected yet! please contact administrator', 'workreap_core');
                    echo json_encode($json);
                    die;
                }
				
                $result = $MailChimp->workreap_call('lists/subscribe', array(
                    'id' 			=> $mailchimp_list,
                    'email' 		=> array('email' => $email),
                    'merge_vars' 	=> array('FNAME' => $fname, 'LNAME' => $lname),
                    'double_optin'	=> false,
                    'update_existing' 	=> false,
                    'replace_interests' => false,
                    'send_welcome' 		=> true,
                ));
				
                if ($result <> '') {
                    if (isset($result['status']) and $result['status'] == 'error') {
                        $json['type'] = 'error';
                        $json['message'] = $result['error'];
                    } else {
                        $json['type'] = 'success';
                        $json['message'] = esc_html__('Successfully subscribed', 'workreap_core');
                    }
                }
            } else {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Some error occur,please try again later.', 'workreap_core');
            }
			
            echo json_encode($json);
            die();
        }

    }

    new Workreap_MailChimp();
}