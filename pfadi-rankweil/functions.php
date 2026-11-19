<?php
/**
 * Pfadi Rankweil – Theme-Funktionen
 *
 * @package pfadi-rankweil
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Version kommt aus dem Kopf von style.css (einzige Stelle, die gepflegt wird).
// Sie hängt an CSS/JS-URLs (?ver=…), damit Browser nach einem Update neu laden.
define( 'PFADI_VERSION', (string) wp_get_theme( get_template() )->get( 'Version' ) );
define( 'PFADI_DIR', get_template_directory() );
define( 'PFADI_URI', get_template_directory_uri() );

require PFADI_DIR . '/inc/customizer.php';
require PFADI_DIR . '/inc/template-tags.php';
require PFADI_DIR . '/inc/migration.php';
require PFADI_DIR . '/inc/compat-hitmag.php';
require PFADI_DIR . '/inc/updater.php';
require PFADI_DIR . '/inc/issuu.php';

/**
 * Theme-Grundeinstellungen.
 */
function pfadi_setup() {
	load_theme_textdomain( 'pfadi-rankweil', PFADI_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 320,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_image_size( 'pfadi-hero', 1400, 900, true );
	add_image_size( 'pfadi-card', 720, 480, true );

	register_nav_menus(
		array(
			// Gleiche Namen wie bei HitMag Pro (menu-1, footer-menu, menu-social): WordPress
			// ordnet die Menüs beim Theme-Wechsel dadurch in beide Richtungen 1:1 zu.
			'menu-1'      => __( 'Hauptmenü', 'pfadi-rankweil' ),
			'footer-menu' => __( 'Footer-Menü (z. B. Impressum, Datenschutz)', 'pfadi-rankweil' ),
			'menu-social' => __( 'Social-Media-Links (ersetzt die Felder im Customizer)', 'pfadi-rankweil' ),
		)
	);

	// Farbpalette für den Block-Editor.
	add_theme_support(
		'editor-color-palette',
		array(
			array( 'name' => 'Pfadi Navy', 'slug' => 'navy', 'color' => '#05368B' ),
			array( 'name' => 'Pfadi Rot', 'slug' => 'rot', 'color' => '#D3031C' ),
			array( 'name' => 'Sand', 'slug' => 'sand', 'color' => '#F5F1EA' ),
			array( 'name' => 'Biber Braun', 'slug' => 'biber', 'color' => '#904837' ),
			array( 'name' => 'WiWö Gelb', 'slug' => 'wiwo', 'color' => '#FBBB21' ),
			array( 'name' => 'GuSp Grün', 'slug' => 'gusp', 'color' => '#159A34' ),
			array( 'name' => 'CaEx Blau', 'slug' => 'caex', 'color' => '#0B4697' ),
			array( 'name' => 'RaRo Rot', 'slug' => 'raro', 'color' => '#E62336' ),
			array( 'name' => 'PWA Pink', 'slug' => 'pwa', 'color' => '#E6007E' ),
			array( 'name' => 'Dunkel', 'slug' => 'dunkel', 'color' => '#0E1A2B' ),
			array( 'name' => 'Weiß', 'slug' => 'weiss', 'color' => '#FFFFFF' ),
		)
	);
}
add_action( 'after_setup_theme', 'pfadi_setup' );

function pfadi_content_width() {
	$GLOBALS['content_width'] = 780;
}
add_action( 'after_setup_theme', 'pfadi_content_width', 0 );

/**
 * Widget-Bereiche.
 */
function pfadi_widgets_init() {
	$defaults = array(
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	);

	register_sidebar(
		array_merge(
			$defaults,
			array(
				'name'        => __( 'Seitenleiste', 'pfadi-rankweil' ),
				'id'          => 'sidebar-1',
				'description' => __( 'Seitenleiste neben Beiträgen und Archiven.', 'pfadi-rankweil' ),
			)
		)
	);
	register_sidebar(
		array_merge(
			$defaults,
			array(
				'name'        => __( 'Startseite – unter den Berichten', 'pfadi-rankweil' ),
				'id'          => 'home-1',
				'description' => __( 'Volle Breite auf der Startseite, z. B. Newsletter-Anmeldung.', 'pfadi-rankweil' ),
			)
		)
	);
	// Kennungen wie bei HitMag Pro → beim Theme-Wechsel landet jedes Widget am gleichen Platz.
	$footer_areas = array(
		'footer-left'  => __( 'Footer links', 'pfadi-rankweil' ),
		'footer-mid'   => __( 'Footer Mitte', 'pfadi-rankweil' ),
		'footer-right' => __( 'Footer rechts', 'pfadi-rankweil' ),
	);
	foreach ( $footer_areas as $area_id => $area_name ) {
		register_sidebar(
			array_merge(
				$defaults,
				array(
					'name' => $area_name,
					'id'   => $area_id,
				)
			)
		);
	}
}
add_action( 'widgets_init', 'pfadi_widgets_init' );

/**
 * CSS & JS.
 */
function pfadi_scripts() {
	wp_enqueue_style( 'pfadi-style', get_stylesheet_uri(), array(), PFADI_VERSION );
	wp_enqueue_style( 'pfadi-main', PFADI_URI . '/assets/css/main.css', array(), PFADI_VERSION );
	wp_add_inline_style( 'pfadi-main', pfadi_customizer_css() );

	wp_enqueue_script( 'pfadi-main', PFADI_URI . '/assets/js/main.js', array(), PFADI_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'pfadi_scripts' );

/**
 * Body-Klassen für Layout-Optionen.
 */
function pfadi_body_classes( $classes ) {
	$sidebar = get_theme_mod( 'pfadi_sidebar', 'right' );
	if ( 'none' === $sidebar || ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	} else {
		$classes[] = 'sidebar-' . $sidebar;
	}
	$classes[] = 'blog-' . get_theme_mod( 'pfadi_blog_layout', 'grid' );
	if ( get_theme_mod( 'pfadi_sticky_header', true ) ) {
		$classes[] = 'has-sticky-header';
	}
	// Stufen-Seiten (z. B. /spaeher/) bekommen die Stufenfarbe – Zuordnung wie bei den Kategorien.
	if ( is_page() ) {
		$slug = get_post_field( 'post_name', get_queried_object_id() );
		$s    = pfadi_stufe_of_term( (object) array( 'slug' => $slug ) );
		if ( $s[0] ) {
			$classes[] = 'page-stufe-' . $s[0];
		}
	}
	return $classes;
}
add_filter( 'body_class', 'pfadi_body_classes' );

/**
 * Auszugslänge.
 */
add_filter(
	'excerpt_length',
	function () {
		return (int) get_theme_mod( 'pfadi_excerpt_length', 22 );
	}
);
add_filter(
	'excerpt_more',
	function () {
		return '…';
	}
);

/**
 * Dark Mode: setzt data-theme sofort im <head>, damit die Seite nicht hell aufblitzt.
 * Modi: auto (System + Schalter), manual (Standard hell + Schalter), off.
 */
function pfadi_dark_mode_head() {
	$mode = pfadi_mod( 'pfadi_dark_mode', 'auto' );
	if ( 'off' === $mode ) {
		return;
	}
	?>
	<script>
	(function(){var m=<?php echo wp_json_encode( $mode ); ?>,k='pfadi-theme',d=document.documentElement,s=null,q=window.matchMedia&&matchMedia('(prefers-color-scheme: dark)');
	try{s=localStorage.getItem(k)}catch(e){}
	function a(){var t=s||(m==='auto'&&q&&q.matches?'dark':'light');d.setAttribute('data-theme',t);var b=document.querySelectorAll('.theme-toggle');for(var i=0;i<b.length;i++){b[i].setAttribute('aria-pressed',t==='dark'?'true':'false');b[i].setAttribute('aria-label',t==='dark'?'Hellen Modus einschalten':'Dunkelmodus einschalten');}var c=document.querySelector('meta[name=theme-color]');if(c)c.content=t==='dark'?'#0B1220':c.getAttribute('data-light');}
	a();window.addEventListener('pageshow',a);document.addEventListener('DOMContentLoaded',a);if(m==='auto'&&q&&q.addEventListener)q.addEventListener('change',function(){if(!s)a()});
	window.pfadiToggleTheme=function(){s=d.getAttribute('data-theme')==='dark'?'light':'dark';try{localStorage.setItem(k,s)}catch(e){}a();};})();
	</script>
	<?php
}
add_action( 'wp_head', 'pfadi_dark_mode_head', 1 );

/**
 * Klassische Widget-Verwaltung wie bei HitMag (abschaltbar im Customizer).
 */
add_filter(
	'use_widgets_block_editor',
	function ( $use ) {
		return get_theme_mod( 'pfadi_classic_widgets', true ) ? false : $use;
	}
);

/**
 * Archiv-Titel ohne „Kategorie:“ / „Schlagwort:“-Präfix.
 */
add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );

/**
 * Pingback-Header.
 */
function pfadi_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'pfadi_pingback_header' );

/**
 * Fallback, solange kein Menü zugewiesen ist: Seiten der obersten Ebene.
 */
function pfadi_menu_fallback() {
	echo '<ul class="menu">';
	wp_list_pages(
		array(
			'title_li' => '',
			'depth'    => 1,
		)
	);
	echo '</ul>';
}

/**
 * Submenü-Toggle-Buttons für mobile Navigation.
 */
function pfadi_nav_submenu_toggle( $item_output, $item, $depth, $args ) {
	if ( isset( $args->theme_location ) && 'menu-1' === $args->theme_location && in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
		$item_output .= '<button class="submenu-toggle" aria-expanded="false"><span class="screen-reader-text">' . esc_html__( 'Untermenü öffnen', 'pfadi-rankweil' ) . '</span>' . pfadi_icon( 'chevron' ) . '</button>';
	}
	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'pfadi_nav_submenu_toggle', 10, 4 );
