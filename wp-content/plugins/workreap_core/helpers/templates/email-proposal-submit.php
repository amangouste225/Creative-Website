<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('WorkreapProposalSubmit')) {

    class WorkreapProposalSubmit extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send report user email
		 *
		 * @since 1.0.0
		 */
		public function send_employer_proposal_submit($params = '') {
			extract($params);
			$subject_default = esc_html__('Proposal Received', 'workreap_core');
			$email_default = 'Hello %employer_name%,<br/>
                    <a href="%freelancer_link%">%freelancer_name%</a> has sent a new proposal on the following project <a href="%project_link%">%project_title%</a>.<br/>
                    Message is given below. <br/>
                    Project Proposal Amount : %proposal_amount%<br/>
                    Project Duration : %proposal_duration%<br/>
                    Message: %message%
                    <br/>
                    %signature%,<br/>';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('emp_proposal_subject');
				$email_content 	= fw_get_db_settings_option('emp_proposal_content');
				
				if( !empty( $project_type ) && $project_type === 'hourly' ){
					$email_content 	= fw_get_db_settings_option('emp_proposal_content_hourly');
				}
			}

			//Set Default Subject
			if( empty( $subject ) ){
				$subject = $subject_default;
			}

			//set defalt contents
			if (empty($email_content)) {
				$email_content = $email_default;
			}
			
			$email_to = $employer_email;
			
			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%employer_name%", $employer_name, $email_content); 
			$email_content = str_replace("%freelancer_link%", $freelancer_link, $email_content); 
			$email_content = str_replace("%freelancer_name%", $freelancer_name, $email_content); 
			$email_content = str_replace("%project_link%", $project_link, $email_content); 
            $email_content = str_replace("%project_title%", $project_title, $email_content);
            $email_content = str_replace("%proposal_amount%", $proposal_amount, $email_content);
			
			if( !empty( $project_type ) && $project_type === 'fixed' ){
            	 $email_content = str_replace("%proposal_duration%", $proposal_duration, $email_content);
			}

            $email_content = str_replace("%message%", $proposal_message, $email_content);
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
		public function send_freelancer_proposal_submit($params = '') {

			extract($params);
			$subject_default = esc_html__('Proposal Submit', 'workreap_core');
			$email_default   = 'Hello %freelancer_name%<br/><br/>
								You have submitted the proposal against this job <a href="%project_link%">%project_title%</a>.<br/><br/>

								Project Proposal Amount : %proposal_amount%<br/>
								Project Duration : %proposal_duration%<br/>
								Message: %message%<br/><br/>

								%signature%,';
			
			
			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('frl_proposal_subject');
				$email_content  = fw_get_db_settings_option('frl_proposal_content');
			}

			//Set Default Subject
			if( empty( $subject ) ){
				$subject = $subject_default;
			}

			//set defalt contents
			if (empty($email_content)) {
				$email_content = $email_default;
            }
            
			//mailto
			$email_to = $freelancer_email;
			
			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%freelancer_link%", $freelancer_link, $email_content); 
			$email_content = str_replace("%freelancer_name%", $freelancer_name, $email_content); 
			$email_content = str_replace("%project_link%", $project_link, $email_content); 
            $email_content = str_replace("%project_title%", $project_title, $email_content);
            $email_content = str_replace("%proposal_amount%", $proposal_amount, $email_content);
			
			if( !empty( $project_type ) && $project_type === 'fixed' ){
            	$email_content = str_replace("%proposal_duration%", $proposal_duration, $email_content);
			}
			
            $email_content = str_replace("%message%", $proposal_message, $email_content);
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

	new WorkreapProposalSubmit();
}