<?php
/**
 * Wolf Visual Composer admin theme activation
 *
 * Functions related to theme activation notice
 *
 * @author WolfThemes
 * @category Admin
 * @package WolfVisualComposer/Admin
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get theme name
 */
function wvc_get_theme_name() {
	$wp_theme = wp_get_theme( get_template() );
	return $wp_theme->Name; // phpcs:ignore
}

if ( ! function_exists( 'wvc_get_theme_slug' ) ) {
	/**
	 * Get the theme slug
	 *
	 * @return string
	 */
	function wvc_get_theme_slug() {
		return apply_filters( 'wolftheme_theme_slug', esc_attr( sanitize_title_with_dashes( get_template() ) ) );
	}
}

/**
 * Handle license form submissions EARLY - before any output
 */
function wvc_handle_license_forms() {
	// Only run on the license page
	if ( ! isset( $_GET['page'] ) || strpos( $_GET['page'], '-about' ) === false ) {
		return;
	}

	// Handle reset
	if ( isset( $_POST['wvc_reset_purchase_code'] ) ) {
		if ( isset( $_POST['wvc_reset_license_nonce'] ) &&
			wp_verify_nonce( $_POST['wvc_reset_license_nonce'], 'wvc_reset_license_theme' ) ) {
			wvc_reset_license_data();
			wp_safe_redirect( admin_url( 'themes.php?page=' . wvc_get_theme_slug() . '-about#license' ) );
			exit;
		}
	}

	// Handle activation
	if ( isset( $_POST['theme_purchase_code'] ) ) {
		wvc_process_theme_activation();
	}
}
add_action( 'admin_init', 'wvc_handle_license_forms' );

/**
 * Process theme activation
 */
function wvc_process_theme_activation() {

	// Check if already activated
	if ( get_option( 'wvc_key' ) ) {
		return;
	}

	// Verify nonce for security
	if ( ! isset( $_POST['wvc_activation_nonce'] ) ||
		! wp_verify_nonce( $_POST['wvc_activation_nonce'], 'wvc_activate_theme' ) ) {
		set_transient( 'wvc_activation_error', esc_html__( 'Security check failed. Please try again.', 'wolf-visual-composer' ), 30 );
		return;
	}

	// Check if cURL is enabled
	if ( ! function_exists( 'curl_init' ) ) {
		set_transient(
			'wvc_activation_error',
			esc_html__( 'The server does not support cURL, which is required for theme activation. Please contact your hosting provider.', 'wolf-visual-composer' ),
			30
		);
		return;
	}

	// Sanitize purchase code
	$code = sanitize_text_field( wp_unslash( $_POST['theme_purchase_code'] ) );

	// Prepare API request
	$remote_url = 'https://api.wolfthemes.cloud/envato/';
	$response   = wp_safe_remote_post(
		$remote_url,
		array(
			'method'  => 'POST',
			'timeout' => 20,
			'body'    => array(
				'action'        => 'activation',
				'purchase_code' => $code,
				'site_domain'   => wvc_get_license_domain(),
			),
		)
	);

	// Handle WP errors
	if ( is_wp_error( $response ) ) {
		$error = $response->get_error_message();

		if ( strpos( $error, 'cURL' ) !== false ) {
			$error = esc_html__( 'The request failed due to a cURL error. This may be caused by firewall or DNS blocking. Please check with your hosting provider to ensure that outgoing requests to api.wolfthemes.cloud are allowed.', 'wolf-visual-composer' );
		}

		set_transient( 'wvc_activation_error', $error, 30 );
		return;
	}

	// Validate response
	if ( ! is_array( $response ) ) {
		set_transient(
			'wvc_activation_error',
			esc_html__( 'Unexpected error during the request. Please try again later.', 'wolf-visual-composer' ),
			30
		);
		return;
	}

	// Get response body
	$body = wp_remote_retrieve_body( $response );

	if ( empty( $body ) ) {
		set_transient(
			'wvc_activation_error',
			esc_html__( 'No response body received from the server. Please try again later.', 'wolf-visual-composer' ),
			30
		);
		return;
	}

	// Decode JSON response
	$data = json_decode( $body );

	// Check for API error response
	if ( isset( $data->error ) ) {
		set_transient(
			'wvc_activation_error',
			sprintf( esc_html__( 'Error: %s', 'wolf-visual-composer' ), $data->error ),
			30
		);
		return;
	}

	// Validate successful response
	if ( ! $data || ! is_object( $data ) || ! isset( $data->code ) || ! isset( $data->key ) ) {
		set_transient(
			'wvc_activation_error',
			esc_html__( 'Invalid or incomplete data received from the server. Please try again.', 'wolf-visual-composer' ),
			30
		);
		error_log( 'Activation Error: Invalid data. Raw response: ' . print_r( $body, true ) );
		return;
	}

	// Success! Save activation data
	wvc_store_license_data( $data );

	// Set success message transient
	set_transient( 'wvc_activation_success', true, 30 );

	// Redirect to license page
	wp_safe_redirect( admin_url( 'themes.php?page=' . wvc_get_theme_slug() . '-about#license' ) );
	exit;
}

/**
 * About Me tab
 */
function wvc_output_about_me_tab() {
	?>
	<a href="#about-me" class="nav-tab"><?php esc_html_e( 'About me', 'wolf-visual-composer' ); ?></a>
	<?php
}
add_action( 'wvc_about_me_tab', 'wvc_output_about_me_tab' );

/**
 * Theme about_me tab content
 */
function wvc_output_about_me_tab_content() {
	?>
	<div id="about-me" class="wvc-options-panel">
		<div class="about-me-text wolftheme-about-me-text">
			<div class="row wolftheme-about-columns">
				<div class="col col-12">
					<h3>About Me</h3>

<img style="float:right; padding-left:40px;" src="https://assets.wolfthemes.com/me.jpg" alt="WolfThemes avatar">

<p>Hi there! I'm Constantin, the creator behind WolfThemes. With over 12 years of experience in designing WordPress themes, I'm passionate about crafting stunning, modern websites that help creative professionals, musicians, and artists showcase their work.</p>

<p>At WolfThemes, we're all about helping you build beautiful, functional websites with ease. From drag-and-drop customization to seamless performance, my goal is to ensure every theme meets your needs while delivering a smooth user experience.</p>

<p>I'm truly grateful to have over 34,000 customers who trust my themes for their websites. Whether you're a band, musician, or part of a creative agency, it's an honor to be part of your journey.</p>

<p><strong>I wish you all the best with your project!</strong></p>

<p><img style="max-width:150px" src="https://assets.wolfthemes.com/logo-dark.png" alt="WolfThemes logo"></p>

<h3>Want to Help?</h3>

<p>If you love the theme and it's working well for you, please take a minute to leave a rating on <a href="https://themeforest.net/downloads" target="_blank">ThemeForest</a>. It would be greatly appreciated!</p>

<p>Thank you for being part of the WolfThemes family!</p>

<p><em>— Constantin</em></p>

<p>
	<a href="https://themeforest.net/downloads"  target="_blank">
		<img style="max-width:150px; margin:15px 0;" src="https://assets.wolfthemes.com/5-stars.png" alt="5-stars">
	</a>
</p>
<p><a class="button-primary" href="https://themeforest.net/downloads"  target="_blank">Leave a rating</a></p>

				</div>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'wvc_about_me_tab_content', 'wvc_output_about_me_tab_content' );

/**
 * License tab
 */
function wvc_output_license_tab() {
	?>
	<a href="#license" class="nav-tab"><?php esc_html_e( 'License', 'wolf-visual-composer' ); ?></a>
	<?php
}
add_action( 'wvc_license_tab', 'wvc_output_license_tab' );

/**
 * Theme license tab content
 */
function wvc_output_license_tab_content() {
	?>
	<div id="license" class="wvc-options-panel">
	<?php
		// Check if actually activated (has purchase code stored)
		$activated  = get_option( 'wvc_key' ) && get_option( 'wvc_code' );
		$theme_name = wvc_get_theme_name();
		$theme_slug = wvc_get_theme_slug();
	?>
		<ul class="wvc-license-info">
			<li>
			<?php
				printf(
					wp_kses_post( __( '%1$s theme works with <strong>%2$s</strong> plugin to offer all its features.', 'wolf-visual-composer' ) ),
					$theme_name,
					'Wolf WPBakery Page Builder Extension'
				);
			?>
			</li>
			<li>
				<?php
					printf(
						wp_kses_post( __( 'It extends of <a href="%1$s" target="_blank">%2$s</a> plugin.', 'wolf-visual-composer' ) ),
						'https://wlfthm.es/wpbpb',
						'WPBakery Page Builder'
					);
				?>
			</li>
			<li>
				<?php esc_html_e( 'It includes plugin territory features that boost the theme functionalities.', 'wolf-visual-composer' ); ?>
			</li>
			<li>
				<?php
				printf(
					wp_kses_post( __( 'This extension is available only to users who purchased their theme from <a href="%1$s" target="_blank">%2$s</a>.', 'wolf-visual-composer' ) ),
					'https://wolfthemes.com',
					'WolfThemes'
				);
				?>
			</li>
		</ul>
		<?php if ( ! $activated ) : ?>
		<p class="wvc-license-cta-text">
			<?php
				esc_html_e( 'Please enter your theme purchase code below to activate your theme and be able to use all features.', 'wolf-visual-composer' );
			?>
		</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'themes.php?page=' . $theme_slug . '-about#license' ) ); ?>">
			<?php wp_nonce_field( 'wvc_activate_theme', 'wvc_activation_nonce' ); ?>
			<input name="theme_purchase_code" placeholder="693e0017-48d3-4bd5-be47-1c5c14e7ab9c" type="text" class="regular-text wvc-license-input"><input value="<?php esc_html_e( 'Activate', 'wolf-visual-composer' ); ?>" type="submit" class="button button-primary wvc-license-button">
		</form>
		<p>
			<a target="_blank" href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Can-I-Find-my-Purchase-Code-"><?php esc_html_e( 'How to find your purchase code', 'wolf-visual-composer' ); ?></a>
		</p>
		<?php else : ?>
		<p>

			<?php
			printf(
				wp_kses_post( __( 'The %s Extension is activated.', 'wolf-visual-composer' ) ),
				'WPBakery Page Buidler Extension'
			);

			$support_end_date = get_option( 'wvc_supported_until' );

			if ( $support_end_date ) {
				echo '<br>';
				echo '<strong>';

				if ( wvc_support_expired() ) {
					echo wp_sprintf(
						wp_kses_post( __( 'Your support for %1$s has expired. You can renew it <a target="_blank" href="%2$s">HERE</a>.', 'wolf-visual-composer' ) ),
						esc_html( wvc_get_theme_name() ),
						esc_url( 'https://themeforest.net/downloads' )
					);
				} else {
					$support_end_timestamp = strtotime( $support_end_date );
					$formatted_date        = date( 'F j, Y', $support_end_timestamp );

					echo wp_sprintf(
						wp_kses_post( __( 'Your support for %1$s is valid until <span style="color: #28a745;">%2$s</span>.', 'wolf-visual-composer' ) ),
						esc_html( wvc_get_theme_name() ),
						esc_html( $formatted_date )
					);
				}

				echo '</strong>';
			}
			?>
		</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'themes.php?page=' . $theme_slug . '-about#license' ) ); ?>">
			<?php wp_nonce_field( 'wvc_reset_license_theme', 'wvc_reset_license_nonce' ); ?>
			<input name="wvc_reset_purchase_code" value="<?php esc_html_e( 'Reset purchase code', 'wolf-visual-composer' ); ?>" type="submit" class="button button-secondary">
		</form>
			<?php
		endif;

		?>
	</div><!-- # -->
	<?php
}
add_action( 'wvc_license_tab_content', 'wvc_output_license_tab_content' );

/**
 * Display activation messages
 */
function wvc_display_activation_messages() {
	// Only show on the theme about page
	if ( ! isset( $_GET['page'] ) || strpos( $_GET['page'], '-about' ) === false ) {
		return;
	}

	// Success message
	if ( get_transient( 'wvc_activation_success' ) ) {
		delete_transient( 'wvc_activation_success' );
		?>
		<div class="notice notice-success is-dismissible wolf-visual-composer-activation-success">
			<p><?php esc_html_e( 'Extension activated successfully!', 'wolf-visual-composer' ); ?></p>
		</div>
		<?php
	}

	// Error message
	$error = get_transient( 'wvc_activation_error' );
	if ( $error ) {
		delete_transient( 'wvc_activation_error' );
		?>
		<div class="notice notice-error is-dismissible wolf-visual-composer-activation-error">
			<p><?php echo esc_html( $error ); ?></p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wvc_display_activation_messages' );