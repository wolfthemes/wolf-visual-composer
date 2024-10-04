<?php
/**
 * Admin notices
 *
 * @author WolfThemes
 * @category Admin
 * @package WolfCore/Admin
 * @version 1.9.9
 */

defined( 'ABSPATH' ) || exit;


add_action( 'admin_init', function() {
	wvc_dismiss_review_notification();
} );

/**
 * Output inviting message to rate the theme
 */
function wvc_rating_request_admin_notice() {

	//delete_option( 'wvc_theme_review_dismissed_date' );
	//delete_option( 'wvc_theme_review_dismissed_permanently' );

	//debug( get_option( 'wvc_activation_time' ) );
	//debug( 'wvc_theme_review_dismissed_permanently ' . get_option( "wvc_theme_review_dismissed_permanently" ) );

	if ( ! wvc_rating_notif_needed() ) {
		return;
	}

	if ( ! wvc_should_show_review_notification() ) {
		return;
	}

	$wp_theme   = wp_get_theme( get_template() );
	$theme_name = $wp_theme->Name;
	$review_link = 'https://themeforest.net/downloads';

	$message = wp_sprintf( '<p class="wvc-admin-notice-img"><img src="%1$s" alt="WolfThemes avatar"></p>
	<p class="wvc-admin-notice-title"><strong>Enjoying %2$s? I’d Love Your Feedback!</strong></p>
        <p>Thank you for using %2$s. If it’s been helpful for your project, I’d really appreciate it if you could take a moment to leave a review.
		It will help me a ton!</p>
		<p class="wvc-admin-notice-cite">&mdash; Constantin from WolfThemes</p>
        <p><a href="%3$s" class="button-primary" target="_blank">Leave a Review</a> &nbsp; <a href="%4$s" class="button-secondary">Remind me later</a></p>
    	<p><em>Not now? No problem! You can dismiss this message anytime.</em><p>
		<p><a href="%5$s" class="button-link">Hide permanently</a></p>
		',
		esc_html( 'https://assets.wolfthemes.com/me.jpg' ),
		esc_html( $theme_name ), // %2$s - Theme name
		esc_url( $review_link ),   // %3$s - Review link
		esc_url( add_query_arg( 'wvc_dismiss_review_notification', '1' ) ), // %4$s - Dismiss link
		esc_url( add_query_arg( 'wvc_dismiss_review_notification', '2' ) ) // %5$s - Dismiss link
	);

	wvc_admin_notice( $message, 'success' );
}
add_action( 'admin_init', 'wvc_rating_request_admin_notice' );

/**
 * Dismiss notif
 *
 * @return void
 */
function wvc_dismiss_review_notification() {
    if ( isset( $_GET['wvc_dismiss_review_notification'] ) ) {
        $dismiss_type = absint( $_GET['wvc_dismiss_review_notification'] );

        if ( $dismiss_type === 1 ) {
            // Temporarily dismiss for 30 days
            update_option( 'wvc_theme_review_dismissed_date', time() );
        } elseif ( $dismiss_type === 2 ) {
            // Permanently dismiss
            update_option( 'wvc_theme_review_dismissed_permanently', true );
        }
    }
}


/**
 * Undocumented function
 *
 * @return void
 */
function wvc_should_show_review_notification() {
    // Check if the user permanently dismissed the notification
    $dismissed_permanently = get_option( 'wvc_theme_review_dismissed_permanently' );

    if ( $dismissed_permanently ) {
        return false; // Don't show the notification again
    }

    // Check if the notification has been dismissed temporarily and when
    $dismissed_date = get_option( 'wvc_theme_review_dismissed_date' );

    // If it was dismissed, check if 15 days have passed since
    if ( $dismissed_date ) {
        $thirty_days_in_seconds = 15 * 24 * 60 * 60;
        if ( ( time() - $dismissed_date ) < $thirty_days_in_seconds ) {
            return false; // Less than 15 days have passed
        }
    }

    return true; // Show the notification
}

/**
 * Custom admin notice
 *
 * @param string $message the message string.
 * @param string $type error|warning|info|success.
 * @param string $cookie_id if set a cookie will be use to hide the notice permanently.
 * @param string $dismiss_text dismiss message text.
 */
function wvc_admin_notice( $message = null, $type = null, $cookie_id = null, $dismiss_text = null ) {

	if ( ! $message || defined( 'DOING_AJAX' ) ) {
		return;
	}

	$is_dismissible = ( 'error' === $message ) ? '' : 'is-dismissible';

	if ( $cookie_id ) {

		if ( ! $dismiss_text ) {
			$dismiss_text = esc_html__( 'Hide permanently', 'wolf-visual-composer' );
		}

		if ( $cookie_id ) {
			if ( ! isset( $_COOKIE[ $cookie_id ] ) ) {
				$href = esc_url( admin_url( 'themes.php?page=' . wvc_get_theme_slug() . '-about&amp;dismiss=' . $cookie_id ) );
				echo wvc_kses( "<div class='notice notice-$type $is_dismissible wvc-admin-notice'><p>$message</p><p><a href='$href' id='$cookie_id' class='button wolf_core-dismiss-admin-notice'>$dismiss_text</a></p></div>" ); // WCS XSS ok.
			}
		}
	} else {
		echo wvc_kses( "<div class='notice notice-$type $is_dismissible wvc-admin-notice'><p>$message</p></div>" ); // phpcs:ignore
	}
	return false;
}
add_action( 'admin_notices', 'wvc_admin_notice' );

/**
 * Activated since 35 days
 */
function wvc_rating_notif_needed() {

	return wvc_is_35_days_after_activation();
}

function wvc_is_35_days_after_activation() {
	$activation_date = get_option( 'wvc_activation_time' );

    if ( $activation_date ) {
        // Get the current timestamp
        $current_time = time();

        // Calculate the difference (35 days in seconds = 30 * 24 * 60 * 60)
        $thirty_days_in_seconds = 35 * DAY_IN_SECONDS;

        // Check if 30 days have passed
        if ( ( $current_time - $activation_date ) >= $thirty_days_in_seconds ) {
            return true; // 35 days have passed
        }
    }
	//return true; // debug
    return false; // Not yet 35 days
}
