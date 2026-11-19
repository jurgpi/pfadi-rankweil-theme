<?php
/**
 * Seitenleiste.
 *
 * @package pfadi-rankweil
 */

if ( 'none' === pfadi_mod( 'pfadi_sidebar', 'right' ) || ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<aside class="layout__side sidebar" aria-label="<?php esc_attr_e( 'Seitenleiste', 'pfadi-rankweil' ); ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
