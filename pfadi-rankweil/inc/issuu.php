<?php
/**
 * Mampfla-Archiv aus Issuu: Shortcode [mampfla_archiv].
 *
 * - Liste der Ausgaben wird automatisch von der Issuu-Profilseite geholt (zweimal täglich
 *   im Hintergrund per WP-Cron). Schlägt ein Abruf fehl, bleibt die letzte Liste erhalten.
 * - Neueste Ausgabe als Viewer, die letzten Ausgaben mit Titelbild, Archiv nach Jahren.
 * - Klick im Archiv öffnet die Ausgabe im Viewer auf der eigenen Seite (Fallback: Issuu).
 *
 * Shortcode-Attribute (alle optional):
 *   user="pfadi-rankweil"   Issuu-Konto
 *   filter="mampf"          nur Dokumente, deren Kürzel diesen Text enthält
 *   ausschluss=""           Kürzel mit diesen Texten weglassen (Komma-getrennt)
 *   extra=""                zusätzliche Kürzel, die immer dabei sind (Komma-getrennt)
 *   neueste="8"             Anzahl Ausgaben mit Titelbild
 *   viewer="ja"             Viewer für die aktuelle Ausgabe anzeigen
 *
 * @package pfadi-rankweil
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Monatsnamen (Index 1–12). */
function pfadi_issuu_months() {
	return array( 1 => 'Jänner', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember' );
}

/**
 * Dokument-Kürzel von der Issuu-Profilseite holen (alle Seiten).
 *
 * @param string $user Issuu-Konto.
 * @return string[]|WP_Error
 */
function pfadi_issuu_fetch_list( $user ) {
	$docs = array();
	for ( $page = 1; $page <= 30; $page++ ) {
		$url = 1 === $page
			? 'https://issuu.com/' . rawurlencode( $user ) . '?ps=24'
			: 'https://issuu.com/' . rawurlencode( $user ) . '/' . $page . '?ps=24';
		$res = wp_remote_get(
			$url,
			array(
				'timeout'    => 20,
				'user-agent' => 'Mozilla/5.0 (compatible; WordPress; ' . home_url( '/' ) . ')',
			)
		);
		if ( is_wp_error( $res ) ) {
			return 1 === $page ? $res : $docs;
		}
		if ( 200 !== (int) wp_remote_retrieve_response_code( $res ) ) {
			break;
		}
		preg_match_all( '#/' . preg_quote( $user, '#' ) . '/docs/([A-Za-z0-9_\-]+)#', wp_remote_retrieve_body( $res ), $m );
		$found = array_values( array_unique( $m[1] ) );
		if ( ! $found ) {
			break;
		}
		$new = array_diff( $found, $docs );
		if ( ! $new ) {
			break; // Seite wiederholt sich → Ende.
		}
		$docs = array_merge( $docs, array_values( $new ) );
		if ( count( $found ) < 24 ) {
			break;
		}
	}
	return $docs ? $docs : new WP_Error( 'pfadi_issuu_empty', 'Keine Dokumente gefunden.' );
}

/**
 * Gespeicherte Liste (Option = dauerhafter Rückfall) + Aktualisierung im Hintergrund.
 *
 * @param string $user Issuu-Konto.
 * @return string[]
 */
function pfadi_issuu_get_list( $user ) {
	$key    = 'pfadi_issuu_docs_' . md5( $user );
	$stored = get_option( $key );
	if ( ! is_array( $stored ) || empty( $stored['docs'] ) ) {
		// Allererster Aufruf: sofort holen (einmalig etwas langsamer).
		$docs = pfadi_issuu_fetch_list( $user );
		if ( is_wp_error( $docs ) ) {
			return array();
		}
		update_option( $key, array( 'docs' => $docs, 'time' => time() ), false );
		return $docs;
	}
	if ( time() - (int) $stored['time'] > 12 * HOUR_IN_SECONDS && ! wp_next_scheduled( 'pfadi_issuu_refresh', array( $user ) ) ) {
		wp_schedule_single_event( time() + 10, 'pfadi_issuu_refresh', array( $user ) );
	}
	return $stored['docs'];
}

add_action(
	'pfadi_issuu_refresh',
	function ( $user ) {
		$docs = pfadi_issuu_fetch_list( $user );
		$key  = 'pfadi_issuu_docs_' . md5( $user );
		if ( ! is_wp_error( $docs ) && count( $docs ) > 0 ) {
			update_option( $key, array( 'docs' => $docs, 'time' => time() ), false );
		} else {
			// Fehler: alte Liste behalten, in 1 Stunde erneut versuchen.
			$old = get_option( $key );
			if ( is_array( $old ) ) {
				$old['time'] = time() - 11 * HOUR_IN_SECONDS;
				update_option( $key, $old, false );
			}
		}
	}
);

/**
 * Jahr/Monat/Thema aus dem Kürzel lesen, z. B. „mampfla_202607_segeln“ → 2026, 7, „Segeln“.
 *
 * @param string $slug Kürzel.
 * @return array { year:int, month:int, topic:string }
 */
function pfadi_issuu_parse( $slug ) {
	$s     = strtolower( $slug );
	$year  = 0;
	$month = 0;
	if ( preg_match( '/(?<![0-9])(19[89][0-9]|20[0-9]{2})[_\-]?(0[1-9]|1[0-2])(?![0-9])/', $s, $m ) ) {
		$year  = (int) $m[1];
		$month = (int) $m[2];
	} elseif ( preg_match( '/(?<![0-9])(0[1-9]|1[0-2])[_\-](19[89][0-9]|20[0-9]{2})(?![0-9])/', $s, $m ) ) {
		// Monat vor dem Jahr, z. B. „mampfla_03_2011“.
		$year  = (int) $m[2];
		$month = (int) $m[1];
	} elseif ( preg_match( '/(?<![0-9])(19[89][0-9]|20[0-9]{2})(?![0-9])/', $s, $m ) ) {
		$year = (int) $m[1];
	}
	$names = array(
		1  => 'j(ä|_|a)?nner|januar|jan',
		2  => 'februar|feb',
		3  => 'm(ä|_|ae)?rz|mrz',
		4  => 'april|apr',
		5  => 'mai',
		6  => 'juni',
		7  => 'juli|jul',
		8  => 'august|aug',
		9  => 'september|sept',
		10 => 'oktober|okt|october',
		11 => 'november|nov',
		12 => 'dezember|dez|december',
	);
	if ( ! $month ) {
		foreach ( $names as $num => $re ) {
			if ( preg_match( '/(^|[_\-])(' . $re . ')([_\-]|$)/', $s ) ) {
				$month = $num;
				break;
			}
		}
	}
	// Thema: übrige Wörter ohne Technik-Zusätze.
	// Zahlen: einstellig, mit führender 0 oder ab 4 Stellen (Jahr/Datum) weglassen – „40“, „100“ bleiben.
	// Von Issuu verstümmelte Umlaute zusammenführen („j_nner“, „m_rz“).
	$s     = str_replace( array( 'j_nner', 'm_rz' ), array( 'jaenner', 'maerz' ), $s );
	$skip  = '/^(d?mampf?a?l?a?\d*|mampfal|jaenner|maerz|web|webausgabe|teil\d*|\d|0\d+|\d{4,}|compressed|kl|klein|online|ohne|tel|neu|gesamt|mobil|fertig|rz|ansicht|g|f|lose|kontroll|titelbild|komprimiert|[0-9a-f]{10,}|' . implode( '|', $names ) . ')$/u';
	$words = array_values(
		array_filter(
			preg_split( '/[_\-]+/', $s ),
			function ( $w ) use ( $skip, $year, $month ) {
				if ( '' === $w || preg_match( $skip, $w ) ) {
					return false;
				}
				// Bereits erkanntes Jahr/Monat nicht noch einmal als Thema zeigen.
				if ( ctype_digit( $w ) && ( (int) $w === $year || ( $month && (int) $w === $month ) ) ) {
					return false;
				}
				return true;
			}
		)
	);
	$small = array( 'und', 'der', 'die', 'das', 'im', 'in', 'am', 'an', 'zum', 'zur', 'für', 'von', 'mit', 'auf', 'aus' );
	foreach ( $words as $i => $w ) {
		if ( 0 === $i || ! in_array( $w, $small, true ) ) {
			$words[ $i ] = mb_strtoupper( mb_substr( $w, 0, 1 ) ) . mb_substr( $w, 1 );
		}
	}
	$topic = implode( ' ', $words );
	return array(
		'year'  => $year,
		'month' => $month,
		'topic' => $topic,
	);
}

/**
 * oEmbed-Daten (Titel, Titelbild) – dauerhaft zwischengespeichert.
 *
 * @param string $user Konto.
 * @param string $slug Kürzel.
 * @return array|null { title, thumb }
 */
function pfadi_issuu_meta( $user, $slug ) {
	$key  = 'pfadi_issuu_meta';
	$all  = get_option( $key );
	$all  = is_array( $all ) ? $all : array();
	$id   = $user . '/' . $slug;
	if ( isset( $all[ $id ] ) ) {
		return $all[ $id ] ? $all[ $id ] : null;
	}
	$oembed = _wp_oembed_get_object();
	$data   = $oembed->get_data( 'https://issuu.com/' . $user . '/docs/' . $slug, array( 'width' => 600 ) );
	$meta   = null;
	if ( $data && ! empty( $data->thumbnail_url ) ) {
		$meta = array(
			'title' => isset( $data->title ) ? sanitize_text_field( $data->title ) : '',
			'thumb' => esc_url_raw( str_replace( '_thumb_medium', '_thumb_large', $data->thumbnail_url ) ),
		);
	}
	$all[ $id ] = $meta ? $meta : false;
	update_option( $key, $all, false );
	return $meta;
}

/**
 * Viewer-Adresse für eine Ausgabe.
 */
function pfadi_issuu_embed_url( $user, $slug ) {
	return 'https://e.issuu.com/embed.html?u=' . rawurlencode( $user ) . '&d=' . rawurlencode( $slug );
}

/**
 * Shortcode-Ausgabe.
 */
function pfadi_mampfla_archiv_shortcode( $atts ) {
	$a = shortcode_atts(
		array(
			'user'       => 'pfadi-rankweil',
			'filter'     => 'mampf',
			'ausschluss' => '',
			'extra'      => '',
			'neueste'    => 8,
			'viewer'     => 'ja',
		),
		$atts,
		'mampfla_archiv'
	);
	$user = preg_replace( '/[^A-Za-z0-9_\-]/', '', $a['user'] );
	$docs = pfadi_issuu_get_list( $user );

	$filter  = strtolower( trim( $a['filter'] ) );
	$exclude = array_filter( array_map( 'trim', explode( ',', strtolower( $a['ausschluss'] ) ) ) );
	$extra   = array_filter( array_map( 'trim', explode( ',', $a['extra'] ) ) );
	$docs    = array_values(
		array_filter(
			array_unique( array_merge( $docs, $extra ) ),
			function ( $d ) use ( $filter, $exclude, $extra ) {
				$l = strtolower( $d );
				if ( in_array( $d, $extra, true ) ) {
					return true;
				}
				if ( $filter && false === strpos( $l, $filter ) ) {
					return false;
				}
				foreach ( $exclude as $x ) {
					if ( false !== strpos( $l, $x ) ) {
						return false;
					}
				}
				return (bool) preg_match( '/^[A-Za-z0-9_\-]+$/', $d );
			}
		)
	);
	if ( ! $docs ) {
		return '<p class="issuu-empty">' . esc_html__( 'Die Ausgaben konnten gerade nicht von Issuu geladen werden.', 'pfadi-rankweil' ) . ' <a href="https://issuu.com/' . esc_attr( $user ) . '" target="_blank" rel="noopener">' . esc_html__( 'Alle Ausgaben auf Issuu', 'pfadi-rankweil' ) . '</a></p>';
	}

	// Jahr/Monat ermitteln; fehlendes Jahr vom Vorgänger übernehmen (Liste ist neu → alt).
	$months = pfadi_issuu_months();
	$items  = array();
	$seen   = array();
	$last_y = 0;
	foreach ( $docs as $slug ) {
		$p = pfadi_issuu_parse( $slug );
		if ( ! $p['year'] ) {
			$p['year'] = $last_y;
		}
		$last_y = $p['year'] ? $p['year'] : $last_y;
		$dup    = $p['year'] && $p['month'] ? $p['year'] . '-' . $p['month'] : $slug;
		if ( isset( $seen[ $dup ] ) ) {
			continue; // gleiche Ausgabe mehrfach hochgeladen → erste (neueste) behalten
		}
		$seen[ $dup ] = true;
		$label        = $p['month'] ? $months[ $p['month'] ] . ( $p['year'] ? ' ' . $p['year'] : '' ) : ( $p['year'] ? (string) $p['year'] : $slug );
		$items[]      = array(
			'slug'  => $slug,
			'year'  => $p['year'],
			'month' => $p['month'],
			'label' => $label,
			'topic' => $p['topic'],
		);
	}

	$current = $items[0];
	$newest  = array_slice( $items, 0, max( 0, (int) $a['neueste'] ) );
	$uid     = 'issuu-' . wp_unique_id();

	ob_start();
	echo '<div class="issuu-archiv" id="' . esc_attr( $uid ) . '" data-user="' . esc_attr( $user ) . '">';

	if ( 'nein' !== strtolower( $a['viewer'] ) ) {
		$cm = pfadi_issuu_meta( $user, $current['slug'] );
		printf(
			'<section class="issuu-viewer" aria-live="polite"><h2 class="issuu-viewer__title"><span class="issuu-viewer__kicker">%1$s</span> <span class="issuu-viewer__label">%2$s</span></h2><div class="issuu-viewer__frame"><iframe src="%3$s" title="%4$s" allow="clipboard-write; fullscreen" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe></div><p class="issuu-viewer__links"><a class="issuu-viewer__open" href="%5$s" target="_blank" rel="noopener">%6$s</a></p></section>',
			esc_html__( 'Aktuelle Ausgabe', 'pfadi-rankweil' ),
			esc_html( $current['label'] . ( $current['topic'] ? ' – ' . $current['topic'] : '' ) ),
			esc_url( pfadi_issuu_embed_url( $user, $current['slug'] ) ),
			esc_attr( $cm && $cm['title'] ? $cm['title'] : 'Mampfla ' . $current['label'] ),
			esc_url( 'https://issuu.com/' . $user . '/docs/' . $current['slug'] ),
			esc_html__( 'Auf Issuu öffnen ↗', 'pfadi-rankweil' )
		);
	}

	if ( $newest ) {
		echo '<h2 class="issuu-section-title">' . esc_html__( 'Die letzten Ausgaben', 'pfadi-rankweil' ) . '</h2><div class="issuu-grid">';
		foreach ( $newest as $it ) {
			$meta = pfadi_issuu_meta( $user, $it['slug'] );
			printf(
				'<a class="issuu-card" href="%1$s" data-issuu-doc="%2$s" data-issuu-label="%3$s" target="_blank" rel="noopener"><span class="issuu-card__cover">%4$s</span><span class="issuu-card__body"><span class="issuu-card__label">%5$s</span>%6$s</span></a>',
				esc_url( 'https://issuu.com/' . $user . '/docs/' . $it['slug'] ),
				esc_attr( $it['slug'] ),
				esc_attr( $it['label'] . ( $it['topic'] ? ' – ' . $it['topic'] : '' ) ),
				$meta && $meta['thumb'] ? '<img src="' . esc_url( $meta['thumb'] ) . '" alt="" loading="lazy" decoding="async">' : '<span class="issuu-card__placeholder">Mampfla</span>',
				esc_html( $it['label'] ),
				$it['topic'] ? '<span class="issuu-card__topic">' . esc_html( $it['topic'] ) . '</span>' : ''
			);
		}
		echo '</div>';
	}

	// Archiv nach Jahrzehnt → Jahr.
	$by_decade = array();
	foreach ( $items as $it ) {
		$y                             = $it['year'] ? $it['year'] : 0;
		$dec                           = $y ? (int) ( floor( $y / 10 ) * 10 ) : 0;
		$by_decade[ $dec ][ $y ][]     = $it;
	}
	krsort( $by_decade );
	$first_year = $items[ count( $items ) - 1 ]['year'];
	echo '<h2 class="issuu-section-title">' . esc_html( sprintf( /* translators: %d: Jahr */ __( 'Archiv seit %d', 'pfadi-rankweil' ), $first_year ) ) . ' <span class="issuu-count">' . esc_html( sprintf( /* translators: %d: Anzahl */ _n( '%d Ausgabe', '%d Ausgaben', count( $items ), 'pfadi-rankweil' ), count( $items ) ) ) . '</span></h2>';
	echo '<div class="issuu-decades">';
	$open = true;
	foreach ( $by_decade as $dec => $years ) {
		krsort( $years );
		$title = $dec ? $dec . 'er' : __( 'Ohne Datum', 'pfadi-rankweil' );
		echo '<details class="issuu-decade"' . ( $open ? ' open' : '' ) . '><summary>' . esc_html( $title ) . '</summary><ul class="issuu-years">';
		$open = false;
		foreach ( $years as $y => $list ) {
			usort(
				$list,
				function ( $x, $z ) {
					return $z['month'] - $x['month'];
				}
			);
			echo '<li class="issuu-year"><span class="issuu-year__num">' . esc_html( $y ? (string) $y : '–' ) . '</span><span class="issuu-year__issues">';
			foreach ( $list as $it ) {
				$short = $it['month'] ? $months[ $it['month'] ] : ( $it['topic'] ? $it['topic'] : __( 'Ausgabe', 'pfadi-rankweil' ) );
				printf(
					'<a class="issuu-chip" href="%1$s" data-issuu-doc="%2$s" data-issuu-label="%3$s" target="_blank" rel="noopener" title="%4$s">%5$s</a>',
					esc_url( 'https://issuu.com/' . $user . '/docs/' . $it['slug'] ),
					esc_attr( $it['slug'] ),
					esc_attr( $it['label'] . ( $it['topic'] ? ' – ' . $it['topic'] : '' ) ),
					esc_attr( $it['topic'] ? $it['topic'] : $it['label'] ),
					esc_html( $short )
				);
			}
			echo '</span></li>';
		}
		echo '</ul></details>';
	}
	echo '</div>';

	echo '<p class="issuu-source">' . wp_kses_post( sprintf( /* translators: %s: Link */ __( 'Alle Ausgaben auch auf %s', 'pfadi-rankweil' ), '<a href="https://issuu.com/' . esc_attr( $user ) . '" target="_blank" rel="noopener">Issuu</a>' ) ) . '</p>';
	echo '</div>';
	return ob_get_clean();
}
add_shortcode( 'mampfla_archiv', 'pfadi_mampfla_archiv_shortcode' );
