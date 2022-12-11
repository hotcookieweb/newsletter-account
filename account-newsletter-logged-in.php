<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Email subscriptions
 *
 * Shows email subscriptions on the account page.
 *
 */
$newsletter = NewsletterSubscription::instance();
$user = $newsletter->get_user_from_request(false);
$email = $newsletter->get_email_from_request(false);
$language = $newsletter->get_user_language($user);
$options = $newsletter->get_options('profile', $language);
$user_logged_in = $newsletter->get_user_by_wp_user_id(get_current_user_id());
$local_options = NewsletterProfile::instance()->get_options('', $language);
?>
<div class="wrap">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<div class="entry-content">
      	<div>
				<h2>Notice</h2>
				<?php
				if ($user != $user_logged_in) { ?>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					You reached email subscriptions through a newsletter.  The newsletter was sent to email address: <?php echo esc_attr($user->email);?>
				</p>
				<?php } ?>
				<h2>Email subscription preferences</h2>
					<div class="tnp tnp-profile">
						<form action="<?php echo $newsletter->build_action_url('ps');?>" method="post">
							Choose from the following email lists:
							<?php // Lists
							$lists = $newsletter->get_lists_for_profile();
							foreach ($lists as $list) {
								$field = 'list_' . $list->id;
								$checked = ($user->$field == 1) ? ' checked' : ''?>
								<ul class="tnp-field tnp-field-list" style="margin-left: 20px">
									<label>
										<input class="tnp-list tnp-list-<?php echo $list->id; ?>" type="checkbox" name="nl[]" value="<?php echo $list->id ?>" <?php echo $checked;?>>
										<span class="tnp-list-label"><?php echo esc_html($list->name)?></span>
									</label>
								</ul>
							<?php } ?>
							<div class="tnp-field tnp-field-button">
								<input type="hidden" name="nk" value="<?php echo esc_attr($user->id . '-' . $user->token);?>">
								<input type="hidden" class="tnp-email" type="text" name="ne" required value="<?php echo esc_attr($user->email);?>">
								<button class="woocommerce-Button button wp-element-button tnp-submit" type="submit" value="<?php echo esc_attr($local_options['save_label'])?>"><?php echo esc_attr($local_options['save_label'])?></button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- .wrap -->
