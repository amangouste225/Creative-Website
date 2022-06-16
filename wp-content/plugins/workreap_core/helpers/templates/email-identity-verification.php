<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('WorkreapIdentityVerification')) {

    class WorkreapIdentityVerification extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send identity verification to admin
		 *
		 * @since 1.0.0
		 */
		public function send_verification_to_admin($params = '') {
			extract($params);
			$subject_default = esc_html__('You have received a new identity verification request', 'workreap_core');
			$contact_default = 'Hello,<br/>
								You have received a new identity verification from the %user_name%, detail is given below<br/>

								You can click <a href="%user_link%">this link</a> to verify this user identity

								<br/>
								%signature%';

			if (function_exists('fw_get_db_settings_option')) {
				$email = fw_get_db_settings_option('identity_email');
				$subject = fw_get_db_settings_option('identity_subject');
				$email_content = fw_get_db_settings_option('identity_content');
			}

			//Set Default Subject
			if( empty( $subject ) ){
				$subject = $subject_default;
			}

			//set defalt contents
			if (empty($email_content)) {
				$email_content = $contact_default;
			}                       
			
			//set defalt admin email
			if( empty( $email ) ){
				$email = get_option('admin_email', 'somename@example.com');
			}
			
			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%user_name%", $user_name, $email_content); 
			$email_content = str_replace("%user_link%", $user_link, $email_content); 
			$email_content = str_replace("%user_email%", $user_email, $email_content); 
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
		
		/**
		 * @Verification email to Freelancer
		 *
		 * @since 1.0.0
		 */
		public function approve_identity_verification($params = '') {
			extract($params);
			$subject_default = esc_html__('Congratulation! your identity verification has been approved', 'workreap_core');
			$contact_default = 'Hello %user_name%<br/>
                                            
								Congratulations!<br/>
								Your submitted documents for the identity verification has been approved.

								<br/>
								%signature%';

			if (function_exists('fw_get_db_settings_option')) {
				$subject = fw_get_db_settings_option('identity_approve_subject');
				$email_content = fw_get_db_settings_option('identity_approve_content');
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
			
			$email_content = str_replace("%user_name%", $user_name, $email_content); 
			$email_content = str_replace("%user_link%", $user_link, $email_content); 
			$email_content = str_replace("%user_email%", $user_email, $email_content); 
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';

			$body .= $this->prepare_email_footers();											           
			wp_mail($user_email, $subject, $body);
		}
		
		/**
		 * @Rejection email to Freelancer
		 *
		 * @since 1.0.0
		 */
		public function reject_identity_verification($params = '') {
			extract($params);
			$subject_default = esc_html__('Your request for identity verification has rejected', 'workreap_core');
			$contact_default = 'Hello %user_name%<br/>
										
								You uploaded document for identity verification has been rejection.<br/>
								%admin_message%
								<br/>
								%signature%';

			if (function_exists('fw_get_db_settings_option')) {
				$subject = fw_get_db_settings_option('identity_reject_subject');
				$email_content = fw_get_db_settings_option('identity_reject_content');
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
			
			$email_content = str_replace("%user_name%", $user_name, $email_content); 
			$email_content = str_replace("%user_link%", $user_link, $email_content); 
			$email_content = str_replace("%user_email%", $user_email, $email_content); 
			$email_content = str_replace("%admin_message%", $admin_message, $email_content); 
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';

			$body .= $this->prepare_email_footers();											           
			wp_mail($user_email, $subject, $body);
		}

	}

	new WorkreapIdentityVerification();
}