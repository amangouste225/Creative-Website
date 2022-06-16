<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('WorkreapProposalMessage')) {

    class WorkreapProposalMessage extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send freelancer proposal message
		 *
		 * @since 1.0.0
		 */
		public function send_proposal_message_freelancer($params = '') {
			extract($params);
			$subject_default = esc_html__('New Proposal Message Received', 'workreap_core');
			$email_default = 'Hello %freelancer_name%<br/><br/>
								You have received a new message!<br/><br/>
								The <a href=" %employer_link%">%employer_name%</a> has submitted a new message on this job <a href="%project_link%">%project_title%</a><br/><br/>
								Message: %message%<br/>
								%signature%';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('frl_proposal_msg_subject');
				$email_content  = fw_get_db_settings_option('frl_proposal_msg_content');
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
			
			$email_content = str_replace("%freelancer_link%", $freelancer_link, $email_content); 
			$email_content = str_replace("%freelancer_name%", $freelancer_name, $email_content); 
			$email_content = str_replace("%employer_name%", $employer_name, $email_content); 
            $email_content = str_replace("%employer_link%", $employer_link, $email_content); 
            $email_content = str_replace("%project_link%", $job_link, $email_content); 
            $email_content = str_replace("%project_title%", $job_title, $email_content); 
            $email_content = str_replace("%message%", $proposal_msg, $email_content); 
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';

            $body 	 .= $this->prepare_email_footers();
            $email_to = $freelancer_email;
			wp_mail($email_to, $subject, $body);
        }	
        
        /**
		 * @Send employer proposal message
		 *
		 * @since 1.0.0
		 */
		public function send_proposal_message_employer($params = '') {
			extract($params);
			$subject_default = esc_html__('Proposal Message Received', 'workreap_core');
			$email_default = 'Hello %employer_name%<br/>
								<a href=" %freelancer_link%">%freelancer_name%</a> has send you a new message on this job <a href="%project_link%">%project_title%</a>.<br/>

								Message: %message%
								<br/>
								%signature%,<br/>';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('emp_proposal_msg_subject');
				$email_content  = fw_get_db_settings_option('emp_proposal_msg_content');
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
			
			$email_content = str_replace("%freelancer_link%", $freelancer_link, $email_content); 
			$email_content = str_replace("%freelancer_name%", $freelancer_name, $email_content); 
			$email_content = str_replace("%employer_name%", $employer_name, $email_content); 
            $email_content = str_replace("%employer_link%", $employer_link, $email_content); 
            $email_content = str_replace("%project_link%", $job_link, $email_content); 
            $email_content = str_replace("%project_title%", $job_title, $email_content); 
            $email_content = str_replace("%message%", $proposal_msg, $email_content); 
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';
            $body .= $this->prepare_email_footers();
            $email_to = $employer_email;
			wp_mail($email_to, $subject, $body);
		}
	}

	new WorkreapProposalMessage();
}