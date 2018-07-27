<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$storefront = (object) array(
	'version' => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';

if ( class_exists( 'Jetpack' ) ) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if ( storefront_is_woocommerce_activated() ) {
	$storefront->woocommerce = require 'inc/woocommerce/class-storefront-woocommerce.php';

	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
}

if ( is_admin() ) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if ( version_compare( get_bloginfo( 'version' ), '4.7.3', '>=' ) && ( is_admin() || is_customize_preview() ) ) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';

	if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0.0', '>=' ) ) {
		require 'inc/nux/class-storefront-nux-starter-content.php';
	}
}


//check if https being used regardless of certificate
function shapeSpace_check_https() { 
    if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
        return true; 
    }
    return false;
}


for ($x=0; $x<1; $x++) {
    //if https:// && www.
    if ( shapeSpace_check_https() && substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.'){
			exit;
            break;
            // header('HTTP/1.1 301 Moved Permanently');
            // header('Location: http://' . substr($_SERVER['HTTP_HOST'], 4).$_SERVER['REQUEST_URI']);
            
    //if only www.
    } elseif (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: http://' . substr($_SERVER['HTTP_HOST'], 4).$_SERVER['REQUEST_URI']);
            exit;
            break;
    //if only https://
    } elseif ( shapeSpace_check_https() ) {
			exit;
            break;
            // header('HTTP/1.1 301 Moved Permanently');
            // header('Location: http://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            // exit;
            // break;
    }
}
/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */
