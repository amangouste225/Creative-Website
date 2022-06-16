<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('WorkreapServicePost')) {

    class WorkreapServicePost extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send report user email
		 *
		 * @since 1.0.0
		 */
		public function send_admin_service_post($params = '') {
			extract($params);
			$status	= !empty( $status ) ? $status : '';
			$subject_default = esc_html__('Service Posted', 'workreap_core');
			$email_default = 'Hello
								A new service has been posted by %freelancer_name%
								Click to view the service link. %service_title%

								%signature%,';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('admin_service_post_subject');
				$email_to 		= fw_get_db_settings_option('admin_service_post_email');
				$email_content  = fw_get_db_settings_option('admin_service_post_content');
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
			if( empty( $email_to ) || !is_email( $email_to ) ){
				$email_to = get_option('admin_email', 'somename@example.com');
			}
			
			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%freelancer_name%", $freelancer_name, $email_content); 
			$email_content = str_replace("%freelancer_link%", $freelancer_link, $email_content); 
			$email_content = str_replace("%service_title%", $service_title, $email_content);
			$email_content = str_replace("%service_link%", $service_link, $email_content); 
			$email_content = str_replace("%status%", $status, $email_content);
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

		/**
		 * @Rejection email to Freelancer
		 *
		 * @since 1.0.0
		 */
		public function reject_service_verification($params = '') {
			extract($params);
			$subject_default = esc_html__('Your service has rejected', 'workreap_core');
			$contact_default = 'Hello %user_name%<br/>
										
								Your service has rejected.<br/>
								%admin_message%
								<br/>
								%signature%';

			if (function_exists('fw_get_db_settings_option')) {
				$subject = fw_get_db_settings_option('admin_service_reject_subject');
				$email_content = fw_get_db_settings_option('admin_service_rejected_content');
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
			$email_content = str_replace("%service_title%", $service_title, $email_content); 
			$email_content = str_replace("%service_link%", $service_link, $email_content); 
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
		
		/**
		 * @Send report user email
		 *
		 * @since 1.0.0
		 */
		public function send_freelancer_service_post($params = '') {
			extract($params);
			$status	= !empty( $status ) ? $status : '';
			$subject_default = esc_html__('Congratulations! Your Service Has Posted', 'workreap_core');
			$email_default = 'Hello %freelancer_name%,<br/>
								Congratulation! Your service has been posted.<br/>
								Click below link to view the service. <a href="%service_link%" target="_blank">%service_title%</a><br/>
								<br/>
								%signature%,<br/>';
			
			
			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('freelancer_service_post_subject');
				$email_content  = fw_get_db_settings_option('freelancer_service_post_content');
			}

			//Set Default Subject
			if( empty( $subject ) ){
				$subject = $subject_default;
			}

			//set defalt contents
			if (empty($email_content)) {
				$email_content = $email_default;
            }
            
			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%freelancer_name%", $freelancer_name, $email_content); 
			$email_content = str_replace("%freelancer_link%", $freelancer_link, $email_content); 
			$email_content = str_replace("%service_title%", $service_title, $email_content); 
			$email_content = str_replace("%service_link%", $service_link, $email_content);
			$email_content = str_replace("%status%", $status, $email_content);
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';

            $body .= $this->prepare_email_footers();
			$email_to = $freelancer_email;						           
			wp_mail($email_to, $subject, $body);
		}
		
	}

	new WorkreapServicePost();
}