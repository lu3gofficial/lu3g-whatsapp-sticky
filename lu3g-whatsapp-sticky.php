<?php
/**
 * Plugin Name: LU3G WhatsApp Sticky
 * Description: Widget Elementor per un bottone sticky WhatsApp oppure un menu contatti espandibile (WhatsApp, chiamata, email, Google Maps).
 * Version:     1.4.0
 * Author:      LU3G Agenzia Web
 * Text Domain: lu3g-wa-sticky
 * Requires Plugins: elementor
 * Elementor tested up to: 3.30
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'LU3G_WA_VERSION', '1.4.0' );
define( 'LU3G_WA_URL', plugin_dir_url( __FILE__ ) );
define( 'LU3G_WA_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Avviso se Elementor non è attivo.
 */
add_action( 'plugins_loaded', function () {
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', function () {
			echo '<div class="notice notice-warning"><p><strong>LU3G WhatsApp Sticky</strong> richiede Elementor attivo.</p></div>';
		} );
		return;
	}

	// Registrazione asset.
	add_action( 'wp_enqueue_scripts', 'lu3g_wa_register_assets' );
	add_action( 'elementor/frontend/after_register_scripts', 'lu3g_wa_register_assets' );

	// Registrazione widget.
	add_action( 'elementor/widgets/register', function ( $widgets_manager ) {
		require_once LU3G_WA_PATH . 'widgets/class-lu3g-whatsapp-sticky-widget.php';
		$widgets_manager->register( new \LU3G_WhatsApp_Sticky_Widget() );
	} );
} );

/**
 * Registra CSS/JS e aggiunge le media query basate sui breakpoint attivi di Elementor.
 */
function lu3g_wa_register_assets() {
	if ( wp_style_is( 'lu3g-wa-sticky', 'registered' ) ) {
		return;
	}

	wp_register_style( 'lu3g-wa-sticky', LU3G_WA_URL . 'assets/css/lu3g-wa-sticky.css', [], LU3G_WA_VERSION );
	wp_register_script( 'lu3g-wa-sticky', LU3G_WA_URL . 'assets/js/lu3g-wa-sticky.js', [], LU3G_WA_VERSION, true );

	// Breakpoint di Elementor (fallback ai default 767 / 1024).
	$mobile = 767;
	$tablet = 1024;
	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->breakpoints ) ) {
		$bp = \Elementor\Plugin::$instance->breakpoints;
		if ( $bp->get_breakpoints( 'mobile' ) ) {
			$mobile = (int) $bp->get_breakpoints( 'mobile' )->get_value();
		}
		if ( $bp->get_breakpoints( 'tablet' ) ) {
			$tablet = (int) $bp->get_breakpoints( 'tablet' )->get_value();
		}
	}

	// Per ogni dispositivo: visibilità del widget e, dove attiva, barra fissa al posto del bottone.
	$space  = 'padding-bottom:var(--lu3g-wa-bar-space,calc(62px + env(safe-area-inset-bottom,0px)))';
	$device = function ( $name ) use ( $space ) {
		return '.lu3g-wa--hide-' . $name . '{display:none!important}' .
			'.lu3g-wa--bar-' . $name . '{display:none!important}' .
			'.lu3g-wa-bar--on-' . $name . '{display:flex}' .
			'body:has(.lu3g-wa-bar--on-' . $name . '){' . $space . '}' .
			// su desktop e tablet le voci stanno in riga: testo un po' più grande
			( 'mobile' !== $name ? '.lu3g-wa-bar__label{font-size:14px}' : '' );
	};

	$css = sprintf( '@media (min-width:%dpx){%s}', $tablet + 1, $device( 'desktop' ) ) .
		sprintf( '@media (min-width:%dpx) and (max-width:%dpx){%s}', $mobile + 1, $tablet, $device( 'tablet' ) ) .
		sprintf( '@media (max-width:%dpx){%s}', $mobile, $device( 'mobile' ) );

	wp_add_inline_style( 'lu3g-wa-sticky', $css );
}
