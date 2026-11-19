<?php
/**
 * Mitmach-Banner.
 *
 * @package pfadi-rankweil
 */

$title = pfadi_mod( 'pfadi_cta_title', 'Lust auf Abenteuer?' );
$text  = pfadi_mod( 'pfadi_cta_text', 'Komm vorbei und schnuppere in eine Heimstunde – wir freuen uns auf dich!' );
$btn   = pfadi_mod( 'pfadi_cta_btn_text', 'Zur Anmeldung' );
$url   = pfadi_url( pfadi_mod( 'pfadi_cta_btn_url', '/anmeldung/' ) );
?>
<section class="section section--tight">
	<div class="container">
		<div class="cta">
			<?php $pfadi_sym = pfadi_symbol( 'light' ); ?>
			<?php if ( $pfadi_sym ) : ?><span class="cta__deco" aria-hidden="true"><?php echo $pfadi_sym; // phpcs:ignore ?></span><?php endif; ?>
			<div class="cta__text">
				<h2 class="cta__title"><?php echo esc_html( $title ); ?></h2>
				<?php if ( $text ) : ?>
					<p><?php echo wp_kses_post( $text ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $btn && $url ) : ?>
				<a class="btn btn--white" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $btn ); ?> <?php echo pfadi_icon( 'arrow' ); // phpcs:ignore ?></a>
			<?php endif; ?>
		</div>
	</div>
</section>
