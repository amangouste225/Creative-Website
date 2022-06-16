<?php
/**
 * Email Helper To Send Earning Notifications
 * To Freelancer
 * @since    1.0.0
 */
if (!class_exists('WorkreapSendEarningNotification')) {

    class WorkreapSendEarningNotification extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send earning notification to freelancer
		 *
		 * @since 1.0.0
		 */
		public function send_notification_to_freelancer($params = '') {
			extract($params);

			$subject_default = esc_html__('Earning Notification', 'workreap_core');
			$contact_default = 'Hi %freelancer_name%,<br/>
								This is confirmation that your total earning has been calculated. <br/>
								Your payouts will be <strong>%total_amount%</strong><br/>
								You will be informed when your payouts will be processed.<br/>
								%signature%';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('fr_earning_subject');
				$email_content 	= fw_get_db_settings_option('fr_earning_content');
			}
			
			//Set Default Subject
			if( empty( $subject ) ){
				$subject = $subject_default;
			}

			//set defalt contents
			if (empty($email_content)) {
				$email_content = $contact_default;
			}                       

			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%freelancer_name%", $freelancer_name, $email_content); 
			$email_content = str_replace("%total_amount%", $total_amount, $email_content); 
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

	new WorkreapSendEarningNotification();
}