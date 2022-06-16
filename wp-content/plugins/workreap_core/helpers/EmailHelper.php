<?php
/**
 * Email Helper For Theme
 * @since    1.0.0
 */
if (!class_exists('Workreap_Email_helper')) {

    class Workreap_Email_helper {

        public function __construct() {
            add_filter('wp_mail_content_type', array(&$this, 'workreap_set_content_type'));
        }

        /**
         * Email Headers From
         * @since    1.0.0
         */
        public function workreap_wp_mail_from($email) {
            if (function_exists('fw_get_db_settings_option')) {
                $email_from_id = fw_get_db_settings_option('email_from_id');
            }

            if (!empty($email_from_id)) {
                return $email_from_id;
            }
        }

        /**
         * Email Headers From name
         * @since    1.0.0
         */
        public function workreap_wp_mail_from_name($name) {
            $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

            if (function_exists('fw_get_db_settings_option')) {
                $email_from_name = fw_get_db_settings_option('email_from_name');
            }

            if (!empty($email_from_name)) {
                return $email_from_name;
            }
        }

        /**
         * Email Content type
         *
         *
         * @since    1.0.0
         */
        public function workreap_set_content_type() {
            return "text/html";
        }

        /**
         * Get Email Header
         * Return email header html
         * @since    1.0.0
         */
        public function prepare_email_headers() {
            global $current_user;
            ob_start();
            $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
            $email_banner = array();
            if (function_exists('fw_get_db_settings_option')) {
                $email_banner = fw_get_db_settings_option('email_banner');
				$email_content_width = fw_get_db_settings_option('email_content_width');
            }

            if (!empty($email_banner['url'])) {
                $banner = $email_banner['url'];
				$banner = workreap_add_http($banner);
            }

            $email_content_width	= !empty($email_content_width) ?  $email_content_width : 600;
            ?>
            <div style="min-width:100%;background-color:#f6f7f9;margin:0;width:100%;color:#283951;font-family:'Helvetica','Arial',sans-serif;padding: 60px 0;">
				<div style="background: #FFF;max-width: <?php echo esc_attr($email_content_width);?>px; width: 100%; margin: 0 auto; overflow: hidden; color: #919191; font:400 16px/26px 'Open Sans', Arial, Helvetica, sans-serif;">
					<?php $this->process_get_logo(); ?>
					<?php if (!empty($banner)) { ?>
						<div id="wt-banner" class="wt-banner" style="width: 100%; float: left; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;"><img style="width: 100%; height: auto; display: block;" src="<?php echo esc_url($banner); ?>" alt="<?php echo esc_attr($blogname); ?>"></div>
					<?php } ?>
					<div style="width: 100%; float: left; padding: 30px 30px 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">
                    <?php
                    return ob_get_clean();
                }

		/**
		 * Get Email Footer
		 *
		 * Return email footer html
		 *
		 * @since    1.0.0
		 */
		public function prepare_email_footers($params = '') {
			global $current_user;
			ob_start();
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			if (function_exists('fw_get_db_settings_option')) {
                $footer_bg_color 	= fw_get_db_settings_option('footer_bg_color');
				$footer_text_color  = fw_get_db_settings_option('footer_text_color');
            }
			
			$footer_bg_color	=  !empty( $footer_bg_color ) ? $footer_bg_color : '#ff5851';
			$footer_text_color	=  !empty( $footer_text_color ) ? $footer_text_color : '#fff';
			?>
					</div>
					<div style="width:100%;float:left;background: <?php echo esc_attr( $footer_bg_color );?>;padding: 30px 15px;text-align:center;box-sizing:border-box;border-radius: 0  0 5px 5px;">
						<p style="font-size: 13px; line-height: 13px; color: <?php echo esc_attr( $footer_text_color );?>; margin: 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;"><?php esc_html_e('Copyright', 'workreap_core'); ?>&nbsp;&copy;&nbsp;<?php echo date('Y'); ?><?php esc_html_e(' | All Rights Reserved', 'workreap_core'); ?> <a href="<?php echo esc_url(home_url('/')); ?>" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; color: <?php echo esc_attr( $footer_text_color );?>; margin: 0; padding: 0;"><?php echo esc_attr($blogname); ?></a></p>
					</div>
				</div>
			</div>
			<?php
			return ob_get_clean();
		}

		/**
		 * @Process Sender Information
		 * @since 1.0.0
		 * 
		 * @return {data}
		 */
		public function process_sender_information($params = '') {
			global $current_user;
			ob_start();
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			$tagline = wp_specialchars_decode(get_option('blogdescription'), ENT_QUOTES);

			$sender_avatar = array();
			$email_sender_name = '';
			$email_sender_tagline = '';
			$sender_url = '';
			$sender_url = 'yes';
			
			if (function_exists('fw_get_db_settings_option')) {
				$sender_avatar = fw_get_db_settings_option('email_sender_avatar');
				$email_sender_name = fw_get_db_settings_option('email_sender_name');
				$email_sender_tagline = fw_get_db_settings_option('email_sender_tagline');
				$sender_url = fw_get_db_settings_option('email_sender_url');
				$regards = fw_get_db_settings_option('regards');
			}

			if (!empty($email_sender_name)) {
				$sender_name = $email_sender_name;
			} else {
				$sender_name = $blogname;
			}

			if (!empty($email_sender_tagline)) {
				$sender_tagline = $email_sender_tagline;
			} else {
				$sender_tagline = $tagline;
			}

			if (!empty($sender_avatar['url'])) {
				$avatar = $sender_avatar['url'];
				$avatar = workreap_add_http($avatar);
			}
			?>
			<div style="width: 100%; float: left; padding: 15px 0 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">
				<?php if (!empty($avatar)) { ?>
					<div style="float: left; max-width: 100px; border-radius: 5px; margin-right: 20px; overflow: hidden; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">
						<img style="display: block; max-width: 100px;" src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($blogname); ?>">
					</div>
				<?php } ?>
				<?php if (!empty($sender_name) || !empty($sender_tagline) || !empty($sender_url)) { ?>
					<div style="overflow: hidden; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">
						<?php if (!empty($regards) && $regards === 'yes') { ?>
							<p style="margin: 0 0 7px; font-size: 14px; line-height: 14px; color: #919191; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;"><?php esc_html_e('Regards', 'workreap_core'); ?></p>
						<?php } ?>
						<?php if (!empty($sender_name)) { ?>
							<h2 style="font-size: 18px; line-height: 18px; margin: 0 0 5px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; color: #333; font-weight: normal;font-family: 'Work Sans', Arial, Helvetica, sans-serif;"><?php echo esc_attr($sender_name); ?></h2>
						<?php } ?>
						<?php if (!empty($sender_tagline)) { ?>
							<p style="margin: 0 0 7px; font-size: 14px; line-height: 14px; color: #919191; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;"><?php echo esc_attr($sender_tagline); ?></p>
						<?php } ?>
						<?php if (!empty($sender_url)) { ?>
							<p style="margin: 0; font-size: 14px; line-height: 14px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;"><a style=" color: #55acee; text-decoration: none;" href="<?php echo esc_url($sender_url); ?>"><?php echo esc_url($sender_url); ?></a></p>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
			<?php
			return ob_get_clean();
		}

		/**
		 * @Registration
		 *
		 * @since 1.0.0
		 */
		public function process_get_logo($params = '') {
			//Get Logo
			if (function_exists('fw_get_db_settings_option')) {
				$email_logo = fw_get_db_settings_option('email_logo');
				$main_logo = fw_get_db_settings_option('main_logo');
				$logo_email_x = fw_get_db_settings_option('logo_email_x');
			}

			if (!empty($email_logo['url'])) {
				$logo = $email_logo['url'];
			}
			
			$logo_email_x	=  !empty( $logo_email_x ) ? $logo_email_x : '100';

			if( !empty( $logo ) ){
				$logo = workreap_add_http($logo);
				echo '<div style="width: 100%; float: left; padding: 30px 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">
						<strong style="float: left; padding: 0 0 0 30px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;"><a style="float: left; color: #55acee; text-decoration: none;" href="'.esc_url(home_url('/')).'"><img style="max-width:'.$logo_email_x.'px;" src="' . esc_url($logo) . '" /></a></strong></div>';
			}
		}
	}

	new Workreap_Email_helper();
}