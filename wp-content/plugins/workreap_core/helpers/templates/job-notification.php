<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('WorkreapJobNotification')) {

    class WorkreapJobNotification extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send report user email
		 *
		 * @since 1.0.0
		 */
		public function send_freelancers_job_notification($params = '') {
			extract($params);
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			
			$subject_default = esc_html__('New Jobs are Posted', 'workreap_core');
			$email_default = 'Hello %freelancer_name%,<br/><br/>
								There are some new jobs posted matching your skills, You can visit our site for more information.<br/>
								%jobs_listings%<br/>
								<a style="color: #fff; padding: 0 50px; margin: 0 0 15px; font-size: 20px; font-weight: 600; line-height: 60px; border-radius: 8px; background: #5dc560; vertical-align: top; display: inline-block; font-family: \'Work Sans\', Arial, Helvetica, sans-serif;  text-decoration: none;" href="%search_job_link%">View All Jobs</a><br>
								%signature%,<br/>';
			$email_to 			= $email;
			
			if (function_exists('fw_get_db_settings_option')) {
				$job_notification  	= fw_get_db_settings_option('job_notification');
			}
			
			$subject 			= !empty( $job_notification['enable']['job_notification_subject'] ) ? $job_notification['enable']['job_notification_subject'] : $subject_default;
			$email_content  	= !empty( $job_notification['enable']['job_notification_content'] ) ? $job_notification['enable']['job_notification_content'] : $email_default;
			
			
			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%freelancer_name%", $freelancer_name, $email_content); 
			$email_content = str_replace("%jobs_listings%", $jobs_listings, $email_content); 
			$email_content = str_replace("%search_job_link%", $search_job_link, $email_content); 
			$email_content = str_replace("%signature%", $sender_info, $email_content);
			$email_content = str_replace("%site%", $blogname, $email_content);

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

	new WorkreapJobNotification();
}