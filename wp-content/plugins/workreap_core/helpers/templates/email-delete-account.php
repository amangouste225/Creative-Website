<?php
/**
 * Email Helper To Delete Account
 * @since    1.0.0
 */
if (!class_exists('WorkreapDeleteAccount')) {

    class WorkreapDeleteAccount extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send report user email
		 *
		 * @since 1.0.0
		 */
		public function send($params = '') {
			extract($params);
			$subject_default = esc_html__('Account Deleted', 'workreap_core');
			$email_default = 'Hi,<br/>

								An existing user has deleted the account due to the following reason: 
								<br/>
								%reason%
								<br/><br/>
								%signature%,<br/>';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('delete_subject');
				$email_to 		= fw_get_db_settings_option('delete_email');
				$email_content 	= fw_get_db_settings_option('delete_content');
			}

			//Set Default Subject
			if( empty( $subject ) ){
				$subject = $subject_default;
			}

			//set defalt contents
			if (empty($email_content)) {
				$email_content = $email_default;
			}                       
			
			//set defalt admin email
			if( empty( $email_to ) ){
				$email_to = get_option('admin_email', 'somename@example.com');
			}
			
			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%username%", $username, $email_content); 
			$email_content = str_replace("%message%", $description, $email_content); 
			$email_content = str_replace("%reason%", $reason, $email_content); 
			$email_content = str_replace("%email%", $email, $email_content); 
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';

			$body .= $this->prepare_email_footers();											           
			wp_mail($email_to, $subject, $body);
		}
		
	}

	new WorkreapDeleteAccount();
}