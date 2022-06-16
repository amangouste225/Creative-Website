<?php
/**
 * Email Helper To Send Email for Getting Password
 * @since    1.0.0
 */
if (!class_exists('WorkreapEmployerEmail')) {

    class WorkreapEmployerEmail extends Workreap_Email_helper{

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
			$subject_default = esc_html__('A New Proposal Received!', 'workreap_core');
			$contact_default = 'Hello %name%,<br/><br/>
                                A freelancer <a href="%freelancer_link%">%freelancer_name%</a> has sent a new proposal <a href="%proposal_link%">%proposal_title%</a> on the following project <a href="%project_link%">%project_title%</a>.
                                Message is given below. <br/>
                                Project Amount : %amount%<br/>
                                Project Time : %time%<br/>
                                Message: %message%
                                <br/>
                                %signature%,<br/>';

			if (function_exists('fw_get_db_settings_option')) {
				$subject = fw_get_db_settings_option('receive_proposal_subject');
				$email_content = fw_get_db_settings_option('receive_proposal_content');
				$email_to = fw_get_db_settings_option('receive_proposal_email');
			}

			//Set Default Subject
			if( empty( $subject ) ){
				$subject = $subject_default;
			}

			//set defalt contents
			if (empty($email_content)) {
				$email_content = $contact_default;
			}    

			//set defalt email
			if (empty($email_to)) {
				$email_to = $email;
			}    

			//Email Sender information
			$sender_info = $this->process_sender_information();                

			$email_content = str_replace("%name%", $name, $email_content); 
			$email_content = str_replace("%freelancer_link%", $freelancer_link, $email_content); 
			$email_content = str_replace("%proposal_link%", $proposal_link, $email_content); 
			$email_content = str_replace("%project_link%", $project_link, $email_content); 
			$email_content = str_replace("%freelancer_name%", $freelancer_name, $email_content); 
			$email_content = str_replace("%proposal_title%", $proposal_title, $email_content); 
			$email_content = str_replace("%project_title%", $project_title, $email_content);
			$email_content = str_replace("%amount%", $amount, $email_content); 
			$email_content = str_replace("%time%", $time, $email_content); 
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

	new WorkreapEmployerEmail();
}