<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('WorkreapOfflinePackages')) {

    class WorkreapOfflinePackages extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send report user email
		 *
		 * @since 1.0.0
		 */
		public function recived_offline_order($params = '') {
			extract($params);
			$subject_default = esc_html__('Offline order is received.', 'workreap_core');
			$email_default = 'Hi, %employer_name%,<br/><br/>
								We have received your order regarding the <a href="%order_link%">"%order_name%"</a>, Ppease send us your payment on the below details and let us know.
								%signature%,<br/>';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('offline_order_notification_subject');
				$email_content 	= fw_get_db_settings_option('offline_order_notification_content');
			}

			$subject 		= !empty($subject) ? $subject : $subject_default;
			$email_content 	= !empty($email_content) ? $email_content : $email_default;
			
			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%order_name%", $order_name, $email_content); 
			$email_content = str_replace("%employer_name%", $employer_name, $email_content); 
            $email_content = str_replace("%order_link%", $order_link, $email_content); 
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

	new WorkreapOfflinePackages();
}