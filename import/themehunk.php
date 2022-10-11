<?php
define('THEMEHUNK_THEME_NAME',esc_html__( 'TH Shop Mania', 'mania-companion' ));

if ( ! defined( 'THEMEHUNK_SITE_LIBRARY_VER' ) ) {
	define( 'THEMEHUNK_SITE_LIBRARY_VER', 1.0 );
}

if ( ! defined( 'ALLOW_UNFILTERED_UPLOADS' ) ) {
	define( 'ALLOW_UNFILTERED_UPLOADS', true );
}

if ( ! defined( 'THEMEHUNK_THEME_SETTINGS' ) ) {
	define( 'THEMEHUNK_THEME_SETTINGS', __( 'Themehunk Sites', 'mania-companion' ) );
}

if ( ! defined( 'THEMEHUNK_SITE_LIBRARY_FILE' ) ) {
	define( 'THEMEHUNK_SITE_LIBRARY_FILE', __FILE__ );
}


if ( ! defined( 'THEMEHUNK_SITE_LIBRARY_BASE' ) ) {
	define( 'THEMEHUNK_SITE_LIBRARY_BASE', plugin_basename( THEMEHUNK_SITE_LIBRARY_FILE ) );
}

if ( ! defined( 'THEMEHUNK_SITE_LIBRARY_DIR' ) ) {
	define( 'THEMEHUNK_SITE_LIBRARY_DIR', MANIA_COMPANION_PLUGIN_PATH.'/import/' );
}

if ( ! defined( 'THEMEHUNK_SITE_LIBRARY_URI' ) ) {
	define( 'THEMEHUNK_SITE_LIBRARY_URI', MANIA_COMPANION_PLUGIN_URI.'/import/' );
}

if ( ! function_exists( 'themehunk_library_setup' ) ) :

	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );


	/**
	 * Themehunk Sites Setup
	 *
	 * @since 1.0.5
	 */
	function themehunk_library_setup() {
	require_once THEMEHUNK_SITE_LIBRARY_DIR . 'inc/library-page.php';
	require_once THEMEHUNK_SITE_LIBRARY_DIR . 'inc/admin-load-page.php';
	}

	add_action( 'init', 'themehunk_library_setup' );

endif;