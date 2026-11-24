<?php
/**
 * Kopfbereich.
 *
 * @package pfadi-rankweil
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="<?php echo esc_attr( pfadi_mod( 'pfadi_color_primary', '#05368B' ) ); ?>" data-light="<?php echo esc_attr( pfadi_mod( 'pfadi_color_primary', '#05368B' ) ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Zum Inhalt springen', 'pfadi-rankweil' ); ?></a>

<?php if ( pfadi_mod( 'pfadi_topbar', true ) ) : ?>
	<div class="topbar">
		<div class="container topbar__inner">
			<p class="topbar__text"><?php echo esc_html( pfadi_mod( 'pfadi_topbar_text', 'Allzeit bereit! – Pfadfinder*innen in Rankweil' ) ); ?></p>
			<?php pfadi_social_links( 'social-links social-links--top' ); ?>
		</div>
	</div>
<?php endif; ?>

<header class="site-header" id="site-header">
	<div class="container site-header__inner">
		<?php $pfadi_dark_logo = (int) pfadi_mod( 'pfadi_logo_dark', 0 ); ?>
		<div class="site-branding<?php echo $pfadi_dark_logo ? ' has-dark-logo' : ''; ?>">
			<?php if ( has_custom_logo() ) : ?>
				<?php
				$pfadi_logo_html = get_custom_logo();
				if ( $pfadi_dark_logo ) {
					$pfadi_dark_img  = wp_get_attachment_image( $pfadi_dark_logo, 'full', false, array( 'class' => 'custom-logo custom-logo--dark', 'alt' => get_bloginfo( 'name' ) ) );
					$pfadi_logo_html = str_replace( '</a>', $pfadi_dark_img . '</a>', $pfadi_logo_html );
				}
				echo $pfadi_logo_html; // phpcs:ignore
				?>
			<?php else : ?>
				<a class="site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php $pfadi_sym = pfadi_symbol(); ?>
					<?php if ( $pfadi_sym ) : ?><span class="site-title__icon"><?php echo $pfadi_sym; // phpcs:ignore ?></span><?php endif; ?>
					<span><?php bloginfo( 'name' ); ?></span>
				</a>
			<?php endif; ?>
		</div>

		<?php if ( 'off' !== pfadi_mod( 'pfadi_dark_mode', 'auto' ) ) : ?>
			<button class="theme-toggle" type="button" aria-pressed="false" aria-label="<?php esc_attr_e( 'Hell/Dunkel umschalten', 'pfadi-rankweil' ); ?>" title="<?php esc_attr_e( 'Hell/Dunkel umschalten', 'pfadi-rankweil' ); ?>">
				<?php echo pfadi_icon( 'moon' ) . pfadi_icon( 'sun' ); // phpcs:ignore ?>
			</button>
		<?php endif; ?>

		<button class="nav-toggle" aria-controls="site-nav" aria-expanded="false">
			<span class="nav-toggle__open"><?php echo pfadi_icon( 'menu' ); // phpcs:ignore ?></span>
			<span class="nav-toggle__close"><?php echo pfadi_icon( 'close' ); // phpcs:ignore ?></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Menü', 'pfadi-rankweil' ); ?></span>
		</button>

		<nav class="site-nav" id="site-nav" aria-label="<?php esc_attr_e( 'Hauptmenü', 'pfadi-rankweil' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
					'menu_class'     => 'menu',
					'container'      => false,
					'fallback_cb'    => 'pfadi_menu_fallback',
					'depth'          => 3,
				)
			);
			?>
			<div class="site-nav__extras">
				<?php if ( pfadi_mod( 'pfadi_header_search', true ) ) : ?>
					<button class="search-toggle" aria-controls="header-search" aria-expanded="false">
						<?php echo pfadi_icon( 'search' ); // phpcs:ignore ?>
						<span class="screen-reader-text"><?php esc_html_e( 'Suche', 'pfadi-rankweil' ); ?></span>
					</button>
				<?php endif; ?>
				<?php
				$cta_text = pfadi_mod( 'pfadi_header_cta_text', 'Anmelden' );
				$cta_url  = pfadi_url( pfadi_mod( 'pfadi_header_cta_url', '/anmeldung/' ) );
				if ( $cta_text && $cta_url ) :
					?>
					<a class="btn btn--accent btn--sm" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_text ); ?></a>
				<?php endif; ?>
			</div>
		</nav>
	</div>
	<?php if ( pfadi_mod( 'pfadi_header_search', true ) ) : ?>
		<div class="header-search" id="header-search" hidden>
			<div class="container"><?php get_search_form(); ?></div>
		</div>
	<?php endif; ?>
</header>

<main id="main" class="site-main">
