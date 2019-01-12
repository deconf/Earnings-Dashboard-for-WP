<?php
/**
 * Author: Alin Marcu
 * Author URI: https://deconf.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
require_once 'functions.php';

if (! current_user_can('manage_options')) {
    return;
}

if (isset($_POST['Clear']) && isset($_POST['gads_security']) && wp_verify_nonce($_POST['gads_security'], 'gads_dash_form')) {
    $auth = new AdSenseAuth();
    $auth->gads_dash_clear_cache();
    ?><div class="updated">
	<p>
		<strong><?php _e('Cleared Cache.', 'google-adsense-dashboard-for-wp' ); ?></strong>
	</p>
</div>
<?php
}
if (isset($_POST['Reset']) && isset($_POST['gads_security']) && wp_verify_nonce($_POST['gads_security'], 'gads_dash_form')) {
    $auth = new AdSenseAuth();
    $auth->gads_dash_clear_cache();
    $auth->gads_dash_reset_token();
    ?><div class="updated">
	<p>
		<strong><?php _e('Token Reseted.', 'google-adsense-dashboard-for-wp'); ?></strong>
	</p>
</div>
<?php
} else
    if (gads_dash_safe_get('gads_dash_hidden') == 'Y' && isset($_POST['gads_security']) && wp_verify_nonce($_POST['gads_security'], 'gads_dash_form')) {
        // Form data sent
        $apikey = gads_dash_safe_get('gads_dash_apikey');
        if ($apikey) {
            update_option('gads_dash_apikey', sanitize_text_field($apikey));
        }

        $clientid = gads_dash_safe_get('gads_dash_clientid');
        if ($clientid) {
            update_option('gads_dash_clientid', sanitize_text_field($clientid));
        }

        $clientsecret = gads_dash_safe_get('gads_dash_clientsecret');
        if ($clientsecret) {
            update_option('gads_dash_clientsecret', sanitize_text_field($clientsecret));
        }

        $dashaccess = gads_dash_safe_get('gads_dash_access');
        update_option('gads_dash_access', $dashaccess);

        $gads_dash_channels = gads_dash_safe_get('gads_dash_channels');
        update_option('gads_dash_channels', $gads_dash_channels);

        $gads_dash_ads = gads_dash_safe_get('gads_dash_ads');
        update_option('gads_dash_ads', $gads_dash_ads);

        $gads_dash_style = gads_dash_safe_get('gads_dash_style');
        update_option('gads_dash_style', $gads_dash_style);

        $gads_dash_cachetime = gads_dash_safe_get('gads_dash_cachetime');
        update_option('gads_dash_cachetime', $gads_dash_cachetime);

        $gads_dash_timezone = gads_dash_safe_get('gads_dash_timezone');
        update_option('gads_dash_timezone', (bool) $gads_dash_timezone);

        $gads_dash_userapi = gads_dash_safe_get('gads_dash_userapi');
        update_option('gads_dash_userapi', $gads_dash_userapi);

        if (! isset($_POST['Clear']) and ! isset($_POST['Reset'])) {
            ?>
<div class="updated">
	<p>
		<strong><?php _e('Options saved.', 'google-adsense-dashboard-for-wp'); ?></strong>
	</p>
</div>
<?php
        }
    } else
        if (gads_dash_safe_get('gads_dash_hidden') == 'A' && isset($_POST['gads_security']) && wp_verify_nonce($_POST['gads_security'], 'gads_dash_form')) {
            $apikey = gads_dash_safe_get('gads_dash_apikey');
            if ($apikey) {
                update_option('gads_dash_apikey', sanitize_text_field($apikey));
            }

            $clientid = gads_dash_safe_get('gads_dash_clientid');
            if ($clientid) {
                update_option('gads_dash_clientid', sanitize_text_field($clientid));
            }

            $clientsecret = gads_dash_safe_get('gads_dash_clientsecret');
            if ($clientsecret) {
                update_option('gads_dash_clientsecret', sanitize_text_field($clientsecret));
            }

            $gads_dash_userapi = gads_dash_safe_get('gads_dash_userapi');
            update_option('gads_dash_userapi', $gads_dash_userapi);
        }

if (isset($_POST['Authorize'])) {
    $adminurl = admin_url("#gads-dash-widget");
    echo '<script> window.location="' . $adminurl . '"; </script> ';
}

if (! get_option('gads_dash_access')) {
    update_option('gads_dash_access', "manage_options");
}

if (! get_option('gads_dash_style')) {
    update_option('gads_dash_style', "green");
}

$apikey = get_option('gads_dash_apikey');
$clientid = get_option('gads_dash_clientid');
$clientsecret = get_option('gads_dash_clientsecret');
$dashaccess = get_option('gads_dash_access');
$gads_dash_channels = get_option('gads_dash_channels');
$gads_dash_ads = get_option('gads_dash_ads');
$gads_dash_style = get_option('gads_dash_style');
$gads_dash_cachetime = get_option('gads_dash_cachetime');
$gads_dash_timezone = get_option('gads_dash_timezone');
$gads_dash_userapi = get_option('gads_dash_userapi');

if (is_rtl()) {
    $float_main = "right";
    $float_note = "left";
} else {
    $float_main = "left";
    $float_note = "right";
}

?>
<div class="wrap">
		<?php echo "<h2>" . __( "Earnings Dashboard Settings", 'google-adsense-dashboard-for-wp' ) . "</h2>"; ?><hr>
</div>

<div id="poststuff">
	<div id="post-body" class="metabox-holder columns-2">
		<div id="post-body-content">
			<div class="settings-wrapper">
				<div class="inside">
					<form name="gads_dash_form" method="post"
						action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
			<?php wp_nonce_field('gads_dash_form','gads_security'); ?>
		<h2><?php _e( 'Google Adsense API', 'google-adsense-dashboard-for-wp' ); ?></h2>
						<p><?php echo __("You should read/watch this step by step", 'google-adsense-dashboard-for-wp')." <a href='https://deconf.com/earnings-dashboard-google-adsense-wordpress/' target='_blank'> ". __("video tutorial")."</a> ".__("before proceeding to authorization", 'google-adsense-dashboard-for-wp');?></p>
						<p>
							<input name="gads_dash_userapi" type="checkbox"
								id="gads_dash_userapi" onchange="this.form.submit()" value="1"
								<?php checked(get_option('gads_dash_userapi'),1); ?> /><strong><?php _e(" use your own API Project credentials", 'google-adsense-dashboard-for-wp' )?></strong>
						</p>
		<?php
if (get_option('gads_dash_userapi')) {
    ?>
			<p>
							<strong><?php _e("API Key:", 'google-adsense-dashboard-for-wp');?></strong> <input
								type="text" name="gads_dash_apikey"
								value="<?php echo esc_attr($apikey); ?>" size="60">
						</p>
						<p>
							<strong><?php _e("Client ID:", 'google-adsense-dashboard-for-wp');?></strong><input
								type="text" name="gads_dash_clientid"
								value="<?php echo esc_attr($clientid); ?>" size="60">
						</p>
						<p>
							<strong><?php _e("Client Secret:", 'google-adsense-dashboard-for-wp');?></strong><input
								type="text" name="gads_dash_clientsecret"
								value="<?php echo esc_attr($clientsecret); ?>" size="60">
						</p>
		<?php
}
?>
		<p><?php
if (get_option('gads_dash_token')) {
    ?>
			<input type="submit" name="Reset" class="button button-secondary"
								value="<?php _e( "Clear Authorization", 'google-adsense-dashboard-for-wp' );?>" /> <input
								type="submit" name="Clear" class="button button-secondary"
								value="<?php _e('Clear Cache', 'google-adsense-dashboard-for-wp' ) ?>" /> <input
								type="hidden" name="gads_dash_hidden" value="Y">
		<?php
} else {
    ?>
			<input type="submit" name="Authorize" class="button button-secondary"
								value="<?php _e( "Authorize Application", 'google-adsense-dashboard-for-wp' );?>" /> <input
								type="submit" name="Clear" class="button button-secondary"
								value="<?php _e('Clear Cache', 'google-adsense-dashboard-for-wp' ) ?>" /> <input
								type="hidden" name="gads_dash_hidden" value="A">

						</p>
					</form>
			<?php _e ( "(the rest of the settings will show up after completing the authorization process)", 'google-adsense-dashboard-for-wp' ); ?>
				</div>
<?php

deconfsidebar();
    return;
}
?>
		<hr>
				<h2> <?php _e( 'Access Level', 'google-adsense-dashboard-for-wp' );?></h2>
				<p><?php _e("View Access Level: ", 'google-adsense-dashboard-for-wp' ); ?>
		<select id="gads_dash_access" name="gads_dash_access">
						<option value="manage_options"
							<?php selected( $dashaccess, "manage_options" ); ?>><?php echo __("Administrators", 'clicky-analytics');?></option>
						<option value="edit_pages"
							<?php selected( $dashaccess, "edit_pages" ); ?>><?php echo __("Editors", 'clicky-analytics');?></option>
						<option value="publish_posts"
							<?php selected( $dashaccess, "publish_posts" ); ?>><?php echo __("Authors", 'clicky-analytics');?></option>
						<option value="edit_posts"
							<?php selected( $dashaccess, "edit_posts" ); ?>><?php echo __("Contributors", 'clicky-analytics');?></option>
					</select>
				</p>
				<hr>
				<h2><?php _e( 'Additional Settings', 'google-adsense-dashboard-for-wp' );?></h2>
				<p>
					<input name="gads_dash_channels" type="checkbox"
						id="gads_dash_channels" value="1"
						<?php checked(get_option('gads_dash_channels'),1); ?> /><?php _e(" show Custom Channels performance report", 'google-adsense-dashboard-for-wp' ); ?></p>
				<p>
					<input name="gads_dash_ads" type="checkbox" id="gads_dash_ads"
						value="1" <?php checked(get_option('gads_dash_ads'),1);?> /><?php _e(" show Ad Units performance report", 'google-adsense-dashboard-for-wp' ); ?></p>
				<p><?php _e("CSS Settings: ", 'google-adsense-dashboard-for-wp' ); ?>
		<select id="gads_dash_style" name="gads_dash_style">
						<option value="green" <?php selected($gads_dash_style,'green');?>> <?php _e("Green Theme", 'google-adsense-dashboard-for-wp');?></option>
						<option value="light" <?php selected($gads_dash_style,'light');?>> <?php _e("Light Theme", 'google-adsense-dashboard-for-wp');?></option>
					</select>
				</p>
				<hr>
				<h2><?php _e( 'Cache Settings', 'google-adsense-dashboard-for-wp' );?></h2>
				<p><?php _e("Cache Time: ", 'google-adsense-dashboard-for-wp' ); ?>
		<select id="gads_dash_cachetime" name="gads_dash_cachetime">
						<option value="900" <?php selected($gads_dash_cachetime,900);?>><?php _e("15 minutes", 'google-adsense-dashboard-for-wp');?></option>
						<option value="1800" <?php selected($gads_dash_cachetime,1800);?>><?php _e("30 minutes", 'google-adsense-dashboard-for-wp');?></option>
						<option value="3600" <?php selected($gads_dash_cachetime,3600);?>><?php _e("1 hour", 'google-adsense-dashboard-for-wp');?></option>
						<option value="7200" <?php selected($gads_dash_cachetime,7200);?>><?php _e("2 hours", 'google-adsense-dashboard-for-wp');?></option>
					</select>
				</p>
				<hr>
				<h2><?php _e( 'Google Adsense Time Zone', 'google-adsense-dashboard-for-wp' );?></h2>

				<p><?php _e("Time Zone: ", 'google-adsense-dashboard-for-wp' ); ?>
	<select id="gads_dash_timezone" name="gads_dash_timezone">
						<option value="0" <?php selected($gads_dash_timezone, 0);?>><?php _e("Billing time zone (PST)", 'google-adsense-dashboard-for-wp');?></option>
						<option value="1" <?php selected($gads_dash_timezone, 1);?>><?php _e("Account time zone", 'google-adsense-dashboard-for-wp');?></option>
					</select>
				</p>

				<p class="submit">
					<input type="submit" name="Submit" class="button button-primary"
						value="<?php _e('Update Options', 'google-adsense-dashboard-for-wp' ) ?>" />
				</p>
				</form>
			</div>

<?php

deconfsidebar();

function deconfsidebar()
{
    ?>

		</div>
	</div>

	<div id="postbox-container-1" class="postbox-container">
		<div class="meta-box-sortables">
			<div class="postbox">
				<h3>
					<span><?php _e("Try it out, it's free!",'google-adsense-dashboard-for-wp') ?></span>
				</h3>
				<div class="inside">
					<a href="https://wordpress.org/plugins/search-engine-insights/" target="_blank"><img src="<?php echo plugins_url( 'images/seiwp.png' , __FILE__ );?>" width="100%" alt="" /></a>
				</div>
			</div>
			<div class="postbox">
				<h3>
					<span><?php _e("Support & Reviews",'google-adsense-dashboard-for-wp')?></span>
				</h3>
				<div class="inside">
					<div class="deconf-title">
						<a
							href="https://deconf.com/earnings-dashboard-google-adsense-wordpress/?utm_source=gadswp_config&utm_medium=link&utm_content=support&utm_campaign=gadswp"><img
							src="<?php echo plugins_url( 'images/help.png' , __FILE__ ); ?>" /></a>
					</div>
					<div class="deconf-desc"><?php echo  __('Plugin documentation and support on', 'google-adsense-dashboard-for-wp') . ' <a href="https://deconf.com/earnings-dashboard-google-adsense-wordpress/?utm_source=gadswp_config&utm_medium=link&utm_content=support&utm_campaign=gadswp">deconf.com</a>.'; ?></div>
					<br />
					<div class="deconf-title">
						<a
							href="https://wordpress.org/support/view/plugin-reviews/google-adsense-dashboard-for-wp#plugin-info"><img
							src="<?php echo plugins_url( 'images/star.png' , __FILE__ ); ?>" /></a>
					</div>
					<div class="deconf-desc"><?php echo  __('Your feedback and review are both important,', 'google-adsense-dashboard-for-wp').' <a href="https://wordpress.org/support/view/plugin-reviews/google-adsense-dashboard-for-wp#plugin-info">'.__('rate this plugin', 'google-adsense-dashboard-for-wp').'</a>!'; ?></div>
				</div>
			</div>
			<div class="postbox">
				<h3>
					<span><?php _e("Further Reading",'google-adsense-dashboard-for-wp')?></span>
				</h3>
				<div class="inside">
					<div class="deconf-title">
						<a
							href="https://deconf.com/move-website-https-ssl/?utm_source=gadswp_config&utm_medium=link&utm_content=ssl&utm_campaign=gadswp"><img
							src="<?php echo plugins_url( 'images/ssl.png' , __FILE__ ); ?>" /></a>
					</div>
					<div class="deconf-desc"><?php echo  '<a href="https://deconf.com/move-website-https-ssl/?utm_source=gadswp_config&utm_medium=link&utm_content=ssl&utm_campaign=gadswp">'.__('Improve search rankings', 'google-adsense-dashboard-for-wp').'</a> '.__('by moving your website to HTTPS/SSL.', 'google-adsense-dashboard-for-wp'); ?></div>
					<br />
					<div class="deconf-title">
						<a
							href="https://deconf.com/wordpress/?utm_source=gadswp_config&utm_medium=link&utm_content=plugins&utm_campaign=gadswp"><img
							src="<?php echo plugins_url( 'images/wp.png' , __FILE__ ); ?>" /></a>
					</div>
					<div class="deconf-desc"><?php echo  __('Other', 'google-adsense-dashboard-for-wp').' <a href="https://deconf.com/wordpress/?utm_source=gadswp_config&utm_medium=link&utm_content=plugins&utm_campaign=gadswp">'.__('WordPress Plugins', 'google-adsense-dashboard-for-wp').'</a> '.__('written by the same author', 'google-adsense-dashboard-for-wp').'.'; ?></div>
				</div>
			</div>
			<div class="postbox">
				<h3>
					<span><?php _e("Other Services",'google-adsense-dashboard-for-wp')?></span>
				</h3>
				<div class="inside">
					<div class="deconf-title">
						<a href="http://tracking.maxcdn.com/c/94142/36539/378"><img
							src="<?php echo plugins_url( 'images/mcdn.png' , __FILE__ ); ?>" /></a>
					</div>
					<div class="deconf-desc"><?php echo  __('Speed up your website and plug into a whole', 'google-adsense-dashboard-for-wp').' <a href="http://tracking.maxcdn.com/c/94142/36539/378">'.__('new level of site speed', 'google-adsense-dashboard-for-wp').'</a>.'; ?></div>
					<br />
					<div class="deconf-title">
						<a
							href="https://deconf.com/clicky-web-analytics-review/?utm_source=gadswp_config&utm_medium=link&utm_content=clicky&utm_campaign=gadswp"><img
							src="<?php echo plugins_url( 'images/clicky.png' , __FILE__ ); ?>" /></a>
					</div>
					<div class="deconf-desc"><?php echo  '<a href="https://deconf.com/clicky-web-analytics-review/?utm_source=gadswp_config&utm_medium=link&utm_content=clicky&utm_campaign=gadswp">'.__('Web Analytics', 'google-adsense-dashboard-for-wp').'</a> '.__('service with users tracking at IP level.', 'google-adsense-dashboard-for-wp'); ?></div>
				</div>
			</div>
		</div>
	</div>

</div>

<?php }?>