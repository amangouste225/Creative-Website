<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('WorkreapHelp')) {

    class WorkreapHelp extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send freelancer service message
		 *
		 * @since 1.0.0
		 */
		public function send_admin_help($params = '') {
			extract($params);
			$subject_default = esc_html__('Help & Support', 'workreap_core');
			$email_default = 'Hello Admin<br/>
								You have received a new query from the %user_from% <br/>
								Subject : %query_type%<br/>
								Message : %message%<br/>
								<br/>
								%signature%,<br/>';

			if (function_exists('fw_get_db_settings_option')) {
				$help_email 	= fw_get_db_settings_option('help_email');
				$subject 		= fw_get_db_settings_option('help_subject');
				$email_content  = fw_get_db_settings_option('help_content');
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
			if( empty( $help_email ) ){
				$help_email = get_option('admin_email', 'somename@example.com');
			}
			
			$user_from	= '<a href="'.esc_url( $user_link ).'">'.$user_name.'</a>';
			
			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%query_type%", $query_type, $email_content); 
			$email_content = str_replace("%message%", $message, $email_content); 
			$email_content = str_replace("%user_from%", $user_from, $email_content); 
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';

            $body .= $this->prepare_email_footers();
			wp_mail($help_email, $subject, $body);
        }	
        
	}

	new WorkreapHelp();
}