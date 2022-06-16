<?php
/**
 * Email Helper To Send Email for Getting Password
 * @since    1.0.0
 */
if (!class_exists('WorkreapGetPassword')) {

    class WorkreapGetPassword extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send Generat Password Link
		 *
		 * @since 1.0.0
		 */
		public function send($params = '') {
			global $current_user;

			extract($params);

			$subject_default = esc_html__('Forgot Password', 'workreap_core');
			$contact_default = 'Hi %name%,<br/>
								Someone requested to reset the password of following account:<br/><br/>
								Email Address: %account_email%<br>
								If this was a mistake, just ignore this email and nothing will happen.

								To reset your password, click reset link below:<br/>
								<a href="%link%">Reset</a>

								%signature%';

			if (function_exists('fw_get_db_settings_option')) {
				$subject = fw_get_db_settings_option('lp_subject');
				$email_content = fw_get_db_settings_option('lp_content');
			}

			//Set Default Subject
			if( empty( $subject ) ){
				$subject = $subject_default;
			}

			//set defalt contents
			if (empty($email_content)) {
				$email_content = $contact_default;
			}                       

			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%name%", $username, $email_content); 
			$email_content = str_replace("%account_email%", $email, $email_content); 
			$email_content = str_replace("%link%", $link, $email_content); 
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';
			$body .= $this->prepare_email_footers();									
			$email_to = $email;											          
			wp_mail($email_to, $subject, $body);
		}
		
	}

	new WorkreapGetPassword();
}