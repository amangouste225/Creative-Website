<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('Workreap_Published')) {

    class Workreap_Published extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Approve freelancer & Employer Profiles
		 *
		 * @since 1.0.0
		 */
		public function publish_approve_user_acount($params = '') {

			extract($params);
			$subject_default = esc_html__('Account Approved!', 'workreap_core');
			$email_default   = 'Hello %name%<br/>
							Your account has been approved. You can now login to setup your profile.
							
							<a href="%site_url%">Login Now</a>

							%signature%';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('user_approve');
				$email_content  = fw_get_db_settings_option('user_approve_content');
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
			
			$email_content = str_replace("%site_url%", $site_url, $email_content); 
			$email_content = str_replace("%name%", $name, $email_content); 
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';

            $body 		.= $this->prepare_email_footers();
			wp_mail($email_to, $subject, $body);
		}
		
		/**
		 * @Approve project
		 *
		 * @since 1.0.0
		 */
		public function publish_approve_project($params = '') {

			extract($params);
			$subject_default = esc_html__('Your project has published!', 'workreap_core');
			$email_default   = 'Hello %name%
											
								Congratulations! 

								Your Project <a href="%link%">%project_title%</a> has been published.

								%signature%,';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('job_approved');
				$email_content  = fw_get_db_settings_option('job_approved_content');
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
			
			$email_content = str_replace("%project_name%", $project_name, $email_content); 
			$email_content = str_replace("%link%", $link, $email_content); 
			$email_content = str_replace("%name%", $name, $email_content); 
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';

            $body 		.= $this->prepare_email_footers();
			wp_mail($email_to, $subject, $body);
		}
		
		/**
		 * @Approve service
		 *
		 * @since 1.0.0
		 */
		public function publish_approve_service($params = '') {
			extract($params);
			$subject_default = esc_html__('Your service has published!', 'workreap_core');
			$email_default   = 'Hello %name%
											
								Congratulations! 

								Your Service <a href="%link%">%project_title%</a> has been published.

								%signature%,';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('service_approved');
				$email_content  = fw_get_db_settings_option('service_approved_content');
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
			
			$email_content = str_replace("%service_name%", $service_name, $email_content); 
			$email_content = str_replace("%link%", $link, $email_content); 
			$email_content = str_replace("%name%", $name, $email_content); 
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';

            $body 		.= $this->prepare_email_footers();
			wp_mail($email_to, $subject, $body);
		}
	}

	new Workreap_Published();
}