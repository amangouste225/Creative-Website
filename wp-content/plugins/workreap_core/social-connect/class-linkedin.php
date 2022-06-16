<?php
/**
 * @Google connect
 * @return 
 */
class Linkedin_Connect {

    private $clientId 		= '';
    private $clientSecret 	= '';
    private $app_name 		= '';
	private $redirectURL 	= '';
	
	const _AUTHORIZE_URL 	= 'https://www.linkedin.com/uas/oauth2/authorization';
    const _TOKEN_URL 		= 'https://www.linkedin.com/uas/oauth2/accessToken';
    const _BASE_URL 		= 'https://api.linkedin.com/v2';
	
    public function __construct() {
        add_action('init', array(&$this, 'do_linkedin_connect'));
		add_action('workreap_linkedin_login_button', array(&$this, 'workreap_linkedin_login_button'));
		add_action('wp_ajax_workreap_linkedin_connect', array(&$this, 'workreap_linkedin_connect'));
		add_action('wp_ajax_nopriv_workreap_linkedin_connect', array(&$this, 'workreap_linkedin_connect'));
    }
	
	/**
     * Get Login Link
     *
     * @link https://codex.wordpress.org/Function_Reference/get_users
     * @return bool|void
     */
	public function workreap_linkedin_login_button(){
		echo '<li class="wt-linkedin"><a class="sp-linkedin-connect" href="#" onclick="event_preventDefault(event);"><i class="fa fa-linkedin"></i><em>'.esc_html__('LinkedIn', 'workreap_core').'</em></a></li>';
	}
	
	/**
     * init api credentials
     *
     * @link https://codex.wordpress.org/Function_Reference/get_users
     * @return bool|void
     */
    private function initApi() {
		if (function_exists('fw_get_db_settings_option')) {
			$this->clientId = fw_get_db_settings_option('linkedin_client_id', $default_value = null);
			$this->clientSecret = fw_get_db_settings_option('linkedin_client_secret', $default_value = null);
			$this->app_name = esc_html__('Linkedin Connect', 'workreap_core');
		}
		
		session_start( array('register') );
		
		$this->redirectURL 	= workreap_new_social_login_url('linkedinlogin');

		require WorkreapGlobalSettings::get_plugin_path() . 'libraries/linkedin/connet_OAuth2Client.php';
        $client = new Social_OAuth2Client($this->clientId, $this->clientSecret);

        // Set Oauth URLs
        $client->redirect_uri 		= home_url('/') . '?action=linkedin_login';
        $client->authorize_url 		= self::_AUTHORIZE_URL;
        $client->token_url 			= self::_TOKEN_URL;
        $client->api_base_url 		= self::_BASE_URL;

        if ( get_current_user_id() ) {
            $client->access_token = get_user_meta(get_current_user_id(), 'linkedin_access_token', true);
        }

        return $client;
    }
	
	/**
     * get linkedin connect
     *
     * @link https://codex.wordpress.org/Function_Reference/get_users
     * @return bool|void
     */
    public function do_linkedin_connect() {
        if (!isset($_REQUEST['action']) || ($_REQUEST['action'] != "linkedin_login")) {
            return false;
        }
		
		session_start( array('register') );

        //If a code is empty
        if (!isset($_REQUEST['code']) && !isset($_REQUEST['error'])) {
            return false;
        }
		
		//On error
        if (isset($_REQUEST['error']) && $_REQUEST['error'] == 'access_denied') {
             wp_redirect(home_url('/'));
        }

		$redirectURL = workreap_new_social_login_url( 'linkedinlogin' );

		$client = $this->initApi();

        //Request access token
        $response 			= $client->authenticate($_REQUEST['code']);
        $this->access_token = $response->{'access_token'};

        //Get first name, last name and email address, and user picture
        $xml 			= $client->get('https://api.linkedin.com/v2/me?projection=(id,firstName,lastName,positions,profilePicture(displayImage~:playableStreams))');
        $email_data 	= $client->get('https://api.linkedin.com/v2/emailAddress?q=members&projection=(elements*(handle~))');
		$email_data 	= json_decode($email_data);
		$xml 			= json_decode($xml);
		
		
		$locale_firstName 	= $xml->firstName->preferredLocale->language.'_'.$xml->firstName->preferredLocale->country;
		$firstName 			= $xml->firstName->localized->$locale_firstName;
		$locale_lastName	= $xml->lastName->preferredLocale->language.'_'.$xml->lastName->preferredLocale->country;
		$lastName 			= $xml->lastName->localized->$locale_lastName;

		$user = array(
			'id'			=> $xml->id,
			'name'			=> $firstName.' '.$lastName,
			'email'			=> $email_data->elements[0]->{'handle~'}->{'emailAddress'},
			'picture'		=>$xml->profilePicture->{'displayImage~'}->elements[0]->identifiers[0]->identifier,
		);

		if ( !is_user_logged_in() ) {
			$ID = email_exists( $user['email'] );

			if ( $ID == false ) { // Real register
				do_action('workreap_create_social_users','linkedin',$user);
			} else if ( $ID ) { // Login
				do_action('workreap_do_social_login',$ID);
			}
			
			//Redirect
			if ( isset( $_GET[ 'code' ] ) ) {
				$access_token = $response->{'access_token'};
				set_site_transient( workreap_get_uniqid() . '_sp_linkedin_connect', $access_token, 3600 );
				header( 'Location: ' . filter_var( workreap_new_social_login_url( 'linkedin' ), FILTER_SANITIZE_URL ) );
				exit;
			}
		}
		
    }

	/**
     * get login URL
     *
     * @link https://codex.wordpress.org/Function_Reference/get_users
     * @return bool|void
     */
	public function workreap_linkedin_connect() {
		if( function_exists('workreap_is_demo_site') ) { 
			workreap_is_demo_site() ;
		}; //if demo site then prevent
		
		session_start( array('register') );
		
		// Set the Redirect URL:
		$client = $this->initApi();
		$pass = wp_generate_password(12, false);
        $authorize_url = $client->authorizeUrl( array('scope' => 'r_liteprofile r_emailaddress',
													  'state' => $pass)
												   );

		$json['type'] 	 		= 'success';
		$json['authUrl']    	= $authorize_url;
		$json['message'] 		= esc_html__('Please wait while you are redirecting to linkedin for authorizations.', 'workreap_core');
		echo json_encode($json);
		die();
	}
}

new Linkedin_Connect();
