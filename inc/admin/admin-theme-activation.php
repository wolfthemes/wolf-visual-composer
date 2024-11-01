<?php
/**
 * WPBakery Page Builder Extension admin theme activation
 *
 * Functions available on admin
 *
 * @author WolfThemes
 * @category Core
 * @package WolfWPBakeryPageBuilderExtension/Admin
 * @version 3.2.8
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get theme name
 */
function wvc_get_theme_name() {
	$wp_theme = wp_get_theme( get_template() );
	return $wp_theme->Name;
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
	<div id="about-me" class="wolf-core-options-panel">
		<div class="about-me-text wolftheme-about-me-text">
			<div class="row wolftheme-about-columns">
				<div class="col col-12">
					<h3>About Me</h3>

<img style="float:right; padding-left:40px;" src="https://assets.wolfthemes.com/me.jpg" alt="WolfThemes avatar">

<p>Hi there! I’m Constantin, the creator behind WolfThemes. With over 12 years of experience in designing WordPress themes, I’m passionate about crafting stunning, modern websites that help creative professionals, musicians, and artists showcase their work.</p>

<p>At WolfThemes, we’re all about helping you build beautiful, functional websites with ease. From drag-and-drop customization to seamless performance, my goal is to ensure every theme meets your needs while delivering a smooth user experience.</p>

<p>I'm truly grateful to have over 34,000 customers who trust my themes for their websites. Whether you're a band, musician, or part of a creative agency, it’s an honor to be part of your journey.</p>

<p><strong>I wish you all the best with your project!</strong></p>

<p><img style="max-width:150px" src="https://assets.wolfthemes.com/logo-dark.png" alt="WolfThemes logo"></p>

<h3>Want to Help?</h3>

<p>If you love the theme and it’s working well for you, please take a minute to leave a rating on <a href="https://themeforest.net/downloads" target="_blank">ThemeForest</a>. It would be greatly appreciated! 😉</p>

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
 * Theme license tab
 */
function wvc_output_license_tab() {
	?>
	<a href="#license" class="nav-tab"><?php esc_html_e( 'License', 'wolf-visual-composer' ); ?></a>
	<?php
}
add_action( 'wvc_license_tab', 'wvc_output_license_tab' );

/**
 * Theme license tab
 */
function wvc_output_license_tab_content() {
	?>
	<?php
	if ( isset( $_POST['wvc_reset_purchase_code'] ) ) :
		delete_option( 'wvc_activation_notice_set' );
		delete_transient( 'wvc_activation_notice' );
		delete_option( 'wvc_activated' );
		delete_option( 'wvc_activation_time' );
		delete_option( 'wvc_code' );
		delete_option( 'wvc_key' );
		endif;
	?>
	<div id="license" class="wvc-options-panel">
	<?php
		$activated  = wvc_activate_theme();
		$theme_name = wvc_get_theme_name();
		$theme_slug = wvc_get_theme_slug();
	?>
		<ul class="wvc-license-info">
			<li>
			<?php
				echo sprintf(
					wp_kses_post( __( '%1$s theme works with <strong>%2$s</strong> plugin to offer all its features.', 'wolf-visual-composer' ) ),
					$theme_name,
					// 'https://wolfthemes.com/wolf-wpbakery-page-builder-extension/',
					'Wolf WPBakery Page Builder Extension'
				);
			?>
			</li>
			<li>
				<?php
				echo sprintf(
					wp_kses_post( __( 'This is an extension of <a href="%1$s" target="_blank">%2$s</a> plugin.', 'wolf-visual-composer' ) ),
					'https://wlfthm.es/wpbpb',
					'WPBakery Page Builder'
				);
				?>
			</li>
			<li>
				<?php
				echo sprintf(
					wp_kses_post( __( 'This extension is available only to users who purchased their theme from <a href="%1$s" target="_blank">%2$s</a>.', 'wolf-visual-composer' ) ),
					'https://wolfthemes.com',
					'WolfThemes'
				);
				?>
			</li>
			<li>
			<?php
				echo sprintf(
					wp_kses_post( __( 'You <strong>do not need to activate %1$s</strong> as the full version is already included in the theme (<a href="%2$s" target="_blank">more infos</a>).', 'wolf-visual-composer' ) ),
					'WPBakery Page Builder',
					'https://wolfthemes.ticksy.com/article/12629/'
				);
			?>
			</li>
		</ul>
		<?php if ( ! $activated ) : ?>
		<p class="wvc-license-cta-text">
			<?php
				echo sprintf(
					wp_kses_post( __( 'Please enter your <strong>theme purchase code</strong> below to activate your theme license and be able to use all features.', 'wolf-visual-composer' ) ),
					'WolfThemes'
				);
			?>
		</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'themes.php?page=' . $theme_slug . '-about' ) ); ?>">
		<input name="theme_purchase_code" placeholder="693e0017-48d3-4bd5-be47-1c5c14e7ab9c" type="text" class="regular-text wvc-license-input"><input value="<?php esc_html_e( 'Activate', 'wolf-visual-composer' ); ?>" type="submit" class="button button-primary wvc-license-button">
		</form>
		<p>
			<a target="_blank" href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Can-I-Find-my-Purchase-Code-"><?php esc_html_e( 'How to find your purchase code', 'wolf-visual-composer' ); ?></a>
		</p>
		<?php else : ?>
		<p>
			<?php
			echo sprintf(
				wp_kses_post( __( 'The %s is activated.', 'wolf-visual-composer' ) ),
				'WPBakery Page Builder Extension'
			);

			$support_end_date = get_option( 'wvc_supported_until' );

			if ( $support_end_date ) {
				echo '<br>';
				echo '<strong>';

				if ( wvc_support_expired() ) {
					// If support has expired, show the renewal message
					echo wp_sprintf(
						wp_kses_post( __( 'Your support for %s has expired. You can renew it <a target="_blank" href="%s">HERE</a>.', 'wolf-visual-composer' ) ),
						esc_html( wvc_get_theme_name() ),
						esc_url( 'https://themeforest.net/downloads' )
					);
				} else {
					// Convert the ISO 8601 formatted date to a timestamp
					$support_end_timestamp = strtotime( $support_end_date );
					// Format the timestamp into a more readable date
					$formatted_date = date( 'F j, Y', $support_end_timestamp ); // E.g., December 8, 2024

					// Show the valid support message with the expiration date
					echo wp_sprintf(
						wp_kses_post( __( 'Your support for %s is valid until <span style="color: #28a745;">%s</span>.', 'wolf-visual-composer' ) ),
						esc_html( wvc_get_theme_name() ),
						esc_html( $formatted_date )
					);
				}

				echo '</strong>';
			}
			?>
		</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'themes.php?page=' . $theme_slug . '-about' ) ); ?>"><input name="wvc_reset_purchase_code" value="<?php esc_html_e( 'Reset purchase code', 'wolf-visual-composer' ); ?>" type="submit" class="button button-secondary">
			</form>
			<?php
		endif;

		?>
	</div><!-- #license -->
	<?php
}
add_action( 'wvc_license_tab_content', 'wvc_output_license_tab_content' );

/**
 * Activate the theme
 */
function wvc_activate_theme() {

	$activated     = get_option( 'wvc_key' );
	$is_error      = false;
	$error_message = esc_html__( 'Something went wrong. It may be due to a temporary Envato API outage. Please try again in a few minutes.', 'wolf-visual-composer' );
	$error         = '';

	// Check if cURL is enabled on the server
	if ( ! function_exists( 'curl_init' ) ) {
		$is_error = true;
		$error = esc_html__( 'The server does not support cURL, which is required for theme activation. Please contact your hosting provider.', 'wolf-visual-composer' );
	}

	// Check if the theme is not already activated
	if ( ! $activated && ! $is_error ) {

		/* Verify purchase */
		if ( ! empty( $_POST['theme_purchase_code'] ) ) {

			$code       = esc_attr( $_POST['theme_purchase_code'] );
			$remote_url = 'https://api.wolfthemes.com/envato/';
			$url        = $remote_url . '?code=' . $code;

			// Send request
			$response = wp_safe_remote_post(
				$url,
				array(
					'method' => 'POST',
					'body'   => array(
						'timeout'        => 30,
						'action'         => 'activation',
						'purchase_code'  => $code,
					),
				)
			);

			// Check for WP errors first
			if ( is_wp_error( $response ) ) {
				$is_error = true;
				$error    = $response->get_error_message();

				// Handle specific cURL errors
				if ( strpos( $error, 'cURL' ) !== false ) {
					$error = esc_html__( 'The request failed due to a cURL error. This may be caused by firewall or DNS blocking. Please check with your hosting provider to ensure that outgoing requests to api.wolfthemes.com are allowed.', 'wolf-visual-composer' );
				}

			} elseif ( is_array( $response ) ) {

				// Retrieve the response body
				$body = wp_remote_retrieve_body( $response );

				// Check if the body is empty
				if ( ! $body ) {
					$is_error = true;
					$error    = esc_html__( 'No response body received from the server. Please try again later.', 'wolf-visual-composer' );
				} else {
					// Decode the JSON response
					$data = json_decode( $body );

					// Validate the response data
					if ( $data && is_object( $data ) && isset( $data->code ) && isset( $data->key ) ) {

						// Save activation details
						update_option( 'wvc_activated', true );
						update_option( 'wvc_activation_time', time() );
						update_option( 'wvc_supported_until', $data->supported_until );
						add_option( 'wvc_code', $data->code );
						add_option( 'wvc_key', $data->key );
						delete_transient( 'wvc_activation_notice' );
						$activated = true;

						// Display success message
						echo '<div class="wvc-activation-success wvc-notice-warning wvc-admin-notice">';
						echo '<p>' . esc_html__( 'Extension activated', 'wolf-visual-composer' ) . '</p>';
						echo '</div>';

						// Redirect after activation
						wp_safe_redirect( admin_url( 'themes.php?page=' . wvc_get_theme_slug() . '-about' ) );
						exit;

					} elseif ( isset( $data->error ) ) {
						$is_error = true;
						$error    = esc_html__( 'Error: ' . $data->error, 'wolf-visual-composer' );
					} else {
						$is_error = true;
						$error    = esc_html__( 'Invalid or incomplete data received from the server. Please try again.', 'wolf-visual-composer' );
						// Log error with raw response body for further debugging
						error_log( 'Activation Error: Invalid or incomplete data. Raw response: ' . print_r( $body, true ) );
					}
				}
			} else {
				$is_error = true;
				$error    = esc_html__( 'Unexpected error during the request. Please try again later.', 'wolf-visual-composer' );
			}
		} else {
			$is_error = true;
			$error    = esc_html__( 'Purchase code cannot be empty', 'wolf-visual-composer' );
		}
	} elseif ( $activated ) {
		return true;
	}

	// Display error message if there was an error
	if ( $is_error && $error && isset( $_POST['theme_purchase_code'] ) ) {
		echo '<div class="wvc-activation-error wvc-notice-warning wvc-admin-notice">';
		echo '<p>' . esc_attr( $error ) . '</p>';
		echo '</div>';
	}

	return $activated;
}

function wvc_get_transient_timeout( $transient ) {
	global $wpdb;
		$transient_timeout = $wpdb->get_col(
			"
		SELECT option_value
		FROM $wpdb->options
		WHERE option_name
		LIKE '%_transient_timeout_$transient%'
		"
		);
	return ( isset( $transient_timeout[0] ) ) ? absint( ( $transient_timeout[0] - time() ) / DAY_IN_SECONDS ) : false;
}
