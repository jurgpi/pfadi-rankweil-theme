<?php
/**
 * Einmalige Übernahme von Einstellungen aus dem vorherigen Theme (HitMag Pro).
 *
 * WordPress speichert Logo, Menü-Positionen usw. pro Theme. Beim Wechsel wären sie
 * im neuen Theme leer. Diese Datei holt sie einmalig herüber:
 * - Logo (custom_logo)
 * - Hauptmenü + Footer-Menü (Menü-Positionen)
 * - Social-Media-Links (aus Theme-Einstellungen oder Social-Menü)
 *
 * Läuft beim Aktivieren und – falls das Theme schon aktiv war – einmal im Admin.
 * Bereits gesetzte Werte im neuen Theme werden nie überschrieben.
 *
 * @package pfadi-rankweil
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme-Einstellungen des alten Themes finden.
 *
 * @param string $preferred Stylesheet-Slug des zuletzt aktiven Themes (falls bekannt).
 * @return array
 */
function pfadi_find_previous_mods( $preferred = '' ) {
	$candidates = array_filter( array( $preferred, 'hitmag-pro', 'hitmag-pro-child', 'hitmag', 'hitmag-child' ) );
	foreach ( array_unique( $candidates ) as $slug ) {
		if ( get_stylesheet() === $slug ) {
			continue;
		}
		$mods = get_option( 'theme_mods_' . $slug );
		if ( is_array( $mods ) && $mods ) {
			return $mods;
		}
	}
	return array();
}

/**
 * Übernahme durchführen.
 *
 * @param string $preferred Slug des vorherigen Themes.
 */
function pfadi_import_previous_theme( $preferred = '' ) {
	if ( get_theme_mod( 'pfadi_imported' ) ) {
		return;
	}
	$mods = pfadi_find_previous_mods( $preferred );
	set_theme_mod( 'pfadi_imported', time() );
	if ( ! $mods ) {
		return;
	}
	$done = array();

	// Logo.
	if ( ! get_theme_mod( 'custom_logo' ) && ! empty( $mods['custom_logo'] ) ) {
		set_theme_mod( 'custom_logo', (int) $mods['custom_logo'] );
		$done[] = __( 'Logo', 'pfadi-rankweil' );
	}

	// Menüs.
	$old_locs = isset( $mods['nav_menu_locations'] ) && is_array( $mods['nav_menu_locations'] ) ? array_filter( $mods['nav_menu_locations'] ) : array();
	$new_locs = (array) get_theme_mod( 'nav_menu_locations', array() );
	if ( $old_locs ) {
		$social_like = array();
		foreach ( $old_locs as $loc => $menu_id ) {
			if ( false !== strpos( $loc, 'social' ) ) {
				$social_like[] = $menu_id;
			}
		}
		if ( empty( $new_locs['menu-1'] ) ) {
			foreach ( array( 'menu-1', 'primary', 'main', 'main-menu', 'header' ) as $key ) {
				if ( ! empty( $old_locs[ $key ] ) ) {
					$new_locs['menu-1'] = $old_locs[ $key ];
					break;
				}
			}
			if ( empty( $new_locs['menu-1'] ) ) {
				foreach ( $old_locs as $loc => $menu_id ) {
					if ( ! in_array( $menu_id, $social_like, true ) && false === strpos( $loc, 'footer' ) && false === strpos( $loc, 'top' ) ) {
						$new_locs['menu-1'] = $menu_id;
						break;
					}
				}
			}
			if ( ! empty( $new_locs['menu-1'] ) ) {
				$done[] = __( 'Hauptmenü', 'pfadi-rankweil' );
			}
		}
		if ( empty( $new_locs['footer-menu'] ) ) {
			foreach ( $old_locs as $loc => $menu_id ) {
				if ( false !== strpos( $loc, 'footer' ) && ! in_array( $menu_id, $social_like, true ) ) {
					$new_locs['footer-menu'] = $menu_id;
					$done[]             = __( 'Footer-Menü', 'pfadi-rankweil' );
					break;
				}
			}
		}
		if ( empty( $new_locs['menu-social'] ) && ! empty( $social_like ) ) {
			$new_locs['menu-social'] = reset( $social_like );
			$done[]             = __( 'Social-Media-Menü', 'pfadi-rankweil' );
		}
		set_theme_mod( 'nav_menu_locations', $new_locs );
	}

	// Social-Links: alle URL-Werte der alten Einstellungen + Links aus Menüs durchsuchen.
	$urls = array();
	array_walk_recursive(
		$mods,
		function ( $v ) use ( &$urls ) {
			if ( is_string( $v ) && preg_match( '#^https?://#i', $v ) ) {
				$urls[] = $v;
			}
		}
	);
	foreach ( $old_locs as $menu_id ) {
		$items = wp_get_nav_menu_items( $menu_id );
		if ( $items ) {
			foreach ( $items as $item ) {
				$urls[] = $item->url;
			}
		}
	}
	$patterns = array(
		'facebook'  => '#facebook\.com|fb\.com#i',
		'instagram' => '#instagram\.com#i',
		'youtube'   => '#youtube\.com|youtu\.be#i',
		'tiktok'    => '#tiktok\.com#i',
		'whatsapp'  => '#whatsapp\.com|wa\.me#i',
	);
	foreach ( $patterns as $key => $re ) {
		if ( get_theme_mod( "pfadi_social_{$key}" ) ) {
			continue;
		}
		foreach ( $urls as $u ) {
			if ( preg_match( $re, $u ) && ! preg_match( '#sharer|share\?#i', $u ) ) {
				set_theme_mod( "pfadi_social_{$key}", esc_url_raw( $u ) );
				$done[] = ucfirst( $key );
				break;
			}
		}
	}

	if ( $done ) {
		set_transient( 'pfadi_import_notice', $done, DAY_IN_SECONDS );
	}
}

/**
 * Sicherung der Anordnung des vorherigen Themes (Widgets + Menü-Positionen).
 *
 * WordPress legt beim Wechsel eine Kopie der Widget-Anordnung des alten Themes in dessen
 * Theme-Einstellungen ab. Wir merken uns diese Kopie zusätzlich, weil WordPress beim
 * Zurückwechseln Widgets und Menüs neu „zuordnet“ – dabei landen z. B. Social-Menüs in
 * falschen Positionen und Theme-eigene Widgets (HitMag „Sidebar Posts“) fallen heraus.
 *
 * @param string $stylesheet Slug des vorherigen Themes.
 */
function pfadi_snapshot_previous_theme( $stylesheet ) {
	if ( ! $stylesheet || get_stylesheet() === $stylesheet ) {
		return;
	}
	$mods = get_option( 'theme_mods_' . $stylesheet );
	if ( ! is_array( $mods ) ) {
		return;
	}
	$widgets = isset( $mods['sidebars_widgets']['data'] ) && is_array( $mods['sidebars_widgets']['data'] ) ? $mods['sidebars_widgets']['data'] : null;
	if ( ! $widgets ) {
		return; // Ohne Widget-Kopie nichts sichern (z. B. Theme war nie aktiv).
	}
	update_option(
		'pfadi_prev_theme_snapshot',
		array(
			'theme'              => $stylesheet,
			'time'               => time(),
			'sidebars_widgets'   => $widgets,
			'nav_menu_locations' => isset( $mods['nav_menu_locations'] ) && is_array( $mods['nav_menu_locations'] ) ? $mods['nav_menu_locations'] : array(),
		),
		false
	);
}

/**
 * Beim Zurückwechseln auf das gesicherte Theme: Anordnung exakt wiederherstellen.
 * Läuft noch im alten Request, in dem dieses Theme geladen ist (Hook „switch_theme“).
 *
 * @param string   $new_name  Name des neuen Themes.
 * @param WP_Theme $new_theme Neues Theme.
 */
function pfadi_restore_previous_theme( $new_name, $new_theme = null ) {
	// Richtung „weg vom Pfadi-Theme“: eigene Anordnung sichern, damit sie beim nächsten
	// Aktivieren wiederkommt (siehe pfadi_restore_own_layout).
	$own_widgets = get_option( 'sidebars_widgets' );
	if ( is_array( $own_widgets ) ) {
		update_option(
			'pfadi_own_snapshot',
			array(
				'time'               => time(),
				'sidebars_widgets'   => $own_widgets,
				'nav_menu_locations' => (array) get_theme_mod( 'nav_menu_locations', array() ),
			),
			false
		);
	}

	$snap = get_option( 'pfadi_prev_theme_snapshot' );
	if ( ! is_array( $snap ) || ! $new_theme instanceof WP_Theme || $new_theme->get_stylesheet() !== $snap['theme'] ) {
		return;
	}
	$slug = $snap['theme'];

	// 1) Menü-Positionen: gesicherte Zuordnung setzen und WordPress' automatisches
	//    Zuordnen (aus den Positionen dieses Themes) verhindern.
	$mods = get_option( 'theme_mods_' . $slug );
	$mods = is_array( $mods ) ? $mods : array();
	$mods['nav_menu_locations'] = $snap['nav_menu_locations'];

	// 2) Widgets: gesicherte Anordnung als aktuelle Anordnung und als Kopie des Themes.
	$mods['sidebars_widgets'] = array(
		'time' => time(),
		'data' => $snap['sidebars_widgets'],
	);
	update_option( 'theme_mods_' . $slug, $mods );
	delete_option( 'theme_switch_menu_locations' );

	$restore = $snap['sidebars_widgets'];
	if ( ! isset( $restore['array_version'] ) ) {
		$restore['array_version'] = 3;
	}
	update_option( 'sidebars_widgets', $restore );

	delete_option( 'pfadi_prev_theme_snapshot' );
}
add_action( 'switch_theme', 'pfadi_restore_previous_theme', 10, 2 );

/**
 * Beim Aktivieren.
 *
 * @param string   $old_name  Name des alten Themes.
 * @param WP_Theme $old_theme Altes Theme.
 */
function pfadi_on_switch_theme( $old_name, $old_theme = null ) {
	$old = $old_theme instanceof WP_Theme ? $old_theme->get_stylesheet() : (string) get_option( 'theme_switched' );
	pfadi_snapshot_previous_theme( $old );
	pfadi_import_previous_theme( $old );
}
add_action( 'after_switch_theme', 'pfadi_on_switch_theme', 5, 2 );

/**
 * Richtung „zurück zum Pfadi-Theme“: eigene Anordnung (Widgets + Menü-Positionen) vom
 * letzten Mal wiederherstellen. Läuft NACH WordPress' eigenem Umsortieren
 * (_wp_sidebars_changed / _wp_menus_changed, Priorität 10) und überschreibt es.
 */
function pfadi_restore_own_layout() {
	$own = get_option( 'pfadi_own_snapshot' );
	if ( ! is_array( $own ) || empty( $own['sidebars_widgets'] ) ) {
		return; // Erstes Aktivieren: WordPress-Standard + Übernahme aus HitMag.
	}
	$widgets = $own['sidebars_widgets'];
	if ( ! isset( $widgets['array_version'] ) ) {
		$widgets['array_version'] = 3;
	}
	update_option( 'sidebars_widgets', $widgets );
	set_theme_mod( 'nav_menu_locations', $own['nav_menu_locations'] );
	remove_theme_mod( 'sidebars_widgets' );
	delete_option( 'pfadi_own_snapshot' );
	set_transient( 'pfadi_import_notice', array( __( 'Widget- und Menü-Anordnung vom letzten Mal', 'pfadi-rankweil' ) ), DAY_IN_SECONDS );
}
add_action( 'after_switch_theme', 'pfadi_restore_own_layout', 99 );

/**
 * Falls das Theme schon aktiv war (Update statt Neuaktivierung): einmal im Admin nachholen.
 */
add_action(
	'admin_init',
	function () {
		if ( current_user_can( 'edit_theme_options' ) && ! get_theme_mod( 'pfadi_imported' ) ) {
			pfadi_import_previous_theme( (string) get_option( 'theme_switched' ) );
		}
	}
);

/**
 * Hinweis im Admin, was übernommen wurde.
 */
add_action(
	'admin_notices',
	function () {
		$done = get_transient( 'pfadi_import_notice' );
		if ( ! $done || ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}
		delete_transient( 'pfadi_import_notice' );
		printf(
			'<div class="notice notice-success is-dismissible"><p><strong>%1$s</strong> %2$s</p><p>%3$s</p></div>',
			esc_html__( 'Pfadi Rankweil Theme:', 'pfadi-rankweil' ),
			/* translators: %s: Liste */
			esc_html( sprintf( __( 'Aus dem vorherigen Theme übernommen: %s.', 'pfadi-rankweil' ), implode( ', ', $done ) ) ),
			esc_html__( 'Bitte unter Design → Widgets die Seitenleiste und den Footer prüfen – HitMag-eigene Widgets müssen ersetzt werden.', 'pfadi-rankweil' )
		);
	}
);
