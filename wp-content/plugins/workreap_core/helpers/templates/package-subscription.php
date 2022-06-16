<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('WorkreapSubscribePackage')) {

    class WorkreapSubscribePackage extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send email to employer
		 *
		 * @since 1.0.0
		 */
		public function send_subscription_email_to_employer($params = '') {

			extract($params);
			$subject_default = esc_html__('Thank you for purchasing the package!', 'workreap_core');
			$email_default = 'Hello %employer_name%
							Thanks for purchasing the package. Your payment has been received and your invoice detail is given below:

							Invoice ID: %invoice%
							Package Name: %package_name%
							Payment Amount: %amount%
							Payment status: %status%
							Payment Method: %method%
							Purchase Date: %date%
							Expiry Date: %expiry%

							%signature%,';

			if (function_exists('fw_get_db_settings_option')) {
				$subject = fw_get_db_settings_option('emp_package_subject');
				$email_content = fw_get_db_settings_option('emp_package_content');
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
			
			$email_content = str_replace("%invoice%", $invoice, $email_content); 
			$email_content = str_replace("%package_name%", $package_name, $email_content); 
			$email_content = str_replace("%amount%", $amount, $email_content); 
            $email_content = str_replace("%status%", $status, $email_content); 
            $email_content = str_replace("%method%", $method, $email_content); 
            $email_content = str_replace("%date%", $date, $email_content); 
			$email_content = str_replace("%expiry%", $expiry, $email_content); 
			$email_content = str_replace("%name%", $name, $email_content); 
			$email_content = str_replace("%link%", $link, $email_content); 
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
		 * @Send email to freelancer
		 *
		 * @since 1.0.0
		 */
		public function send_subscription_email_to_freelancer($params = '') {

			extract($params);
			$subject_default = esc_html__('Thank you for purchasing the package!', 'workreap_core');
			$email_default = 'Hello %employer_name%
							Thanks for purchasing the package. Your payment has been received and your invoice detail is given below:

							Invoice ID: %invoice%
							Package Name: %package_name%
							Payment Amount: %amount%
							Payment status: %status%
							Payment Method: %method%
							Purchase Date: %date%
							Expiry Date: %expiry%

							%signature%,';

			if (function_exists('fw_get_db_settings_option')) {
				$subject = fw_get_db_settings_option('frl_package_subject');
				$email_content = fw_get_db_settings_option('frl_package_content');
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
			
			$email_content = str_replace("%invoice%", $invoice, $email_content); 
			$email_content = str_replace("%package_name%", $package_name, $email_content); 
			$email_content = str_replace("%amount%", $amount, $email_content); 
            $email_content = str_replace("%status%", $status, $email_content); 
            $email_content = str_replace("%method%", $method, $email_content); 
            $email_content = str_replace("%date%", $date, $email_content); 
			$email_content = str_replace("%expiry%", $expiry, $email_content); 
			$email_content = str_replace("%name%", $name, $email_content); 
			$email_content = str_replace("%link%", $link, $email_content); 
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

	new WorkreapCancelJob();
}