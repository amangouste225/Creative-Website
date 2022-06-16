<?php
/**
 * @facebook connect
 * @return 
 */
require WorkreapGlobalSettings::get_plugin_path() . 'libraries/Facebook/autoload.php';

use Facebook\Facebook;

class Facebook_Connect {

    private $app_id = '';
    private $app_secret = '';
    private $callback_url = '';

    public function __construct() {
        add_action('do_facebook_connect', array(&$this, 'do_facebook_connect'));
		add_action('wp_ajax_workreap_fb_connect', array(&$this, 'get_login_url'));
		add_action('wp_ajax_nopriv_workreap_fb_connect', array(&$this, 'get_login_url'));
    }

    private function initApi() {
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$this->app_id 		= fw_get_db_settings_option( 'app_id', $default_value = null );
			$this->app_secret 	= fw_get_db_settings_option( 'app_secret', $default_value = null );
			$this->callback_url = workreap_new_social_login_url( 'facebooklogin' );
		}
		
        $facebook = new Facebook([
            'app_id' => $this->app_id,
            'app_secret' => $this->app_secret,
            'default_graph_version' => 'v2.5',
            'persistent_data_handler' => 'session'
        ]);

        return $facebook;
    }
	
	/**
     * get facebook connect
     *
     * @link https://codex.wordpress.org/Function_Reference/get_users
     * @return bool|void
     */
    public function do_facebook_connect() {
        if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$app_id = fw_get_db_settings_option( 'app_id', $default_value = null );
			$app_secret = fw_get_db_settings_option( 'app_secret', $default_value = null );
		}
		
		session_start( array('register') );

		// Set the Redirect URL:
		$redirectURL = workreap_new_social_login_url( 'facebooklogin' );

		$fb = $this->initApi();
		 # Create the login helper object
		$helper = $fb->getRedirectLoginHelper();

		# Get the access token and catch the exceptions if any
		try {
			$accessToken = $helper->getAccessToken();
		} catch (Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			//echo 'Graph returned an error: ' . $e->getMessage();
			//exit;
			workreap_new_social_redirect('facebook');
		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			//echo 'Facebook SDK returned an error: ' . $e->getMessage();
			//exit;
			workreap_new_social_redirect('facebook');
		}

		if (isset($accessToken)) {
			// Logged in!
			// Now you can redirect to another page and use the
			// access token from $_SESSION['facebook_access_token'] 
			// But we shall we the same page
			// Sets the default fallback access token so 
			// we don't have to pass it to each request
			$fb->setDefaultAccessToken($accessToken);

			try {
				$response 	= $fb->get('/me?fields=email,name');
				$user 		= $response->getGraphUser();
				
				set_site_transient( workreap_get_uniqid() . '_sp_facebook_connect', $accessToken, 3600 );
				$email = filter_var( $user[ 'email' ], FILTER_SANITIZE_EMAIL );

				if ( !is_user_logged_in() ) {
					$ID = email_exists( $email );
					if ( $ID == false ) { // Real register
						do_action('workreap_create_social_users','facebook',$user);
					} else if ( $ID ) { // Login
						do_action('workreap_do_social_login',$ID);
					}
				}

			} catch (Facebook\Exceptions\FacebookResponseException $e) {
				// When Graph returns an error
				//echo 'Graph returned an error: ' . $e->getMessage();
				//exit;
				workreap_new_social_redirect('facebook');
			} catch (Facebook\Exceptions\FacebookSDKException $e) {
				// When validation fails or other local issues
				//echo 'Facebook SDK returned an error: ' . $e->getMessage();
				//exit;
				workreap_new_social_redirect('facebook');
			}
		}
    }
	
	/**
     * get facebook data
     *
     * @link https://codex.wordpress.org/Function_Reference/get_users
     * @return bool|void
     */
    public static function display_facebook_userdata($userNode) {
        echo "Welcome to profile !<br><br>";
        echo 'Name: ' . $userNode->getName() . '<br>';
        echo 'User ID: ' . $userNode->getId() . '<br>';
        echo 'Email: ' . $userNode->getProperty('email') . '<br><br>';
        $image = 'https://graph.facebook.com/' . $userNode->getId() . '/picture?width=400';
        echo "Picture<br>";
        echo "<img src='$image' /><br><br>";
    }
	
	/**
     * get login URL
     *
     * @link https://codex.wordpress.org/Function_Reference/get_users
     * @return bool|void
     */
	public function get_login_url() {
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		session_start( array('register') );
		// Set the Redirect URL:
		$redirectURL = workreap_new_social_login_url( 'facebooklogin' );
		$fb = $this->initApi();
		
		 # Create the login helper object
        $helper = $fb->getRedirectLoginHelper();

		$permissions = ['email'];
		$authUrl = $helper->getLoginUrl($redirectURL, $permissions);
		
		$json['type'] 	 		= 'success';
		$json['authUrl']    	= $authUrl;
		$json['message'] 		= esc_html__('Please wait while you are redirecting to facebook for authorizations.', 'workreap_core');
		echo json_encode($json);
		die();
	}
}

new Facebook_Connect();
