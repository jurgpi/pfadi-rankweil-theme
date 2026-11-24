<?php
/**
 * Stufen-Kacheln.
 *
 * @package pfadi-rankweil
 */

$title = pfadi_mod( 'pfadi_stufen_title', 'Für jedes Alter das passende Abenteuer' );
$intro = pfadi_mod( 'pfadi_stufen_intro', 'Vier Altersstufen, ein gemeinsames Ziel: Kinder und Jugendliche stark machen.' );
?>
<section class="section stufen" id="stufen">
	<div class="container">
		<header class="section-head">
			<?php if ( $title ) : ?>
				<h2 class="section-title"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>
			<?php if ( $intro ) : ?>
				<p class="section-intro"><?php echo wp_kses_post( $intro ); ?></p>
			<?php endif; ?>
		</header>

		<div class="stufen-grid">
			<?php
			foreach ( pfadi_stufen_defaults() as $i => $d ) :
				$name  = pfadi_mod( "pfadi_stufe{$i}_name", $d['name'] );
				$short = pfadi_mod( "pfadi_stufe{$i}_short", $d['short'] );
				$age   = pfadi_mod( "pfadi_stufe{$i}_age", $d['age'] );
				$text  = pfadi_mod( "pfadi_stufe{$i}_text", $d['text'] );
				$url   = pfadi_url( pfadi_mod( "pfadi_stufe{$i}_url", $d['url'] ) );
				$img   = (int) pfadi_mod( "pfadi_stufe{$i}_image", 0 );
				if ( ! $name ) {
					continue;
				}
				$tag = $url ? 'a' : 'div';
				?>
				<<?php echo $tag; // phpcs:ignore ?> class="stufe stufe-<?php echo (int) $i; ?><?php echo $img ? ' has-image' : ''; ?>"<?php echo $url ? ' href="' . esc_url( $url ) . '"' : ''; ?>>
					<?php if ( $img ) : ?>
						<span class="stufe__img"><?php echo wp_get_attachment_image( $img, 'pfadi-card', false, array( 'loading' => 'lazy' ) ); ?></span>
					<?php endif; ?>
					<span class="stufe__body">
						<span class="stufe__short"><?php echo esc_html( $short ); ?></span>
						<span class="stufe__age"><?php echo esc_html( $age ); ?></span>
						<h3 class="stufe__name"><?php echo esc_html( $name ); ?></h3>
						<span class="stufe__text"><?php echo wp_kses_post( $text ); ?></span>
						<?php if ( $url ) : ?>
							<span class="stufe__more"><?php esc_html_e( 'Mehr erfahren', 'pfadi-rankweil' ); ?> <?php echo pfadi_icon( 'arrow' ); // phpcs:ignore ?></span>
						<?php endif; ?>
					</span>
				</<?php echo $tag; // phpcs:ignore ?>>
			<?php endforeach; ?>
		</div>
	</div>
</section>
