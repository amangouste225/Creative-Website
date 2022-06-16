<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('WorkreapPostStatus')) {

    class WorkreapPostStatus extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send welcome freelancer email
		 *
		 * @since 1.0.0
		 */
		public function send($params = '') {
			extract($params);

			if (function_exists('fw_get_db_settings_option')) {
				$subject = fw_get_db_settings_option('status_subject');
				$email 	 = fw_get_db_settings_option('status_email');
				$email_content = fw_get_db_settings_option('status_content_'.$type);
			}
			
			if(!empty($subject) && !empty($email_content) && !empty($email) && is_email($email)){                     

				$sender_info = $this->process_sender_information();

				$email_content = str_replace("%name%", $name, $email_content); 
				$email_content = str_replace("%post_link%", $post_link, $email_content); 
				$email_content = str_replace("%signature%", $sender_info, $email_content);

				$body = '';
				$body .= $this->prepare_email_headers();

				$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
				$body .= '<div style="width: 100%; float: left;">';
				$body .= wpautop( $email_content );
				$body .= '</div>';
				$body .= '</div>';
				$body .= $this->prepare_email_footers();											           
				wp_mail($email, $subject, $body);
			}
		}	
		
	}

	new WorkreapPostStatus();
}