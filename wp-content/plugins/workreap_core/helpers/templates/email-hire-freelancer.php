<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('WorkreapHireFreelancer')) {

    class WorkreapHireFreelancer extends Workreap_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send freelancer hiring email
		 *
		 * @since 1.0.0
		 */
		public function send_hire_freelancer_email($params = '') {
			extract($params);
			$subject_default = esc_html__('Congratulations! You have been hired.', 'workreap_core');
			$email_default = 'Hello %freelancer_name%<br/>
								Congratulations!<br/>
								You have hired for the following job <a href="%project_link%">%project_title%</a> by the employer <a href="%employer_link%">%employer_name%</a><br/>
								%signature%';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('frl_hire_subject');
				$email_content 	= fw_get_db_settings_option('frl_hire_content');
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
            $email_content = str_replace("%project_link%", $project_link, $email_content); 
            $email_content = str_replace("%project_title%", $project_title, $email_content); 
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
		 * @Send employer hiring email
		 *
		 * @since 1.0.0
		 */
		public function send_hiring_employer_email($params = '') {
			extract($params);
			$subject_default = esc_html__('Hiring completed', 'workreap_core');
			$email_default = 'Hello %employer_name%<br/>
			Congratulations!<br/>
			You have hired the freelancer "%freelancer_name%" for the following job <a href="%project_link%">%project_title%</a><br/>
			%signature%';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('emp_hire_subject');
				$email_content 	= fw_get_db_settings_option('emp_hire_content');
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
            $email_content = str_replace("%project_link%", $project_link, $email_content); 
            $email_content = str_replace("%project_title%", $project_title, $email_content); 
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';

			$body .= $this->prepare_email_footers();
			wp_mail($employer_email_to, $subject, $body);
		}
		
		/**
		 * @Send rejected freelancers email
		 *
		 * @since 1.0.0
		 */
		public function send_rejected_freelancers_email($params = '') {
			extract($params);
			
			if (function_exists('fw_get_db_settings_option')) {
				$proposal_email = fw_get_db_settings_option('proposal_email');
			}
			
			if(!empty($proposal_email) && $proposal_email === 'disable'){
				return;
			}

			$meta_query_args	= array();
			$query_args = array('posts_per_page' => -1,
				'post_type' 		=> 'proposals',
				'suppress_filters' 	=> false,
				'author__not_in'    => array( $freelancer_id )
			);

			$meta_query_args[] = array(
				'key' 			=> '_project_id',
				'value' 		=> $project_id,
				'compare' 		=> '='
			);

			$query_relation = array('relation' => 'AND',);
			$query_args['meta_query'] = array_merge($query_relation, $meta_query_args);    

			$pquery = get_posts($query_args);

			if( !empty($pquery) ){
				$subject_default = esc_html__('Your proposal has been rejected', 'workreap_core');
				$email_default = 'Hi %freelancer_name%,<br/><br/>
									We are sorry, your proposal has been rejected<br/>
									Employer %employer_name% has hire other freelancer for the project %project_title% <br/>

									Try to bid on other project to get hired

									%signature%<br/>';

				if (function_exists('fw_get_db_settings_option')) {
					$subject 		= fw_get_db_settings_option('fr_proposal_subject');
					$email_content 	= fw_get_db_settings_option('fr_proposal_content');
				}


				//Set Default Subject
				if( empty( $subject ) ){
					$subject = $subject_default;
				}

				//set defalt contents
				if (empty($email_content)) {
					$email_content = $email_default;
				}
				
				
				foreach( $pquery as $key => $user ){
					$email_to 			= !empty( $user->post_author ) ? get_userdata( $user->post_author )->user_email : '';
					$freelaner_id 		= !empty( $user->post_author ) ? get_userdata( $user->post_author )->ID : '';
					
					$freelancer_post_id		= get_post_meta($user->ID,'_send_by',true);
					$freelancer_link_rej 	= !empty($freelancer_post_id) ? esc_url( get_the_permalink( $freelancer_post_id )) : '';
					$freelancer_title_rej 	= !empty($freelancer_post_id) ? esc_html( get_the_title($freelancer_post_id)) : '';
					$email_new_content 		= '';
													
					if(!empty($email_to)){
						$sender_info = $this->process_sender_information();

						$email_new_content = str_replace("%freelancer_link%", $freelancer_link_rej, $email_content); 
						$email_new_content = str_replace("%freelancer_name%", $freelancer_title_rej, $email_new_content); 

						$email_new_content = str_replace("%employer_name%", $employer_name, $email_new_content); 
						$email_new_content = str_replace("%employer_link%", $employer_link, $email_new_content); 

						$email_new_content = str_replace("%project_link%", $project_link, $email_new_content); 
						$email_new_content = str_replace("%project_title%", $project_title, $email_new_content); 

						$email_new_content = str_replace("%signature%", $sender_info, $email_new_content);

						$body = '';
						$body .= $this->prepare_email_headers();

						$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
						$body .= '<div style="width: 100%; float: left;">';
						$body .= '<p>' . $email_new_content . '</p>';
						$body .= '</div>';
						$body .= '</div>';

						$body .= $this->prepare_email_footers();
						wp_mail($email_to, $subject, $body);
						
						
						//Push notification
						$push	= array();
						$push['freelancer_id']		= $freelaner_id;
						$push['employer_id']		= $employer_user_id;
						$push['project_id']			= $project_id;

						$push['%freelancer_link%']	= $freelancer_link_rej;
						$push['%freelancer_name%']	= $freelancer_title_rej;
						$push['%employer_name%']	= $employer_name;
						$push['%employer_link%']	= $employer_link;
						$push['%project_title%']	= $project_title;
						$push['%project_link%']		= $project_link;
						
						$push['type']				= 'freelancer_rejected';

						do_action('workreap_user_push_notify',array($freelaner_id),'','pusher_fr_proposal_content',$push);
					}
				}
			}
		}
		
		/**
		 * @Send service email to freelancer
		 *
		 * @since 1.0.0
		 */
		public function send_hire_freelancer_email_service($params = '') {
			extract($params);
			$subject_default = esc_html__('Congratulations! You have been hired.', 'workreap_core');
			$email_default = 'Hello %freelancer_name%<br/>
								Congratulations!<br/>
								You have received new order for the following service <a href="%service_link%">%service_title%</a> by the employer <a href="%employer_link%">%employer_name%</a><br/>
								%signature%';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('service_buy_subject');
				$email_content 	= fw_get_db_settings_option('service_buy_content');
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
			$email_content = str_replace("%service_price%", $service_price, $email_content); 
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
		 * @Send service email to employer
		 *
		 * @since 1.0.0
		 */
		public function send_hire_employer_email_service($params = '') {
			extract($params);
			$subject_default = esc_html__('Service order confirmation', 'workreap_core');
			$email_default = 'Hello %employer_name%<br/>
											
							Thank you for the order my service <a href="%service_link%">%service_title%</a><br/>
							%signature%';

			if (function_exists('fw_get_db_settings_option')) {
				$subject 		= fw_get_db_settings_option('service_buy_subject_employer');
				$email_content 	= fw_get_db_settings_option('service_buy_content_employer');
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
			$email_content = str_replace("%service_price%", $service_price, $email_content); 
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';

			$body .= $this->prepare_email_footers();
			wp_mail($employer_email, $subject, $body);
		}
	}

	new WorkreapHireFreelancer();
}