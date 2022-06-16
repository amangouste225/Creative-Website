<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('WorkreapCancelService')) {

    class WorkreapCancelService extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send report user email
		 *
		 * @since 1.0.0
		 */
		public function send_service_cancel_email($params = '') {

			extract($params);
			$subject_default = esc_html__('Service Cancelled', 'workreap_core');
			$email_default   = 'Hello %freelancer_name%<br/>
							Unfortunately <a href=" %employer_link%">%employer_name%</a> has cancelled the service <a href="%service_link%">%service_title%</a> due to following below reasons.<br/>
							service "Cancel Reason" is given below.<br/><br/>
							Message: %message%<br/>

							%signature%';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('frl_cancel_service_subject');
				$email_content  = fw_get_db_settings_option('frl_cancel_service_content');
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
            $email_content = str_replace("%service_link%", $service_link, $email_content); 
            $email_content = str_replace("%service_title%", $service_title, $email_content); 
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
			$email_to = $freelancer_email;
			wp_mail($email_to, $subject, $body);
		}	
	}

	new WorkreapCancelService();
}