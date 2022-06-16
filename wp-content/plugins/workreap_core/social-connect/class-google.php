<?php
/**
 * @Google connect
 * @return 
 */
class Google_Connect {

    private $clientId 		= '';
    private $clientSecret 	= '';
    private $app_name 		= '';
	private $redirectURL 	= '';

    public function __construct() {
        add_action('do_google_connect', array(&$this, 'do_google_connect'));
		add_action('wp_ajax_workreap_google_connect', array(&$this, 'get_login_url'));
		add_action('wp_ajax_nopriv_workreap_google_connect', array(&$this, 'get_login_url'));
    }
	
	/**
     * init api credentials
     *
     * @link https://codex.wordpress.org/Function_Reference/get_users
     * @return bool|void
     */
    private function initApi() {
		if (function_exists('fw_get_db_settings_option')) {
			$this->clientId = fw_get_db_settings_option('client_id', $default_value = null);
			$this->clientSecret = fw_get_db_settings_option('client_secret', $default_value = null);
			$this->app_name = fw_get_db_settings_option('app_name', $default_value = esc_html__('Google Connect', 'workreap_core'));
		}

		$this->redirectURL 	= workreap_new_social_login_url('googlelogin');
		
		$client = new Google_Client();
		$client->setApplicationName($this->app_name);
		$client->addScope('profile');
		// Visit https://code.google.com/apis/console?api=plus to generate your
		$client->setClientId($this->clientId);
		$client->setClientSecret($this->clientSecret);
		$client->setRedirectUri($this->redirectURL);
		
        return $client;
    }
	
	/**
     * get google connect
     *
     * @link https://codex.wordpress.org/Function_Reference/get_users
     * @return bool|void
     */
    public function do_google_connect() {
		$redirectURL = workreap_new_social_login_url( 'googlelogin' );
		session_start( array('register') );
		
		$client = $this->initApi();
		$oauth2 = new Google_Service_Oauth2( $client );

		if ( isset( $_GET[ 'code' ] ) ) {
			//set_site_transient( workreap_get_uniqid().'_google_r', $_GET['redirect'], 3600);
			$client->authenticate( $_GET[ 'code' ] );
			$access_token = $client->getAccessToken();
			set_site_transient( workreap_get_uniqid() . '_sp_google_connect', $access_token, 3600 );
			header( 'Location: ' . filter_var( workreap_new_social_login_url( 'googlelogin' ), FILTER_SANITIZE_URL ) );
			exit;
		}

		$access_token = get_site_transient( workreap_get_uniqid() . '_sp_google_connect' );

		if ( $access_token !== false ) {
			$client->setAccessToken( $access_token );
		}

		if ( $client->getAccessToken() ) {
			$user = $oauth2->userinfo->get();
			set_site_transient( workreap_get_uniqid() . '_sp_google_connect', $client->getAccessToken(), 3600 );
			$email = filter_var( $user[ 'email' ], FILTER_SANITIZE_EMAIL );

			if ( !is_user_logged_in() ) {
				$ID = email_exists( $email );
				if ( $ID == false ) { // Real register
					do_action('workreap_create_social_users','google',$user);
				} else if ( $ID ) { // Login
					do_action('workreap_do_social_login',$ID);
				}
			}
		}
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
		$client  = $this->initApi();
		$authUrl = $client->createAuthUrl();

		$json['type'] 	 		= 'success';
		$json['authUrl']    	= $authUrl;
		$json['message'] 		= esc_html__('Please wait while you are redirecting to google+ for authorizations.', 'workreap_core');
		echo json_encode($json);
		die();
	}
}
new Google_Connect();
