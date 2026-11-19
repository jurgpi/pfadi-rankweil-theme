<?php
/**
 * Customizer-Einstellungen (Design → Anpassen → „Pfadi Theme“).
 *
 * @package pfadi-rankweil
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Standardwerte der vier Stufen (PPÖ-Farben).
 */
function pfadi_stufen_defaults() {
	return array(
		1 => array(
			'name'  => 'Wichtel & Wölflinge',
			'short' => 'WiWö',
			'age'   => '8–11 Jahre',
			'text'  => 'Spielen, basteln, Abenteuer erleben – gemeinsam die Welt entdecken.',
			'color' => '#F7B500',
			'url'   => '/stufen/wichtel/',
			'cats'  => 'wichtel,woelflinge',
		),
		2 => array(
			'name'  => 'Guides & Späher',
			'short' => 'GuSp',
			'age'   => '11–14 Jahre',
			'text'  => 'Patrulle, Lagerfeuer, Hike: Verantwortung übernehmen und draußen sein.',
			'color' => '#2E9E4A',
			'url'   => '/stufen/guides/',
			'cats'  => 'guides,spaeher',
		),
		3 => array(
			'name'  => 'Caravelles & Explorer',
			'short' => 'CaEx',
			'age'   => '14–17 Jahre',
			'text'  => 'Eigene Projekte planen, Grenzen testen und Unternehmungen wagen.',
			'color' => '#1F7FD1',
			'url'   => '/stufen/caravelles/',
			'cats'  => 'caravelles,explorer',
		),
		4 => array(
			'name'  => 'Ranger & Rover',
			'short' => 'RaRo',
			'age'   => '17–20 Jahre',
			'text'  => 'Gemeinschaft, Engagement und internationale Abenteuer.',
			'color' => '#D3031C',
			'url'   => '/stufen/ranger/',
			'cats'  => 'ranger,rover',
		),
	);
}

/**
 * Bequemer Getter mit Defaults.
 */
function pfadi_mod( $key, $default = '' ) {
	return get_theme_mod( $key, $default );
}

/* -------- Sanitizer -------- */
function pfadi_sanitize_checkbox( $v ) {
	return (bool) $v;
}
function pfadi_sanitize_select( $v, $setting ) {
	$choices = $setting->manager->get_control( $setting->id )->choices;
	return array_key_exists( $v, $choices ) ? $v : $setting->default;
}
function pfadi_sanitize_url_or_path( $v ) {
	$v = trim( (string) $v );
	if ( '' === $v ) {
		return '';
	}
	if ( 0 === strpos( $v, '/' ) || 0 === strpos( $v, '#' ) ) {
		return sanitize_text_field( $v );
	}
	return esc_url_raw( $v );
}

/**
 * Relativen Pfad (z. B. „/anmeldung/“) zu voller URL machen.
 */
function pfadi_url( $v ) {
	if ( '' === $v ) {
		return '';
	}
	if ( 0 === strpos( $v, '/' ) ) {
		return home_url( $v );
	}
	return $v;
}

/**
 * Registrierung aller Optionen.
 */
function pfadi_customize_register( $wp_customize ) {

	$wp_customize->add_panel(
		'pfadi_panel',
		array(
			'title'       => __( 'Pfadi Theme', 'pfadi-rankweil' ),
			/* translators: %s: Versionsnummer */
			'description' => sprintf( __( 'Version %s – Änderungen siehe CHANGELOG.md im Theme-Ordner.', 'pfadi-rankweil' ), PFADI_VERSION ),
			'priority'    => 25,
		)
	);

	// Helfer zum kompakten Anlegen von Optionen.
	$add = function ( $id, $section, $label, $type = 'text', $default = '', $extra = array() ) use ( $wp_customize ) {
		$sanitize = 'sanitize_text_field';
		switch ( $type ) {
			case 'checkbox':
				$sanitize = 'pfadi_sanitize_checkbox';
				break;
			case 'textarea':
				$sanitize = 'wp_kses_post';
				break;
			case 'url':
				$sanitize = 'pfadi_sanitize_url_or_path';
				$type     = 'text';
				break;
			case 'number':
				$sanitize = 'absint';
				break;
			case 'select':
			case 'radio':
				$sanitize = 'pfadi_sanitize_select';
				break;
			case 'color':
				$sanitize = 'sanitize_hex_color';
				break;
			case 'image':
				$sanitize = 'absint';
				break;
		}
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $default,
				'sanitize_callback' => $sanitize,
				'transport'         => 'refresh',
			)
		);
		$args = array_merge(
			array(
				'label'   => $label,
				'section' => $section,
				'type'    => $type,
			),
			$extra
		);
		if ( 'color' === $type ) {
			unset( $args['type'] );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, $args ) );
		} elseif ( 'image' === $type ) {
			unset( $args['type'] );
			$args['mime_type'] = 'image';
			$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $id, $args ) );
		} else {
			$wp_customize->add_control( $id, $args );
		}
	};

	$section = function ( $id, $title, $prio ) use ( $wp_customize ) {
		$wp_customize->add_section(
			$id,
			array(
				'title'    => $title,
				'panel'    => 'pfadi_panel',
				'priority' => $prio,
			)
		);
	};

	// Kategorien für Auswahlfelder.
	$cats = array( 0 => __( '– Alle Kategorien –', 'pfadi-rankweil' ) );
	foreach ( get_categories( array( 'hide_empty' => false ) ) as $c ) {
		$cats[ $c->term_id ] = $c->name;
	}

	/* ---------- Farben & Layout ---------- */
	$section( 'pfadi_layout', __( 'Farben & Layout', 'pfadi-rankweil' ), 10 );
	$add( 'pfadi_color_primary', 'pfadi_layout', __( 'Hauptfarbe (Navy)', 'pfadi-rankweil' ), 'color', '#05368B' );
	$add( 'pfadi_color_accent', 'pfadi_layout', __( 'Akzentfarbe (Rot)', 'pfadi-rankweil' ), 'color', '#D3031C' );
	$add( 'pfadi_color_bg', 'pfadi_layout', __( 'Hintergrund', 'pfadi-rankweil' ), 'color', '#F5F1EA' );
	$add(
		'pfadi_dark_mode',
		'pfadi_layout',
		__( 'Dark Mode', 'pfadi-rankweil' ),
		'select',
		'auto',
		array(
			'choices' => array(
				'auto'   => __( 'Automatisch (System) + Umschalter', 'pfadi-rankweil' ),
				'manual' => __( 'Standard hell + Umschalter', 'pfadi-rankweil' ),
				'off'    => __( 'Aus (immer hell)', 'pfadi-rankweil' ),
			),
		)
	);
	$add(
		'pfadi_radius',
		'pfadi_layout',
		__( 'Eckenrundung', 'pfadi-rankweil' ),
		'select',
		'round',
		array(
			'choices' => array(
				'square' => __( 'Eckig', 'pfadi-rankweil' ),
				'soft'   => __( 'Leicht gerundet', 'pfadi-rankweil' ),
				'round'  => __( 'Rund (Standard)', 'pfadi-rankweil' ),
			),
		)
	);
	$add(
		'pfadi_sidebar',
		'pfadi_layout',
		__( 'Seitenleiste', 'pfadi-rankweil' ),
		'select',
		'right',
		array(
			'choices' => array(
				'right' => __( 'Rechts', 'pfadi-rankweil' ),
				'left'  => __( 'Links', 'pfadi-rankweil' ),
				'none'  => __( 'Keine', 'pfadi-rankweil' ),
			),
		)
	);
	$add(
		'pfadi_blog_layout',
		'pfadi_layout',
		__( 'Darstellung Beitragslisten', 'pfadi-rankweil' ),
		'select',
		'grid',
		array(
			'choices' => array(
				'grid' => __( 'Kacheln', 'pfadi-rankweil' ),
				'list' => __( 'Liste', 'pfadi-rankweil' ),
			),
		)
	);
	$add( 'pfadi_classic_widgets', 'pfadi_layout', __( 'Klassische Widget-Verwaltung (wie bei HitMag)', 'pfadi-rankweil' ), 'checkbox', true );
	$add( 'pfadi_excerpt_length', 'pfadi_layout', __( 'Auszugslänge (Wörter)', 'pfadi-rankweil' ), 'number', 22, array( 'input_attrs' => array( 'min' => 5, 'max' => 80 ) ) );
	$add(
		'pfadi_single_header',
		'pfadi_layout',
		__( 'Kopf von Beiträgen', 'pfadi-rankweil' ),
		'radio',
		'classic',
		array(
			'choices' => array(
				'classic' => __( 'Klassisch: Titel und Bild in der Textspalte (wie HitMag)', 'pfadi-rankweil' ),
				'hero'    => __( 'Groß: Bild über die ganze Breite, Titel darauf', 'pfadi-rankweil' ),
			),
		)
	);
	$add( 'pfadi_reading_time', 'pfadi_layout', __( 'Lesezeit anzeigen', 'pfadi-rankweil' ), 'checkbox', true );
	$add( 'pfadi_related', 'pfadi_layout', __( 'Ähnliche Beiträge unter Artikeln', 'pfadi-rankweil' ), 'checkbox', true );
	$add( 'pfadi_share', 'pfadi_layout', __( 'Teilen-Buttons (ohne Tracking)', 'pfadi-rankweil' ), 'checkbox', true );

	/* ---------- Kopfbereich ---------- */
	$section( 'pfadi_header', __( 'Kopfbereich', 'pfadi-rankweil' ), 20 );
	$add( 'pfadi_logo_height', 'pfadi_header', __( 'Logo-Höhe in Pixel (Desktop; beim Scrollen und am Handy kleiner)', 'pfadi-rankweil' ), 'number', 84, array( 'input_attrs' => array( 'min' => 32, 'max' => 140, 'step' => 2 ) ) );
	$add( 'pfadi_logo_dark', 'pfadi_header', __( 'Logo für Dark Mode (optional, helle Variante)', 'pfadi-rankweil' ), 'image', '' );
	$add( 'pfadi_symbol', 'pfadi_header', __( 'Pfadi-Symbol (z. B. offizielle PPÖ-Lilie, PNG/SVG mit transparentem Hintergrund)', 'pfadi-rankweil' ), 'image', '' );
	$add( 'pfadi_symbol_light', 'pfadi_header', __( 'Pfadi-Symbol hell/weiß (für dunkle Flächen wie Footer)', 'pfadi-rankweil' ), 'image', '' );
	$add( 'pfadi_sticky_header', 'pfadi_header', __( 'Kopfzeile beim Scrollen fixieren', 'pfadi-rankweil' ), 'checkbox', true );
	$add( 'pfadi_topbar', 'pfadi_header', __( 'Obere Infoleiste anzeigen', 'pfadi-rankweil' ), 'checkbox', true );
	$add( 'pfadi_topbar_text', 'pfadi_header', __( 'Text in der Infoleiste', 'pfadi-rankweil' ), 'text', 'Allzeit bereit! – Pfadfinder*innen in Rankweil' );
	$add( 'pfadi_header_cta_text', 'pfadi_header', __( 'Button-Text im Menü', 'pfadi-rankweil' ), 'text', 'Mitmachen' );
	$add( 'pfadi_header_cta_url', 'pfadi_header', __( 'Button-Link', 'pfadi-rankweil' ), 'url', '/anmeldung/' );
	$add( 'pfadi_header_search', 'pfadi_header', __( 'Suche im Menü anzeigen', 'pfadi-rankweil' ), 'checkbox', true );

	/* ---------- Startseite: Hero ---------- */
	$section( 'pfadi_hero', __( 'Startseite: Aufmacher', 'pfadi-rankweil' ), 30 );
	$add( 'pfadi_hero_enable', 'pfadi_hero', __( 'Aufmacher anzeigen', 'pfadi-rankweil' ), 'checkbox', true );
	$add(
		'pfadi_hero_style',
		'pfadi_hero',
		__( 'Stil', 'pfadi-rankweil' ),
		'radio',
		'bento',
		array(
			'choices' => array(
				'bento'   => __( 'Neueste Beiträge als Kachel-Raster', 'pfadi-rankweil' ),
				'welcome' => __( 'Willkommens-Banner mit Bild & Buttons', 'pfadi-rankweil' ),
			),
		)
	);
	$add( 'pfadi_front_content', 'pfadi_hero', __( 'Text der Startseite (Seite unter Einstellungen → Lesen) unter den Stufen anzeigen', 'pfadi-rankweil' ), 'checkbox', false );
	$add( 'pfadi_hero_sticky', 'pfadi_hero', __( 'Fixierte („oben gehaltene“) Beiträge zuerst zeigen', 'pfadi-rankweil' ), 'checkbox', false );
	$add( 'pfadi_hero_cat', 'pfadi_hero', __( 'Kategorie für Kachel-Raster', 'pfadi-rankweil' ), 'select', 0, array( 'choices' => $cats ) );
	$add( 'pfadi_hero_title', 'pfadi_hero', __( 'Banner: Überschrift', 'pfadi-rankweil' ), 'text', 'Abenteuer. Freundschaft. Draußen sein.' );
	$add( 'pfadi_hero_text', 'pfadi_hero', __( 'Banner: Text', 'pfadi-rankweil' ), 'textarea', 'Bei den Pfadfinder*innen Rankweil erleben Kinder und Jugendliche von 8 bis 20 Jahren jede Woche echte Gemeinschaft.' );
	$add( 'pfadi_hero_image', 'pfadi_hero', __( 'Banner: Hintergrundbild', 'pfadi-rankweil' ), 'image', '' );
	$add( 'pfadi_hero_btn1_text', 'pfadi_hero', __( 'Banner: Button 1 Text', 'pfadi-rankweil' ), 'text', 'Jetzt anmelden' );
	$add( 'pfadi_hero_btn1_url', 'pfadi_hero', __( 'Banner: Button 1 Link', 'pfadi-rankweil' ), 'url', '/anmeldung/' );
	$add( 'pfadi_hero_btn2_text', 'pfadi_hero', __( 'Banner: Button 2 Text', 'pfadi-rankweil' ), 'text', 'Unsere Stufen' );
	$add( 'pfadi_hero_btn2_url', 'pfadi_hero', __( 'Banner: Button 2 Link', 'pfadi-rankweil' ), 'url', '#stufen' );

	/* ---------- Startseite: Stufen ---------- */
	$section( 'pfadi_stufen', __( 'Startseite: Stufen', 'pfadi-rankweil' ), 40 );
	$add( 'pfadi_stufen_enable', 'pfadi_stufen', __( 'Stufen-Bereich anzeigen', 'pfadi-rankweil' ), 'checkbox', true );
	$add( 'pfadi_stufen_title', 'pfadi_stufen', __( 'Überschrift', 'pfadi-rankweil' ), 'text', 'Für jedes Alter das passende Abenteuer' );
	$add( 'pfadi_stufen_intro', 'pfadi_stufen', __( 'Einleitung', 'pfadi-rankweil' ), 'textarea', 'Vier Altersstufen, ein gemeinsames Ziel: Kinder und Jugendliche stark machen.' );
	foreach ( pfadi_stufen_defaults() as $i => $d ) {
		/* translators: %d: Nummer der Stufe */
		$p = sprintf( __( 'Stufe %d', 'pfadi-rankweil' ), $i ) . ': ';
		$add( "pfadi_stufe{$i}_name", 'pfadi_stufen', $p . __( 'Name', 'pfadi-rankweil' ), 'text', $d['name'] );
		$add( "pfadi_stufe{$i}_short", 'pfadi_stufen', $p . __( 'Kürzel', 'pfadi-rankweil' ), 'text', $d['short'] );
		$add( "pfadi_stufe{$i}_age", 'pfadi_stufen', $p . __( 'Alter', 'pfadi-rankweil' ), 'text', $d['age'] );
		$add( "pfadi_stufe{$i}_text", 'pfadi_stufen', $p . __( 'Text', 'pfadi-rankweil' ), 'textarea', $d['text'] );
		$add( "pfadi_stufe{$i}_color", 'pfadi_stufen', $p . __( 'Farbe', 'pfadi-rankweil' ), 'color', $d['color'] );
		$add( "pfadi_stufe{$i}_image", 'pfadi_stufen', $p . __( 'Bild (optional)', 'pfadi-rankweil' ), 'image', '' );
		$add( "pfadi_stufe{$i}_url", 'pfadi_stufen', $p . __( 'Link', 'pfadi-rankweil' ), 'url', $d['url'] );
		$add( "pfadi_stufe{$i}_cats", 'pfadi_stufen', $p . __( 'Kategorien (Slugs, mit Komma) – bekommen die Stufenfarbe', 'pfadi-rankweil' ), 'text', $d['cats'] );
	}

	/* ---------- Startseite: Berichte ---------- */
	$section( 'pfadi_news', __( 'Startseite: Berichte', 'pfadi-rankweil' ), 50 );
	$add( 'pfadi_news_title', 'pfadi_news', __( 'Überschrift', 'pfadi-rankweil' ), 'text', 'Letzte Berichte' );
	$add( 'pfadi_news_cat', 'pfadi_news', __( 'Kategorie', 'pfadi-rankweil' ), 'select', 0, array( 'choices' => $cats ) );
	$add( 'pfadi_news_count', 'pfadi_news', __( 'Anzahl Beiträge', 'pfadi-rankweil' ), 'number', 6, array( 'input_attrs' => array( 'min' => 1, 'max' => 12 ) ) );
	$add( 'pfadi_news_skip_hero', 'pfadi_news', __( 'Beiträge aus dem Aufmacher nicht wiederholen', 'pfadi-rankweil' ), 'checkbox', true );
	$add(
		'pfadi_front_sidebar',
		'pfadi_news',
		__( 'Widgets der Seitenleiste auf der Startseite (z. B. „Neues aus der Gruppe“, „Wichtiges“)', 'pfadi-rankweil' ),
		'select',
		'after_stufen',
		array(
			'choices' => array(
				'after_hero'   => __( 'Unter dem Aufmacher', 'pfadi-rankweil' ),
				'after_stufen' => __( 'Unter den Stufen (Standard)', 'pfadi-rankweil' ),
				'after_news'   => __( 'Unter den Berichten', 'pfadi-rankweil' ),
				'off'          => __( 'Nicht anzeigen', 'pfadi-rankweil' ),
			),
		)
	);
	$add( 'pfadi_exclude_cats', 'pfadi_news', __( 'Kategorien auf der Startseite ausblenden (Slugs, mit Komma)', 'pfadi-rankweil' ), 'text', 'wichtiges' );
	$add( 'pfadi_news_more_text', 'pfadi_news', __( 'Button „Alle Berichte“', 'pfadi-rankweil' ), 'text', 'Alle Berichte' );

	/* ---------- Startseite: Mitmach-Banner ---------- */
	$section( 'pfadi_cta', __( 'Startseite: Mitmach-Banner', 'pfadi-rankweil' ), 60 );
	$add( 'pfadi_cta_enable', 'pfadi_cta', __( 'Banner anzeigen', 'pfadi-rankweil' ), 'checkbox', true );
	$add( 'pfadi_cta_title', 'pfadi_cta', __( 'Überschrift', 'pfadi-rankweil' ), 'text', 'Lust auf Abenteuer?' );
	$add( 'pfadi_cta_text', 'pfadi_cta', __( 'Text', 'pfadi-rankweil' ), 'textarea', 'Komm vorbei und schnuppere in eine Heimstunde – wir freuen uns auf dich!' );
	$add( 'pfadi_cta_btn_text', 'pfadi_cta', __( 'Button-Text', 'pfadi-rankweil' ), 'text', 'Zur Anmeldung' );
	$add( 'pfadi_cta_btn_url', 'pfadi_cta', __( 'Button-Link', 'pfadi-rankweil' ), 'url', '/anmeldung/' );

	/* ---------- Social Media ---------- */
	$section( 'pfadi_social', __( 'Social Media', 'pfadi-rankweil' ), 70 );
	foreach ( pfadi_social_networks() as $key => $label ) {
		$add( "pfadi_social_{$key}", 'pfadi_social', $label, 'url', '' );
	}

	/* ---------- Footer ---------- */
	$section( 'pfadi_footer', __( 'Footer', 'pfadi-rankweil' ), 80 );
	$add( 'pfadi_footer_about', 'pfadi_footer', __( 'Kurzbeschreibung neben dem Logo', 'pfadi-rankweil' ), 'textarea', 'Wir sind Teil der Pfadfinder und Pfadfinderinnen Österreichs (PPÖ) – offen für alle Kinder und Jugendlichen.' );
	$add( 'pfadi_footer_logo', 'pfadi_footer', __( 'Logo rechts unten (z. B. weißes PPÖ-Gruppenlogo)', 'pfadi-rankweil' ), 'image', '' );
	$add( 'pfadi_footer_logo_url', 'pfadi_footer', __( 'Link des Logos (leer = kein Link)', 'pfadi-rankweil' ), 'url', 'https://ppoe.at/' );
	$add( 'pfadi_footer_logo_width', 'pfadi_footer', __( 'Breite des Logos in Pixel', 'pfadi-rankweil' ), 'number', 240, array( 'input_attrs' => array( 'min' => 60, 'max' => 480, 'step' => 10 ) ) );
	$add( 'pfadi_footer_copyright', 'pfadi_footer', __( 'Copyright-Text ({jahr} = aktuelles Jahr)', 'pfadi-rankweil' ), 'text', '© {jahr} Pfadfindergruppe Rankweil' );
}
add_action( 'customize_register', 'pfadi_customize_register' );

/**
 * Unterstützte Netzwerke.
 */
function pfadi_social_networks() {
	return array(
		'facebook'  => 'Facebook',
		'instagram' => 'Instagram',
		'youtube'   => 'YouTube',
		'tiktok'    => 'TikTok',
		'whatsapp'  => 'WhatsApp-Kanal',
	);
}

/**
 * Hex → „r, g, b“.
 */
function pfadi_hex_rgb( $hex ) {
	$hex = ltrim( (string) $hex, '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	if ( 6 !== strlen( $hex ) ) {
		return '0, 0, 0';
	}
	return hexdec( substr( $hex, 0, 2 ) ) . ', ' . hexdec( substr( $hex, 2, 2 ) ) . ', ' . hexdec( substr( $hex, 4, 2 ) );
}

/**
 * Dynamisches CSS aus den Customizer-Werten.
 */
function pfadi_customizer_css() {
	$primary = pfadi_mod( 'pfadi_color_primary', '#05368B' );
	$accent  = pfadi_mod( 'pfadi_color_accent', '#D3031C' );
	$bg      = pfadi_mod( 'pfadi_color_bg', '#F5F1EA' );
	$radius  = array(
		'square' => '0px',
		'soft'   => '8px',
		'round'  => '20px',
	);
	$r       = $radius[ pfadi_mod( 'pfadi_radius', 'round' ) ] ?? '20px';

	$css = ':root{'
		. '--c-primary:' . $primary . ';'
		. '--c-primary-rgb:' . pfadi_hex_rgb( $primary ) . ';'
		. '--c-accent:' . $accent . ';'
		. '--c-accent-rgb:' . pfadi_hex_rgb( $accent ) . ';'
		. '--c-bg:' . $bg . ';'
		. '--radius:' . $r . ';'
		. '--logo-h:' . max( 32, min( 140, (int) pfadi_mod( 'pfadi_logo_height', 84 ) ) ) . 'px;'
		. '}';

	foreach ( pfadi_stufen_defaults() as $i => $d ) {
		$c    = pfadi_mod( "pfadi_stufe{$i}_color", $d['color'] );
		$css .= '.stufe-' . $i . ',.page-stufe-' . $i . '{--stufe:' . $c . ';--stufe-rgb:' . pfadi_hex_rgb( $c ) . ';}';
	}
	return $css;
}
