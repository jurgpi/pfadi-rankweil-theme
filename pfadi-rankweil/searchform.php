<?php
/**
 * Suchformular.
 *
 * @package pfadi-rankweil
 */

$pfadi_sid = wp_unique_id( 'search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $pfadi_sid ); ?>"><?php esc_html_e( 'Suche nach:', 'pfadi-rankweil' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $pfadi_sid ); ?>" class="search-field" placeholder="<?php esc_attr_e( 'Berichte, Lager, Termine …', 'pfadi-rankweil' ); ?>" value="<?php echo get_search_query(); ?>" name="s">
	<button type="submit" class="search-submit"><?php echo pfadi_icon( 'search' ); // phpcs:ignore ?><span class="screen-reader-text"><?php esc_html_e( 'Suchen', 'pfadi-rankweil' ); ?></span></button>
</form>
