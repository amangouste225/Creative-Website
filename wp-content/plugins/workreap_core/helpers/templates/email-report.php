<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('WorkreapReportUser')) {

    class WorkreapReportUser extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send report user email
		 *
		 * @since 1.0.0
		 */
		public function send_employer_report($params = '') {
			extract($params);
			$subject_default = esc_html__('Employer Reported', 'workreap_core');
			$email_default = 'Hello,<br/>
								An employer "%reported_employer%" has been reported by %reported_by%<br/>
								Message is given below. <br/>
								%message%
								<br/>
								%signature%,<br/>';

			if (function_exists('fw_get_db_settings_option')) {
				$subject = fw_get_db_settings_option('report_emp_subject');
				$email_to = fw_get_db_settings_option('report_emp_email');
				$email_content = fw_get_db_settings_option('report_emp_content');
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
			
			$email_content = str_replace("%reported_employer%", $name, $email_content); 
			$email_content = str_replace("%reported_by%", $reported_by, $email_content); 
			$email_content = str_replace("%employer_link%", $employer_link, $email_content); 
			$email_content = str_replace("%user_link%", $user_link, $email_content); 
			$email_content = str_replace("%message%", $message, $email_content); 
			$email_content = str_replace("%reported_title%", $reported_title, $email_content); 
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
		 * @Send report user email
		 *
		 * @since 1.0.0
		 */
		public function send_project_report($params = '') {
			extract($params);
			$subject_default = esc_html__('Project Reported', 'workreap_core');
			$email_default = 'Hello,<br/>
								A project "%reported_project%" has been reported by %reported_by%<br/>
								Message is given below. <br/>
								%message%
								<br/>
								%signature%,<br/>';
			
			
			if (function_exists('fw_get_db_settings_option')) {
				$subject = fw_get_db_settings_option('report_pro_subject');
				$email_to = fw_get_db_settings_option('report_pro_email');
				$email_content = fw_get_db_settings_option('report_pro_content');
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
			
			$email_content = str_replace("%reported_project%", $name, $email_content); 
			$email_content = str_replace("%reported_by%", $reported_by, $email_content); 
			$email_content = str_replace("%project_link%", $project_link, $email_content); 
			$email_content = str_replace("%user_link%", $user_link, $email_content);
			$email_content = str_replace("%reported_title%", $reported_title, $email_content); 
			$email_content = str_replace("%message%", $message, $email_content); 
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
		 * @Send report user email
		 *
		 * @since 1.0.0
		 */
		public function send_freelancer_report($params = '') {
			extract($params);
			$subject_default = esc_html__('A freelancer has reported!', 'workreap_core');
			$email_default = 'Hello,<br/>
								A freelancer "%reported_freelancer%" has been reported by "%reported_by%"<br/>
								Message is given below. <br/>
								%message%
								<br/>
								%signature%,<br/>';

			if (function_exists('fw_get_db_settings_option')) {
				$subject = fw_get_db_settings_option('report_fre_subject');
				$email_to = fw_get_db_settings_option('report_fre_email');
				$email_content = fw_get_db_settings_option('report_fre_content');
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
			
			$email_content = str_replace("%reported_freelancer%", $name, $email_content); 
			$email_content = str_replace("%reported_by%", $reported_by, $email_content); 
			$email_content = str_replace("%freelancer_link%", $freelancer_link, $email_content); 
			$email_content = str_replace("%user_link%", $user_link, $email_content); 
			$email_content = str_replace("%message%", $message, $email_content);
			$email_content = str_replace("%reported_title%", $reported_title, $email_content); 
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
		 * @Send report user email
		 *
		 * @since 1.0.0
		 */
		public function send_service_report($params = '') {
			extract($params);
			$subject_default = esc_html__('A Service has reported!', 'workreap_core');
			$email_default = 'Hello,<br/>
								A freelancer service "%reported_service%" has been reported by "%reported_by%"<br/>
								Message is given below. <br/>
								%message%
								<br/>
								%signature%,<br/>';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('report_serv_subject');
				$email_to 		= fw_get_db_settings_option('report_serv_email');
				$email_content 	= fw_get_db_settings_option('report_serv_content');
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
			
			$email_content = str_replace("%reported_service%", $name, $email_content); 
			$email_content = str_replace("%reported_by%", $reported_by, $email_content); 
			$email_content = str_replace("%service_link%", $service_link, $email_content); 
			$email_content = str_replace("%user_link%", $user_link, $email_content); 
			$email_content = str_replace("%message%", $message, $email_content); 
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

	new WorkreapReportUser();
}