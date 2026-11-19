<?php
/**
 * Kompatibilität zu HitMag Pro.
 *
 * 1) Widget „Sidebar Posts“ mit derselben internen Kennung (`sidebar_posts`) und denselben
 *    Einstellungen wie bei HitMag. Dadurch funktionieren die vorhandenen Widgets
 *    (z. B. „Neues aus der Gruppe“, „Wichtiges“) ohne Neuanlage – in beiden Themes.
 * 2) Widget-Bereiche heißen wie bei HitMag (sidebar-1, footer-left/-mid/-right), damit
 *    WordPress beim Theme-Wechsel jedes Widget an denselben Platz legt.
 * 3) Einmalige Reparatur: Widgets, die beim Wechsel verrutscht sind, kommen an den Platz
 *    zurück, den sie im vorherigen Theme hatten (aus der Sicherung in migration.php).
 *
 * @package pfadi-rankweil
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Beiträge mit Vorschaubild – kompatibel zu HitMag „Sidebar Posts“.
 */
class Pfadi_Sidebar_Posts_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'sidebar_posts',
			__( 'Beiträge mit Bild', 'pfadi-rankweil' ),
			array(
				'classname'                   => 'widget_sidebar_posts',
				'description'                 => __( 'Neueste Beiträge einer Kategorie mit Vorschaubild.', 'pfadi-rankweil' ),
				'customize_selective_refresh' => true,
				'show_instance_in_rest'       => true,
			)
		);
	}

	/**
	 * Ausgabe.
	 *
	 * @param array $args     Bereichs-Argumente.
	 * @param array $instance Einstellungen.
	 */
	public function widget( $args, $instance ) {
		$title  = isset( $instance['title'] ) ? $instance['title'] : '';
		$title  = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$number = isset( $instance['number_posts'] ) ? max( 1, min( 20, (int) $instance['number_posts'] ) ) : 5;
		$cat    = isset( $instance['category'] ) ? (int) $instance['category'] : 0;

		$q = new WP_Query(
			array(
				'posts_per_page'      => $number,
				'cat'                 => $cat,
				'ignore_sticky_posts' => empty( $instance['sticky_posts'] ),
				'no_found_rows'       => true,
			)
		);
		if ( ! $q->have_posts() ) {
			return;
		}

		echo $args['before_widget']; // phpcs:ignore
		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore
		}
		echo '<ul class="mini-posts">';
		while ( $q->have_posts() ) {
			$q->the_post();
			printf(
				'<li><a class="mini-posts__link" href="%1$s"><span class="mini-posts__thumb">%2$s</span><span class="mini-posts__body"><span class="mini-posts__title">%3$s</span><span class="mini-posts__date">%4$s</span></span></a></li>',
				esc_url( get_permalink() ),
				has_post_thumbnail() ? get_the_post_thumbnail( null, 'thumbnail', array( 'loading' => 'lazy', 'alt' => '' ) ) : '<span class="thumb-fallback"></span>',
				esc_html( get_the_title() ),
				esc_html( get_the_date() )
			);
		}
		echo '</ul>';
		wp_reset_postdata();
		echo $args['after_widget']; // phpcs:ignore
	}

	/**
	 * Einstellungsformular (Design → Widgets).
	 *
	 * @param array $instance Einstellungen.
	 */
	public function form( $instance ) {
		$title  = isset( $instance['title'] ) ? $instance['title'] : '';
		$cat    = isset( $instance['category'] ) ? (int) $instance['category'] : 0;
		$number = isset( $instance['number_posts'] ) ? (int) $instance['number_posts'] : 5;
		$sticky = ! empty( $instance['sticky_posts'] );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Titel:', 'pfadi-rankweil' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Kategorie:', 'pfadi-rankweil' ); ?></label>
			<?php
			wp_dropdown_categories(
				array(
					'show_option_all' => __( '– Alle Kategorien –', 'pfadi-rankweil' ),
					'hide_empty'      => 0,
					'hierarchical'    => 1,
					'selected'        => $cat,
					'name'            => $this->get_field_name( 'category' ),
					'id'              => $this->get_field_id( 'category' ),
					'class'           => 'widefat',
				)
			);
			?>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number_posts' ) ); ?>"><?php esc_html_e( 'Anzahl Beiträge:', 'pfadi-rankweil' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number_posts' ) ); ?>" type="number" min="1" max="20" value="<?php echo esc_attr( $number ); ?>">
		</p>
		<p>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'sticky_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'sticky_posts' ) ); ?>" <?php checked( $sticky ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'sticky_posts' ) ); ?>"><?php esc_html_e( 'Fixierte Beiträge zuerst', 'pfadi-rankweil' ); ?></label>
		</p>
		<?php
		return '';
	}

	/**
	 * Speichern.
	 *
	 * @param array $new_instance Neu.
	 * @param array $old_instance Alt.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		return array(
			'title'        => sanitize_text_field( isset( $new_instance['title'] ) ? $new_instance['title'] : '' ),
			'category'     => absint( isset( $new_instance['category'] ) ? $new_instance['category'] : 0 ),
			'number_posts' => max( 1, min( 20, absint( isset( $new_instance['number_posts'] ) ? $new_instance['number_posts'] : 5 ) ) ),
			'sticky_posts' => ! empty( $new_instance['sticky_posts'] ),
		);
	}
}

add_action(
	'widgets_init',
	function () {
		register_widget( 'Pfadi_Sidebar_Posts_Widget' );
	}
);

/**
 * Bisherige Bereichs-Kennungen dieses Themes → HitMag-Kennungen.
 */
function pfadi_sidebar_id_map() {
	return array(
		'footer-1' => 'footer-left',
		'footer-2' => 'footer-mid',
		'footer-3' => 'footer-right',
	);
}

/**
 * Einmalige Reparatur der Widget-Anordnung (läuft im Admin bzw. direkt nach dem Aktivieren).
 */
function pfadi_repair_widgets() {
	global $wp_registered_widgets;

	if ( get_option( 'pfadi_widgets_repaired_v161' ) ) {
		return;
	}
	$sw = get_option( 'sidebars_widgets' );
	if ( ! is_array( $sw ) ) {
		return;
	}

	// a) Alte Footer-Kennungen (bis 1.6.0) auf die HitMag-Kennungen umziehen.
	foreach ( pfadi_sidebar_id_map() as $old => $new ) {
		if ( ! empty( $sw[ $old ] ) && is_array( $sw[ $old ] ) ) {
			$sw[ $new ] = array_values( array_unique( array_merge( isset( $sw[ $new ] ) && is_array( $sw[ $new ] ) ? $sw[ $new ] : array(), $sw[ $old ] ) ) );
		}
		unset( $sw[ $old ] );
	}

	// b) Anordnung aus dem vorherigen Theme für Bereiche mit gleicher Kennung übernehmen.
	$snap = get_option( 'pfadi_prev_theme_snapshot' );
	if ( is_array( $snap ) && ! empty( $snap['sidebars_widgets'] ) && is_array( $snap['sidebars_widgets'] ) ) {
		foreach ( $snap['sidebars_widgets'] as $sid => $list ) {
			if ( 'wp_inactive_widgets' === $sid || 'array_version' === $sid || ! is_array( $list ) || ! is_registered_sidebar( $sid ) ) {
				continue;
			}
			foreach ( array_values( $list ) as $pos => $wid ) {
				if ( ! isset( $wp_registered_widgets[ $wid ] ) ) {
					continue; // Widget-Typ gibt es in diesem Theme nicht.
				}
				// Aus allen anderen Bereichen entfernen …
				foreach ( $sw as $k => $l ) {
					if ( is_array( $l ) && $k !== $sid ) {
						$sw[ $k ] = array_values( array_diff( $l, array( $wid ) ) );
					}
				}
				// … und im gleichen Bereich an die gleiche Stelle setzen.
				$cur = isset( $sw[ $sid ] ) && is_array( $sw[ $sid ] ) ? array_values( array_diff( $sw[ $sid ], array( $wid ) ) ) : array();
				array_splice( $cur, min( $pos, count( $cur ) ), 0, array( $wid ) );
				$sw[ $sid ] = $cur;
			}
		}
	}

	update_option( 'sidebars_widgets', $sw );
	update_option( 'pfadi_widgets_repaired_v161', time(), false );
}
add_action(
	'admin_init',
	function () {
		if ( current_user_can( 'edit_theme_options' ) ) {
			pfadi_repair_widgets();
		}
	}
);
add_action( 'after_switch_theme', 'pfadi_repair_widgets', 100 );
