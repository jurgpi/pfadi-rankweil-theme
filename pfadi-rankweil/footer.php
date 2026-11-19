<?php
/**
 * Fußbereich.
 *
 * @package pfadi-rankweil
 */
?>
</main>

<footer class="site-footer">
	<?php pfadi_wave( 'top' ); ?>
	<div class="container">
		<?php
		$pfadi_has_badge = (bool) pfadi_mod( 'pfadi_footer_logo', 0 );
		$pfadi_fcols     = 0;
		$pfadi_fareas    = array( 'footer-left', 'footer-mid', 'footer-right' );
		foreach ( $pfadi_fareas as $pfadi_area ) {
			$pfadi_fcols += is_active_sidebar( $pfadi_area ) ? 1 : 0;
		}
		?>
		<div class="footer-grid<?php echo $pfadi_has_badge ? ' has-badge' : ''; ?>" style="--fcols:<?php echo max( 1, $pfadi_fcols ); ?>">
			<div class="footer-about">
				<a class="footer-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php $pfadi_sym = pfadi_symbol( 'light' ); ?>
					<?php if ( $pfadi_sym ) : ?><span class="footer-logo__icon"><?php echo $pfadi_sym; // phpcs:ignore ?></span><?php endif; ?>
					<span><?php bloginfo( 'name' ); ?></span>
				</a>
				<?php $about = pfadi_mod( 'pfadi_footer_about', '' ); ?>
				<?php if ( $about ) : ?>
					<p><?php echo wp_kses_post( $about ); ?></p>
				<?php endif; ?>
				<?php pfadi_social_links( 'social-links social-links--footer' ); ?>
			</div>
			<?php foreach ( $pfadi_fareas as $pfadi_area ) : ?>
				<?php if ( is_active_sidebar( $pfadi_area ) ) : ?>
					<div class="footer-col footer-col--<?php echo esc_attr( $pfadi_area ); ?>"><?php dynamic_sidebar( $pfadi_area ); ?></div>
				<?php endif; ?>
			<?php endforeach; ?>

			<?php
			$pfadi_flogo = (int) pfadi_mod( 'pfadi_footer_logo', 0 );
			if ( $pfadi_flogo ) :
				$pfadi_flogo_url = pfadi_url( pfadi_mod( 'pfadi_footer_logo_url', 'https://ppoe.at/' ) );
				$pfadi_flogo_img = wp_get_attachment_image(
					$pfadi_flogo,
					'large',
					false,
					array(
						'class'   => 'footer-badge__img',
						'loading' => 'lazy',
						'style'   => 'width:' . max( 60, (int) pfadi_mod( 'pfadi_footer_logo_width', 240 ) ) . 'px',
						'alt'     => __( 'Pfadfinder und Pfadfinderinnen Österreichs', 'pfadi-rankweil' ),
					)
				);
				?>
				<div class="footer-badge">
					<?php if ( $pfadi_flogo_url ) : ?>
						<a href="<?php echo esc_url( $pfadi_flogo_url ); ?>" target="_blank" rel="noopener"><?php echo $pfadi_flogo_img; // phpcs:ignore ?></a>
					<?php else : ?>
						<?php echo $pfadi_flogo_img; // phpcs:ignore ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="footer-bottom">
			<p><?php echo esc_html( str_replace( array( '{jahr}', '{year}' ), wp_date( 'Y' ), pfadi_mod( 'pfadi_footer_copyright', '© {jahr} Pfadfindergruppe Rankweil' ) ) ); ?></p>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer-menu',
					'menu_class'     => 'footer-menu',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
			?>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
