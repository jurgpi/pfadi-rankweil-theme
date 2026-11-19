<?php
/**
 * Aufmacher: Willkommens-Banner.
 *
 * @package pfadi-rankweil
 */

$img_id = (int) pfadi_mod( 'pfadi_hero_image', 0 );
$img    = $img_id ? wp_get_attachment_image_url( $img_id, 'full' ) : '';
$b1t    = pfadi_mod( 'pfadi_hero_btn1_text', 'Jetzt anmelden' );
$b1u    = pfadi_url( pfadi_mod( 'pfadi_hero_btn1_url', '/anmeldung/' ) );
$b2t    = pfadi_mod( 'pfadi_hero_btn2_text', 'Unsere Stufen' );
$b2u    = pfadi_url( pfadi_mod( 'pfadi_hero_btn2_url', '#stufen' ) );
?>
<section class="hero-welcome<?php echo $img ? ' has-image' : ''; ?>"<?php echo $img ? ' style="--hero-img:url(' . esc_url( $img ) . ')"' : ''; ?>>
	<div class="container hero-welcome__inner">
		<span class="hero-welcome__badge"><?php echo pfadi_symbol( 'light' ); // phpcs:ignore ?> <?php bloginfo( 'name' ); ?></span>
		<h1 class="hero-welcome__title"><?php echo esc_html( pfadi_mod( 'pfadi_hero_title', 'Abenteuer. Freundschaft. Draußen sein.' ) ); ?></h1>
		<div class="hero-welcome__text"><?php echo wp_kses_post( wpautop( pfadi_mod( 'pfadi_hero_text', '' ) ) ); ?></div>
		<div class="hero-welcome__buttons">
			<?php if ( $b1t && $b1u ) : ?>
				<a class="btn btn--accent" href="<?php echo esc_url( $b1u ); ?>"><?php echo esc_html( $b1t ); ?> <?php echo pfadi_icon( 'arrow' ); // phpcs:ignore ?></a>
			<?php endif; ?>
			<?php if ( $b2t && $b2u ) : ?>
				<a class="btn btn--ghost" href="<?php echo esc_url( $b2u ); ?>"><?php echo esc_html( $b2t ); ?></a>
			<?php endif; ?>
		</div>
	</div>
	<?php pfadi_wave( 'bottom' ); ?>
</section>
