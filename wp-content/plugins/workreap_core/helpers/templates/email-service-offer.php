<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('WorkreapServiceOffer')) {

    class WorkreapServiceOffer extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }
		
		/**
		 * @Send service offer
		 *
		 * @since 1.0.0
		 */
		public function send_offer($params = '') {
			extract($params);
			$subject_default = esc_html__('New offer received', 'workreap_core');
			$email_default   = 'Hello %employer_name%

			You have received a new offer for the "%service_name%" from the freelancer "%freelancer_name%"
			
			You can accept or decline this
			
			Thank you';
			
			
			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('send_offer_subject');
				$email_content  = fw_get_db_settings_option('send_offer_content');
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
			
			//Email Sender information
			$email_content = str_replace("%freelancer_link%", $freelancer_link, $email_content); 
			$email_content = str_replace("%freelancer_name%", $freelancer_name, $email_content); 
			$email_content = str_replace("%service_link%", $service_link, $email_content); 
			$email_content = str_replace("%service_name%", $service_name, $email_content); 
			$email_content = str_replace("%employer_link%", $employer_link, $email_content); 
			$email_content = str_replace("%employer_name%", $employer_name, $email_content); 
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
		 * @Update offer offer
		 *
		 * @since 1.0.0
		 */
		public function update_offer($params = '') {
			extract($params);
			$subject_default = esc_html__('Freelancer updated the offer', 'workreap_core');
			$email_default   = 'Hello %employer_name%

			Freelancer has updated the offer for the "%service_name%" from the freelancer "%freelancer_name%"
			
			You can accept or decline this
			
			Thank you';
			
			
			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('update_offer_subject');
				$email_content  = fw_get_db_settings_option('update_offer_content');
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
			
			//Email Sender information
			$email_content = str_replace("%freelancer_link%", $freelancer_link, $email_content); 
			$email_content = str_replace("%freelancer_name%", $freelancer_name, $email_content); 
			$email_content = str_replace("%service_link%", $service_link, $email_content); 
			$email_content = str_replace("%service_name%", $service_name, $email_content); 
			$email_content = str_replace("%employer_link%", $employer_link, $email_content); 
			$email_content = str_replace("%employer_name%", $employer_name, $email_content); 
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
		 * @Update offer offer
		 *
		 * @since 1.0.0
		 */
		public function decline_offer($params = '') {
			extract($params);
			$subject_default = esc_html__('Your offer has been rejected', 'workreap_core');
			$email_default   = 'Hello %freelancer_name%

			Your offer has been declined for the "%service_name% by the "%employer_name%"
			
			You can review the comments and send it again
			
			Thank you';
			
			
			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('decline_offer_subject');
				$email_content  = fw_get_db_settings_option('decline_offer_content');
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
			
			//Email Sender information
			$email_content = str_replace("%freelancer_link%", $freelancer_link, $email_content); 
			$email_content = str_replace("%freelancer_name%", $freelancer_name, $email_content); 
			$email_content = str_replace("%service_link%", $service_link, $email_content); 
			$email_content = str_replace("%service_name%", $service_name, $email_content); 
			$email_content = str_replace("%employer_link%", $employer_link, $email_content); 
			$email_content = str_replace("%employer_name%", $employer_name, $email_content); 
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

	new WorkreapServiceOffer();
}