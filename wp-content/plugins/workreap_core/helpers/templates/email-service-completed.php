<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('WorkreapServiceCompleted')) {

    class WorkreapServiceCompleted extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send admin service completed email
		 *
		 * @since 1.0.0
		 */
		public function send_service_completed_email_admin($params = '') {
			extract($params);
			$subject_default = esc_html__('Service Completed', 'workreap_core');
			$email_default = 'Hello Admin<br/>
							The <a href=" %freelancer_link%">%freelancer_name%</a> is completed the following service (<a href="%service_link%">%service_title%</a>).<br/>
							<br/>
							%signature%,<br/>';

			if (function_exists('fw_get_db_settings_option')) {
                $email_to 		= fw_get_db_settings_option('admin_service_complete_email');
				$subject 		= fw_get_db_settings_option('admin_service_complete_subject');
				$email_content 	= fw_get_db_settings_option('admin_service_complete_content');
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
			
			$email_content = str_replace("%employer_name%", $employer_name, $email_content);
			$email_content = str_replace("%employer_link%", $employer_link, $email_content); 
			$email_content = str_replace("%freelancer_name%", $freelancer_name, $email_content); 
			$email_content = str_replace("%freelancer_link%", $freelancer_link, $email_content); 
			$email_content = str_replace("%service_title%", $service_title, $email_content); 
			$email_content = str_replace("%service_link%", $service_link, $email_content); 
			$email_content = str_replace("%message%", $message, $email_content); 
			$email_content = str_replace("%ratings%", $ratings, $email_content); 
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
		public function send_service_completed_email_freelancer($params = '') {
			extract($params);
			$subject_default = esc_html__('Service Completed', 'workreap_core');
			$email_default = 'Hello %freelancer_name%
								Congratulation! You have complete your service.

								<a href=" %employer_link%">%employer_name%</a> has confirmed that the following service (<a href="%service_link%">%service_title%</a>) has been completed completed.
								You have received the following ratings from the employer, please login to view your feedback ratings.
								<ul style="margin: 0; width: 100%; float: left; list-style: none; font-size: 14px; line-height: 20px; padding: 0 0 15px; font-family: \'Work Sans\', Arial, Helvetica, sans-serif;">
									<li style="width: 100%; float: left; line-height: inherit; list-style-type: none; background: #f7f7f7;"><strong style="width: 50%; float: left; padding: 10px; color: #333; font-weight: 400; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">Message:</strong> <span style="width: 50%; float: left; padding: 10px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">%message%</span></li>
									<li style="width: 100%; float: left; line-height: inherit; list-style-type: none;"><strong style="width: 50%; float: left; padding: 10px; color: #333; font-weight: 400; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">Rating:</strong> <span style="width: 50%; float: left; padding: 10px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">%ratings%</span></li>
								</ul>
								%signature%';

			if (function_exists('fw_get_db_settings_option')) {
				$subject = fw_get_db_settings_option('frl_service_complete_subject');
				$email_content = fw_get_db_settings_option('frl_service_complete_content');
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
			
			$email_content = str_replace("%employer_name%", $employer_name, $email_content);
			$email_content = str_replace("%employer_link%", $employer_link, $email_content); 
			$email_content = str_replace("%freelancer_name%", $freelancer_name, $email_content); 
			$email_content = str_replace("%freelancer_link%", $freelancer_link, $email_content); 
			$email_content = str_replace("%service_title%", $service_title, $email_content); 
			$email_content = str_replace("%service_link%", $service_link, $email_content); 
			$email_content = str_replace("%message%", $message, $email_content); 
			$email_content = str_replace("%ratings%", $ratings, $email_content); 
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

	new WorkreapServiceCompleted();
}