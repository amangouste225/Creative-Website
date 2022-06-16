<?php
/**
 * Email Helper To Send Payouts Notifications
 * To Freelancer
 * @since    1.0.0
 */
if (!class_exists('WorkreapSendPayoutsNotification')) {

    class WorkreapSendPayoutsNotification extends Workreap_Email_helper{

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

			$subject_default = esc_html__('Payouts Notification', 'workreap_core');
			$contact_default = 'Hi %freelancer_name%,<br/>
								Congratulations!<br/>
								Your payouts has been processed. Your total payouts was <strong>%total_amount%</strong><br/>
								%signature%<br/><br/>';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('fr_payouts_subject');
				$email_content 	= fw_get_db_settings_option('fr_payouts_content');
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
		
		public function send_withdraw_request_to_admin($params = '') {
			extract($params);

			$subject_default = esc_html__('New withdrawal request has received', 'workreap_core');
			$contact_default = 'Hello,<br/>
								You have received a new withdraw request from the %user_name% <br/>
								You can click <a href="%detail%">this link</a> to view the withdrawal details
								<br/>
								%signature%';

			if (function_exists('fw_get_db_settings_option')) {
				$email 			= fw_get_db_settings_option('withdraw_email');
				$subject 		= fw_get_db_settings_option('withdraw_subject');
				$email_content 	= fw_get_db_settings_option('withdraw_content');
			}
			
			//Set Default Subject
			if( empty( $subject ) ){
				$subject = $subject_default;
			}

			//set defalt contents
			if (empty($email_content)) {
				$email_content = $contact_default;
			}                       
			
			//set defalt admin email
			if( empty( $email ) ){
				$email = get_option('admin_email', 'somename@example.com');
			}
			
			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%user_name%", $user_name, $email_content); 
			$email_content = str_replace("%user_link%", $user_link, $email_content); 
			$email_content = str_replace("%amount%", $amount, $email_content); 
			$email_content = str_replace("%detail%", $detail, $email_content); 
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';
			$body .= $this->prepare_email_footers();
																	          
			wp_mail($email, $subject, $body);
		}
		
	}

	new WorkreapSendPayoutsNotification();
}