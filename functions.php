<?php
/**
 * Author: Alin Marcu
 * Author URI: http://deconf.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

function gads_dash_safe_get($key) {
	if (array_key_exists ( $key, $_POST )) {
		return $_POST [$key];
	}
	return false;
}
function gads_dash_pretty_error($e) {
	return '<p>'.esc_html($e->getMessage()).'</p><p>'. __('For further help and support go to', 'gads-dash' ).' <a href="http://deconf.com/ask/" target="_blank">' . __ ( "Deconf Help Center", 'gads-dash' ) . '</a></p>';
}
class AdSenseAuth {
	protected $client;
	protected $adSenseService;
	private $user, $authUrl;
	public function __construct() {
		
		// If at least PHP 5.3.0 use the autoloader, if not try to edit the include_path
		if (version_compare ( PHP_VERSION, '5.3.0' ) >= 0) {
			require 'vendor/autoload.php';
		} else {
			set_include_path ( dirname ( __FILE__ ).'/src/'. PATH_SEPARATOR . get_include_path () );
			// Include GAPI client
			if (! class_exists ( 'Google_Client' )) {
				require_once 'Google/Client.php';
			}
			// Include GAPI AdSense Service
			if (! class_exists ( 'Google_Service_AdSense' )) {
				require_once 'Google/Service/AdSense.php';
			}
		}
		
		$this->client = new Google_Client ();
		$this->client->setAccessType ( 'offline' );
		$this->client->setApplicationName ( 'Google Adsense Dashboard for WP' );
		$this->client->setRedirectUri ( 'urn:ietf:wg:oauth:2.0:oob' );
		
		if (get_option ( 'gads_dash_userapi' )) {
			$this->client->setClientId ( get_option ( 'gads_dash_clientid' ) );
			$this->client->setClientSecret ( get_option ( 'gads_dash_clientsecret' ) );
			$this->client->setDeveloperKey ( get_option ( 'gads_dash_apikey' ) );
		} else {
			$this->client->setClientId ( '265189663307.apps.googleusercontent.com' );
			$this->client->setClientSecret ( 'B-LxlsVehit2CCzF5ke-SK6T' );
			$this->client->setDeveloperKey ( 'AIzaSyDH3q3w33uLpH4GN25CZqoWE_Nkcpk2UmY' );
		}
		
		$this->adSenseService = new Google_Service_AdSense ( $this->client );
	}
	function gads_dash_store_token($user, $token) {
		update_option ( 'gads_dash_user', $user );
		update_option ( 'gads_dash_token', $token );
	}
	function gads_dash_get_token() {
		if (get_option ( 'gads_dash_token' )) {
			return get_option ( 'gads_dash_token' );
		} else {
			return;
		}
	}
	public function gads_dash_reset_token() {
		update_option ( 'gads_dash_token', "" );
	}
	function gads_dash_clear_cache() {
		global $wpdb;
		$sqlquery = $wpdb->query ( "DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_gadsdash%%'" );
		$sqlquery = $wpdb->query ( "DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_gadsdash%%'" );
	}
	function authenticate($user) {
		$this->user = $user;
		$token = $this->gads_dash_get_token ();
		
		if (isset ( $token )) {
			$this->client->setAccessToken ( $token );
		} else {
			$this->client->setScopes ( array (
					"https://www.googleapis.com/auth/adsense.readonly" 
			) );
			$this->authUrl = $this->client->createAuthUrl ();
			if (! isset ( $_REQUEST ['gads_dash_authorize'] )) {
				if (! current_user_can ( 'manage_options' )) {
					_e ( "Ask an admin to authorize this Application", 'gads-dash' );
					return;
				}
				
				echo '<div style="padding:20px;">' . __ ( "Use this link to get your access code:", 'gads-dash' ) . ' <a href="' . $this->authUrl . '" target="_blank">' . __ ( "Get Access Code", 'gads-dash' ) . '</a>';
				echo '<form name="input" action="#" method="POST">
							<p><b>' . __ ( "Access Code:", 'gads-dash' ) . ' </b><input type="text" name="gads_dash_code" value="" size="35"></p>
							<input type="submit" class="button button-primary" name="gads_dash_authorize" value="' . __ ( "Save Access Code", 'gads-dash' ) . '"/>
						</form>
					</div>';
				return;
			} else if (isset ( $_REQUEST ['gads_dash_code'] )) {
				$this->client->authenticate ( $_REQUEST ['gads_dash_code'] );
				$this->gads_dash_store_token ( $this->user, $this->client->getAccessToken () );
			} else {
				$adminurl = admin_url ( "#gads-dash-widget" );
				echo '<script> window.location="' . $adminurl . '"; </script> ';
			}
		}
	}
	function getAdSenseService() {
		return $this->adSenseService;
	}
	function gads_dash_refreshToken() {
		if ($this->client->getAccessToken () != null) {
			$this->gads_dash_store_token ( 'default', $this->client->getAccessToken () );
		}
	}
}

?>