<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('WorkreapMilestoneRequest')) {

    class WorkreapMilestoneRequest extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send report user email
		 *
		 * @since 1.0.0
		 */
		public function send_milestone_request_email($params = '') {
			extract($params);
			$subject_default = esc_html__('Milestones request received.', 'workreap_core');
			$email_default = 'Hello %freelancer_name%,<br/><br/>
								Employer <a href="%employer_link%">%employer_name%</a> has created milestones for the project <a href="%project_link%">%project_title%</a>. You can accept or reject the employer request for project.
								%signature%,<br/>';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('ml_rec_subject');
				$email_content 	= fw_get_db_settings_option('ml_rec_content');
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
			$email_content = str_replace("%employer_name%", $employer_name, $email_content); 
            $email_content = str_replace("%employer_link%", $employer_link, $email_content); 
            $email_content = str_replace("%project_link%", $project_link, $email_content); 
            $email_content = str_replace("%project_title%", $project_title, $email_content); 
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
		public function send_milestone_request_approved_email($params = '') {
			extract($params);
			$subject_default = esc_html__('Milestone Request Approved.', 'workreap_core');
			$email_default = 'Hello %employer_name%,<br/><br/>

							 Your request for milestone on the project <a href="%project_link%">%project_title%</a> has been approved<br/>
							 by freelancer <a href="%freelancer_link%">%freelancer_name%</a>.<br/>
							 Please login to see the details of milestone.<br/><br/>
							 
							 %signature%';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('ml_req_appr_subject');
				$email_content 	= fw_get_db_settings_option('ml_req_appr_content');
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
			$email_content = str_replace("%employer_name%", $employer_name, $email_content); 
            $email_content = str_replace("%project_link%", $project_link, $email_content); 
            $email_content = str_replace("%project_title%", $project_title, $email_content);
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
		public function send_milestone_request_rejected_email($params = '') {
			extract($params);
			$subject_default = esc_html__('Milestone Request Rejected.', 'workreap_core');
			$email_default = 'Hello %employer_name%,<br/><br/>

							 Your request for milestone on the project <a href="%project_link%">%project_title%</a> has been rejected<br/>
							 by freelancer <a href="%freelancer_link%">%freelancer_name%</a>.<br/>
							 Reason: %reason%.<br/><br/>
							 
							 %signature%';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('ml_req_rej_subject');
				$email_content 	= fw_get_db_settings_option('ml_req_rej_content');
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
			$email_content = str_replace("%employer_name%", $employer_name, $email_content); 
            $email_content = str_replace("%project_link%", $project_link, $email_content); 
            $email_content = str_replace("%project_title%", $project_title, $email_content);
            $email_content = str_replace("%reason%", $reason, $email_content);
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
		public function send_hired_against_milestone_to_freelancer_email($params = '') {
			extract($params);
			$subject_default = esc_html__('.', 'workreap_core');
			$email_default = 'Hello %freelancer_name%,<br/><br/>
								You have been hired for the milestone <strong>%milestone_title%</strong> against the project <a href="%project_link%">%project_title%</a>.<br/>
								Please login to see the details of milestone.<br/><br/>

								%signature%';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('hired_ml_subject');
				$email_content 	= fw_get_db_settings_option('hired_ml_content');
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
			$email_content = str_replace("%milestone_title%", $milestone_title, $email_content); 
            $email_content = str_replace("%project_link%", $project_link, $email_content); 
            $email_content = str_replace("%project_title%", $project_title, $email_content);
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
		public function send_completed_milestone_to_freelancer_email($params = '') {
			extract($params);
			$subject_default = esc_html__('.', 'workreap_core');
			$email_default = 'Hello %freelancer_name%,<br/><br/>
							Congratulations!!
							Milestone <strong>%milestone_title%</strong> has been completed!!<br/><br/>
							 
							%signature%';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('ml_completed_subject');
				$email_content 	= fw_get_db_settings_option('ml_completed_content');
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
			$email_content = str_replace("%milestone_title%", $milestone_title, $email_content); 
			
			$email_content = str_replace("%project_link%", $project_link, $email_content); 
            $email_content = str_replace("%project_title%", $project_title, $email_content);
			
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

	new WorkreapMilestoneRequest();
}