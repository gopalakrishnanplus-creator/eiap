<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package WP News and Scrolling Widgets
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div id="pciwgas_welcome_tabs" class="pciwgas-vtab-cnt pciwgas_welcome_tabs pciwgas-clearfix">
	


	<!-- <div class="pciwgas-deal-offer-wrap">
		<h3 style="font-weight: bold; font-size: 30px; color:#ffef00; text-align:center; margin: 15px 0 5px 0;">Why Invest Time On Free Version?</h3>

		<h3 style="font-size: 18px; text-align:center; margin:0; color:#fff;">Explore Post Category Image with Essential Bundle Free for 5 Days.</h3>			

		<div class="pciwgas-deal-free-offer">
			<a href="<?php //echo esc_url( PCIWGAS_PLUGIN_BUNDLE_LINK ); ?>" target="_blank" class="pciwgas-sf-free-btn"><span class="dashicons dashicons-cart"></span> Try Pro For 5 Days Free</a>
		</div>
	</div> -->

	<!-- <div class="pciwgas-black-friday-banner-wrp">
		<a href="<?php // echo esc_url( PCIWGAS_PLUGIN_BUNDLE_LINK ); ?>" target="_blank"><img style="width: 100%;" src="<?php // echo esc_url( PCIWGAS_URL ); ?>assets/images/black-friday-banner.png" alt="black-friday-banner" /></a>
	</div> -->

	<div class="pciwgas-black-friday-banner-wrp" style="background:#e1ecc8;padding: 20px 20px 40px; border-radius:5px; text-align:center;margin-bottom: 40px;">
		<h2 style="font-size:30px; margin-bottom:10px;"><span style="color:#0055fb;">Post Category Image with Grid and Slider</span> is included in <span style="color:#0055fb;">Essential Plugin Bundle</span> </h2> 
		<h4 style="font-size: 18px;margin-top: 0px;color: #ff5d52;margin-bottom: 24px;">Now get Designs, Optimization, Security, Backup, Migration Solutions @ one stop. </h4>

		<div class="pciwgas-black-friday-feature">

			<div class="pciwgas-inner-deal-class" style="width:40%;">
				<div class="pciwgas-inner-Bonus-class">Bonus</div>
				<div class="pciwgas-image-logo" style="font-weight: bold;font-size: 26px;color: #222;"><img style="width: 34px; height:34px;vertical-align: middle;margin-right: 5px;" class="pciwgas-img-logo" src="<?php echo esc_url( PCIWGAS_URL ); ?>assets/images/essential-logo-small.png" alt="essential-logo" /><span class="pciwgas-esstial-name" style="color:#0055fb;">Essential </span>Plugin</div>
				<div class="pciwgas-sub-heading" style="font-size: 16px;text-align: left;font-weight: bold;color: #222;margin-bottom: 10px;">Includes All premium plugins at no extra cost.</div>
				<a class="pciwgas-sf-btn" href="<?php echo esc_url( PCIWGAS_PLUGIN_BUNDLE_LINK ); ?>" target="_blank">Grab The Deal</a>
			</div>

			<div class="pciwgas-main-list-class" style="width:60%;">
				<div class="pciwgas-inner-list-class">
					<div class="pciwgas-list-img-class"><img src="<?php echo esc_url( PCIWGAS_URL ); ?>assets/images/logo-image/img-slider.png" alt="essential-logo" /> Image Slider</li></div>

					<div class="pciwgas-list-img-class"><img src="<?php echo esc_url( PCIWGAS_URL ); ?>assets/images/logo-image/advertising.png" alt="essential-logo" /> Publication</li></div>

					<div class="pciwgas-list-img-class"><img src="<?php echo esc_url( PCIWGAS_URL ); ?>assets/images/logo-image/marketing.png" alt="essential-logo" /> Marketing</li></div>

					<div class="pciwgas-list-img-class"><img src="<?php echo esc_url( PCIWGAS_URL ); ?>assets/images/logo-image/photo-album.png" alt="essential-logo" /> Photo album</li></div>

					<div class="pciwgas-list-img-class"><img src="<?php echo esc_url( PCIWGAS_URL ); ?>assets/images/logo-image/showcase.png" alt="essential-logo" /> Showcase</li></div>

					<div class="pciwgas-list-img-class"><img src="<?php echo esc_url( PCIWGAS_URL ); ?>assets/images/logo-image/shopping-bag.png" alt="essential-logo" /> WooCommerce</li></div>

					<div class="pciwgas-list-img-class"><img src="<?php echo esc_url( PCIWGAS_URL ); ?>assets/images/logo-image/performance.png" alt="essential-logo" /> Performance</li></div>

					<div class="pciwgas-list-img-class"><img src="<?php echo esc_url( PCIWGAS_URL ); ?>assets/images/logo-image/security.png" alt="essential-logo" /> Security</li></div>

					<div class="pciwgas-list-img-class"><img src="<?php echo esc_url( PCIWGAS_URL ); ?>assets/images/logo-image/forms.png" alt="essential-logo" /> Pro Forms</li></div>

					<div class="pciwgas-list-img-class"><img src="<?php echo esc_url( PCIWGAS_URL ); ?>assets/images/logo-image/seo.png" alt="essential-logo" /> SEO</li></div>

					<div class="pciwgas-list-img-class"><img src="<?php echo esc_url( PCIWGAS_URL ); ?>assets/images/logo-image/backup.png" alt="essential-logo" /> Backups</li></div>

					<div class="pciwgas-list-img-class"><img src="<?php echo esc_url( PCIWGAS_URL ); ?>assets/images/logo-image/White-labeling.png" alt="essential-logo" /> Migration</li></div>
				</div>
			</div>
		</div>
		<div class="pciwgas-main-feature-item">
			<div class="pciwgas-inner-feature-item">
				<div class="pciwgas-list-feature-item">
					<img src="<?php echo esc_url( PCIWGAS_URL ); ?>assets/images/logo-image/layers.png" alt="layer" />
					<h5>Site management</h5>
					<p>Manage, update, secure & optimize unlimited sites.</p>
				</div>
				<div class="pciwgas-list-feature-item">
					<img src="<?php echo esc_url( PCIWGAS_URL ); ?>assets/images/logo-image/risk.png" alt="backup" />
					<h5>Backup storage</h5>
					<p>Secure sites with auto backups and easy restore.</p>
				</div>
				<div class="pciwgas-list-feature-item">
					<img src="<?php echo esc_url( PCIWGAS_URL ); ?>assets/images/logo-image/support.png" alt="support" />
					<h5>Support</h5>
					<p>Get answers on everything WordPress at anytime.</p>
				</div>
			</div>
		</div>
		<a class="pciwgas-sf-btn" href="<?php echo esc_url( PCIWGAS_PLUGIN_BUNDLE_LINK ); ?>" target="_blank">Grab The Deal</a>
	</div>

	<!-- Start - Welcome Box -->
	<div class="pciwgas-welcome-wrap" style="padding: 30px;border-radius: 10px;border: 1px solid #e5ecf6;">
		<div class="pciwgas-welcome-inr pciwgas-center">
			<div style="font-size: 24px; font-weight:700; margin-bottom: 15px;">Display <span class="pciwgas-blue">post categories with grid and slider</span> layout. Also given option to upload image for post category.</div>
			<h5 class="pciwgas-content" style="font-size: 20px; font-weight:700; margin-bottom: 15px;">Experience <span class="pciwgas-blue">2 Layouts</span>, <span class="pciwgas-blue">20+ stunning designs.</span></h5>
		</div>
		<div style="margin: 30px 0; text-transform: uppercase; text-align:center;">
			<a href="<?php echo esc_url( $pciwgas_add_link ); ?>" class="pciwgas-sf-btn">Launch Post Category Image With Free Features</a>
		</div>
	</div>

</div>