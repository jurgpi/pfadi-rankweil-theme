<?php
/**
 * Template-Hilfsfunktionen.
 *
 * @package pfadi-rankweil
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inline-SVG-Icons (keine externen Icon-Fonts nötig).
 */
function pfadi_icon( $name ) {
	$icons = array(
		'chevron'   => '<path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>',
		'arrow'     => '<path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>',
		'search'    => '<circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2.5"/><path d="M20 20l-4-4" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>',
		'menu'      => '<path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>',
		'close'     => '<path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>',
		'clock'     => '<circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/><path d="M12 7v5l3 2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'calendar'  => '<rect x="3.5" y="5" width="17" height="15" rx="3" fill="none" stroke="currentColor" stroke-width="2"/><path d="M3.5 10h17M8 3v4M16 3v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'facebook'  => '<path fill="currentColor" d="M14 8h3V4h-3c-2.8 0-4.5 1.8-4.5 4.6V11H7v4h2.5v9h4v-9H17l.5-4h-4V8.8c0-.5.3-.8.5-.8z"/>',
		'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1.3" fill="currentColor"/>',
		'youtube'   => '<path fill="currentColor" d="M22 8.2c-.2-1.6-1.1-2.7-2.7-2.9C16.8 5 12 5 12 5s-4.8 0-7.3.3C3.1 5.5 2.2 6.6 2 8.2 1.8 9.5 1.8 12 1.8 12s0 2.5.2 3.8c.2 1.6 1.1 2.7 2.7 2.9 2.5.3 7.3.3 7.3.3s4.8 0 7.3-.3c1.6-.2 2.5-1.3 2.7-2.9.2-1.3.2-3.8.2-3.8s0-2.5-.2-3.8zM10 15.2V8.8l5.3 3.2-5.3 3.2z"/>',
		'tiktok'    => '<path fill="currentColor" d="M16.5 3c.4 2.3 1.8 3.7 4 3.9v3.3c-1.5 0-2.8-.4-4-1.2v6.3c0 3.2-2.5 5.7-5.7 5.7S5 18.5 5 15.3s2.5-5.7 5.7-5.7c.3 0 .6 0 .9.1v3.4c-.3-.1-.6-.2-.9-.2-1.3 0-2.4 1.1-2.4 2.4s1.1 2.4 2.4 2.4 2.4-1.1 2.4-2.4V3h3.4z"/>',
		'whatsapp'  => '<path fill="currentColor" d="M12 2a10 10 0 00-8.6 15.1L2 22l5-1.3A10 10 0 1012 2zm0 18.2c-1.5 0-3-.4-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1112 20.2zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1-.7-.3-1.4-.7-2-1.3-.5-.5-1-1.1-1.3-1.7-.1-.2 0-.4.1-.5l.4-.5.2-.4v-.4l-.8-1.9c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.4.1-.6.3-.6.6-.9 1.3-.9 2.1.1.9.4 1.8 1 2.6 1.1 1.6 2.5 2.9 4.2 3.6 1.5.6 2.1.7 2.9.6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2l-.4-.3z"/>',
		'sun'       => '<circle cx="12" cy="12" r="4.5" fill="none" stroke="currentColor" stroke-width="2.2"/><path d="M12 2v2.5M12 19.5V22M4.2 4.2l1.8 1.8M18 18l1.8 1.8M2 12h2.5M19.5 12H22M4.2 19.8L6 18M18 6l1.8-1.8" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>',
		'moon'      => '<path d="M20.5 14.5A8.5 8.5 0 019.5 3.5a8.5 8.5 0 1011 11z" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>',
		'mail'      => '<rect x="3" y="5" width="18" height="14" rx="3" fill="none" stroke="currentColor" stroke-width="2"/><path d="M4 7l8 6 8-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'link'      => '<path d="M10 14a4 4 0 005.7 0l3-3a4 4 0 00-5.7-5.7l-1 1M14 10a4 4 0 00-5.7 0l-3 3a4 4 0 005.7 5.7l1-1" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
	);
	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}
	return '<svg class="icon icon-' . esc_attr( $name ) . '" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">' . $icons[ $name ] . '</svg>';
}

/**
 * Offizielles Pfadi-Symbol aus dem Customizer (z. B. PPÖ-Verbandsabzeichen).
 * $variant 'light' = helle Version für dunkle Flächen, fällt auf die normale zurück.
 * Ist nichts hochgeladen, wird nichts ausgegeben (kein selbstgebautes Ersatz-Logo).
 */
function pfadi_symbol( $variant = 'default' ) {
	$id = 0;
	if ( 'light' === $variant ) {
		$id = (int) pfadi_mod( 'pfadi_symbol_light', 0 );
	}
	if ( ! $id ) {
		$id = (int) pfadi_mod( 'pfadi_symbol', 0 );
	}
	if ( ! $id ) {
		return '';
	}
	return wp_get_attachment_image( $id, 'medium', false, array( 'class' => 'pfadi-symbol', 'alt' => '', 'loading' => 'lazy' ) );
}

/**
 * Lesezeit in Minuten.
 */
function pfadi_reading_time( $post_id = null ) {
	$content = get_post_field( 'post_content', $post_id ? $post_id : get_the_ID() );
	$words   = str_word_count( wp_strip_all_tags( strip_shortcodes( $content ) ) );
	return max( 1, (int) ceil( $words / 200 ) );
}

/**
 * Metazeile: Datum + Lesezeit.
 */
function pfadi_post_meta( $show_reading = true ) {
	echo '<div class="post-meta">';
	printf(
		'<span class="meta-date">%1$s<time datetime="%2$s">%3$s</time></span>',
		pfadi_icon( 'calendar' ), // phpcs:ignore
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() )
	);
	if ( $show_reading && pfadi_mod( 'pfadi_reading_time', true ) ) {
		/* translators: %d: Minuten */
		printf( '<span class="meta-read">%1$s%2$s</span>', pfadi_icon( 'clock' ), esc_html( sprintf( __( '%d Min. Lesezeit', 'pfadi-rankweil' ), pfadi_reading_time() ) ) ); // phpcs:ignore
	}
	echo '</div>';
}

/**
 * Zu welcher Stufe (1–4) gehört eine Kategorie? 0 = keine.
 * Zuordnung über die Kategorie-Slugs aus dem Customizer (Startseite: Stufen).
 */
function pfadi_stufe_of_term( $term ) {
	static $map = null;
	if ( null === $map ) {
		$map = array();
		foreach ( pfadi_stufen_defaults() as $i => $d ) {
			$slugs = array_filter( array_map( 'trim', explode( ',', (string) pfadi_mod( "pfadi_stufe{$i}_cats", $d['cats'] ) ) ) );
			foreach ( $slugs as $pos => $slug ) {
				$map[ $slug ] = array( $i, $pos );
			}
		}
	}
	return isset( $map[ $term->slug ] ) ? $map[ $term->slug ] : array( 0, 0 );
}

/**
 * Sinnvolle Kategorien eines Beitrags für die Anzeige:
 * - „Allgemein“ (Standardkategorie) nur, wenn sonst nichts da ist
 * - Oberkategorie (z. B. „Gruppe“) weglassen, wenn eine Unterkategorie (Stufe) gesetzt ist
 * - mehr als $max Stufen → stattdessen die gemeinsame Oberkategorie („Gruppe“)
 * - Stufen in Stufen-Reihenfolge
 *
 * @return WP_Term[]
 */
function pfadi_post_categories( $post_id = null, $max = 3 ) {
	$cats = get_the_category( $post_id );
	if ( empty( $cats ) ) {
		return array();
	}

	$default = (int) get_option( 'default_category' );
	if ( count( $cats ) > 1 ) {
		$without = array_filter(
			$cats,
			function ( $c ) use ( $default ) {
				return (int) $c->term_id !== $default;
			}
		);
		if ( $without ) {
			$cats = $without;
		}
	}

	// Eltern entfernen, deren Kinder auch gesetzt sind.
	$all  = $cats;
	$cats = array_filter(
		$cats,
		function ( $c ) use ( $all ) {
			foreach ( $all as $o ) {
				if ( (int) $o->parent === (int) $c->term_id ) {
					return false;
				}
			}
			return true;
		}
	);

	// Zu viele → gemeinsame Oberkategorie.
	if ( count( $cats ) > $max ) {
		$parents = array_unique( wp_list_pluck( $cats, 'parent' ) );
		if ( 1 === count( $parents ) && (int) reset( $parents ) > 0 ) {
			$parent = get_term( (int) reset( $parents ), 'category' );
			if ( $parent && ! is_wp_error( $parent ) ) {
				return array( $parent );
			}
		}
	}

	usort(
		$cats,
		function ( $a, $b ) {
			$sa = pfadi_stufe_of_term( $a );
			$sb = pfadi_stufe_of_term( $b );
			// Nicht-Stufen (News, Gruppe …) zuerst, dann Stufen in Reihenfolge.
			$ka = $sa[0] ? $sa[0] * 10 + $sa[1] : 0;
			$kb = $sb[0] ? $sb[0] * 10 + $sb[1] : 0;
			return $ka - $kb;
		}
	);

	return array_slice( array_values( $cats ), 0, $max );
}

/**
 * Kategorie-Labels (Stufen in ihrer Farbe).
 *
 * @param bool $linked Als Link ausgeben.
 * @param int  $max    Maximale Anzahl Labels.
 */
function pfadi_category_pill( $linked = true, $max = 3 ) {
	$cats = pfadi_post_categories( null, $max );
	if ( empty( $cats ) ) {
		return;
	}
	echo '<span class="pills">';
	foreach ( $cats as $c ) {
		$s     = pfadi_stufe_of_term( $c );
		$class = 'pill' . ( $s[0] ? ' pill--stufe stufe-' . $s[0] : '' ) . ' pill--' . sanitize_html_class( $c->slug );
		if ( $linked ) {
			printf( '<a class="%1$s" href="%2$s">%3$s</a>', esc_attr( $class ), esc_url( get_category_link( $c ) ), esc_html( $c->name ) );
		} else {
			printf( '<span class="%1$s">%2$s</span>', esc_attr( $class ), esc_html( $c->name ) );
		}
	}
	echo '</span>';
}

/**
 * Kategorien, die auf der Startseite (Aufmacher & Berichte) nicht erscheinen sollen.
 *
 * @return int[]
 */
function pfadi_excluded_cat_ids() {
	$ids = array();
	foreach ( array_filter( array_map( 'trim', explode( ',', (string) pfadi_mod( 'pfadi_exclude_cats', 'wichtiges' ) ) ) ) as $slug ) {
		$t = get_category_by_slug( $slug );
		if ( $t ) {
			$ids[] = (int) $t->term_id;
		}
	}
	return $ids;
}

/**
 * Beitragsbild oder Platzhalter mit Lilie.
 */
function pfadi_thumbnail( $size = 'pfadi-card', $sizes = '', $priority = false ) {
	if ( has_post_thumbnail() ) {
		$attr = array(
			// Steht immer neben dem Titel – leerer Alt-Text, sonst wird der Titel doppelt vorgelesen.
			'alt'      => '',
			'decoding' => 'async',
		);
		// Das größte Bild im sichtbaren Bereich (z. B. die große Bento-Kachel) sofort und
		// bevorzugt laden – alles andere erst, wenn es in die Nähe des Bildschirms kommt.
		if ( $priority ) {
			$attr['loading']       = 'eager';
			$attr['fetchpriority'] = 'high';
		} else {
			$attr['loading'] = 'lazy';
		}
		// Tatsächliche Anzeigebreite, damit der Browser aus srcset die passende Größe wählt.
		if ( $sizes ) {
			$attr['sizes'] = $sizes;
		}
		the_post_thumbnail( $size, $attr );
	} else {
		echo '<span class="thumb-fallback">' . pfadi_symbol( 'light' ) . '</span>'; // phpcs:ignore
	}
}

/**
 * Social-Links.
 */
function pfadi_social_links( $class = 'social-links' ) {
	$out = '';

	// Vorrang: Menü an der Position „Social-Media-Links“ (wie bei HitMag/HybridMag).
	$locations = get_nav_menu_locations();
	$items     = ! empty( $locations['menu-social'] ) ? wp_get_nav_menu_items( $locations['menu-social'] ) : array();
	if ( $items && ! is_wp_error( $items ) ) {
		$services = array(
			'facebook'  => '#facebook\.com|fb\.com#i',
			'instagram' => '#instagram\.com#i',
			'youtube'   => '#youtube\.com|youtu\.be#i',
			'tiktok'    => '#tiktok\.com#i',
			'whatsapp'  => '#whatsapp\.com|wa\.me#i',
			'mail'      => '#^mailto:#i',
		);
		foreach ( $items as $item ) {
			if ( (int) $item->menu_item_parent ) {
				continue;
			}
			$icon = 'link';
			foreach ( $services as $key => $re ) {
				if ( preg_match( $re, $item->url ) ) {
					$icon = $key;
					break;
				}
			}
			$out .= sprintf(
				'<li><a href="%1$s" target="_blank" rel="noopener" aria-label="%2$s">%3$s</a></li>',
				esc_url( $item->url ),
				esc_attr( $item->title ),
				pfadi_icon( $icon )
			);
		}
		echo '<ul class="' . esc_attr( $class ) . '">' . $out . '</ul>'; // phpcs:ignore
		return;
	}

	foreach ( pfadi_social_networks() as $key => $label ) {
		$url = pfadi_mod( "pfadi_social_{$key}", '' );
		if ( $url ) {
			$out .= sprintf(
				'<li><a href="%1$s" target="_blank" rel="noopener" aria-label="%2$s">%3$s</a></li>',
				esc_url( $url ),
				esc_attr( $label ),
				pfadi_icon( $key )
			);
		}
	}
	if ( $out ) {
		echo '<ul class="' . esc_attr( $class ) . '">' . $out . '</ul>'; // phpcs:ignore
	}
}

/**
 * Teilen-Links (reine Links, kein Tracking-Skript).
 */
function pfadi_share_links() {
	if ( ! pfadi_mod( 'pfadi_share', true ) ) {
		return;
	}
	$url   = rawurlencode( get_permalink() );
	$title = rawurlencode( get_the_title() );
	$links = array(
		'whatsapp' => array( 'WhatsApp', 'https://wa.me/?text=' . $title . '%20' . $url ),
		'facebook' => array( 'Facebook', 'https://www.facebook.com/sharer/sharer.php?u=' . $url ),
		'mail'     => array( 'E-Mail', 'mailto:?subject=' . $title . '&body=' . $url ),
	);
	echo '<div class="share"><span class="share-label">' . esc_html__( 'Teilen:', 'pfadi-rankweil' ) . '</span>';
	foreach ( $links as $key => $l ) {
		printf( '<a class="share-%1$s" href="%2$s" target="_blank" rel="noopener" aria-label="%3$s">%4$s</a>', esc_attr( $key ), esc_url( $l[1] ), esc_attr( $l[0] ), pfadi_icon( $key ) ); // phpcs:ignore
	}
	printf( '<button type="button" class="share-copy" data-url="%1$s" aria-label="%2$s">%3$s</button>', esc_url( get_permalink() ), esc_attr__( 'Link kopieren', 'pfadi-rankweil' ), pfadi_icon( 'link' ) ); // phpcs:ignore
	echo '</div>';
}

/**
 * Einfache Breadcrumbs.
 */
function pfadi_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}
	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Brotkrümelnavigation', 'pfadi-rankweil' ) . '"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Start', 'pfadi-rankweil' ) . '</a>';
	if ( is_single() ) {
		$cats = pfadi_post_categories( null, 1 );
		if ( $cats ) {
			$chain = array_reverse( get_ancestors( $cats[0]->term_id, 'category' ) );
			$chain[] = $cats[0]->term_id;
			foreach ( $chain as $tid ) {
				$t = get_term( $tid, 'category' );
				if ( $t && ! is_wp_error( $t ) ) {
					echo '<span>/</span><a href="' . esc_url( get_category_link( $t ) ) . '">' . esc_html( $t->name ) . '</a>';
				}
			}
		}
	} elseif ( is_category() ) {
		foreach ( array_reverse( get_ancestors( get_queried_object_id(), 'category' ) ) as $tid ) {
			$t = get_term( $tid, 'category' );
			if ( $t && ! is_wp_error( $t ) ) {
				echo '<span>/</span><a href="' . esc_url( get_category_link( $t ) ) . '">' . esc_html( $t->name ) . '</a>';
			}
		}
	} elseif ( is_page() ) {
		foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $anc ) {
			echo '<span>/</span><a href="' . esc_url( get_permalink( $anc ) ) . '">' . esc_html( get_the_title( $anc ) ) . '</a>';
		}
	}
	echo '</nav>';
}

/**
 * Seitenkopf für Archive / Seiten.
 */
function pfadi_page_hero( $title, $subtitle = '', $class = '' ) {
	?>
	<header class="page-hero <?php echo esc_attr( $class ); ?>">
		<div class="container">
			<?php pfadi_breadcrumbs(); ?>
			<h1 class="page-hero__title"><?php echo wp_kses_post( $title ); ?></h1>
			<?php if ( $subtitle ) : ?>
				<div class="page-hero__sub"><?php echo wp_kses_post( $subtitle ); ?></div>
			<?php endif; ?>
		</div>
		<?php pfadi_wave( 'bottom' ); ?>
	</header>
	<?php
}

/**
 * Wellen-Trenner (Hügel-Silhouette).
 */
function pfadi_wave( $pos = 'bottom' ) {
	echo '<svg class="wave wave--' . esc_attr( $pos ) . '" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true"><path d="M0 48c120-26 240-38 360-30s240 34 360 36 240-22 360-34 240-2 360 14v46H0z" fill="currentColor"/></svg>';
}

/**
 * Pagination.
 */
function pfadi_pagination() {
	the_posts_pagination(
		array(
			'mid_size'  => 1,
			'prev_text' => '<span class="screen-reader-text">' . __( 'Zurück', 'pfadi-rankweil' ) . '</span>←',
			'next_text' => '<span class="screen-reader-text">' . __( 'Weiter', 'pfadi-rankweil' ) . '</span>→',
		)
	);
}
