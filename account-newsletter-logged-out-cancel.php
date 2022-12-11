<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="u-columns col2-set">
	<?php
	$newsletter = NewsletterSubscription::instance();
	$user = $newsletter->get_user_from_request(false);
	$email = $newsletter->get_email_from_request(false);
	$language = $newsletter->get_user_language($user);
	$options = $newsletter->get_options('profile', $language);
	?>
	<div class="u-column1 col-1">
		<h3 class="entry-title"><?php esc_html_e('Email Subscription', 'the newsletter plugin'); ?></h3>
		<?php switch ($_GET['nm']) {
			case 'unsubscription':
			case 'unsubscribed':
			?>
				<form class="woocommerce-form woocommerce-form-register register" action="<?php echo NewsletterUnsubscription::instance()->build_action_url('reactivate', $user, $email);?>" method="post">
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						Your subscription has been deleted.  If that was an error, you subscribe again below.
					</p>
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="reg_email">Email address&nbsp;<span class="required">*</span></label>
						<input id="reg_email" type="email" class="woocommerce-Input woocommerce-Input--text input-text tnp-email" name="ne" autocomplete="email"  value="<?php echo esc_attr($user->email);?>" readonly>
					</p>
					<p class="woocommerce-form-row form-row">
						<button type="submit" class="woocommerce-button button wp-element-button"  value="Cancel">Reactivate</button>
					</p>
				</form>
				<?php break;
			case 'unsubscribe':
			?>
			<form class="woocommerce-form woocommerce-form-register register" action="<?php echo NewsletterUnsubscription::instance()->build_action_url('uc', $user, $email);?>" method="post">
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					Please confirm you want to unsubscribe by clicking below
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_email">Email address&nbsp;<span class="required">*</span></label>
					<input id="reg_email" type="email" class="woocommerce-Input woocommerce-Input--text input-text tnp-email" name="ne" autocomplete="email"  value="<?php echo esc_attr($user->email);?>" readonly>
				</p>
				<p class="woocommerce-form-row form-row">
					<button type="submit" class="woocommerce-button button wp-element-button"  value="Cancel">Unsubscribe</button>
				</p>
				<?php break;
			default: 
				printf(__FILE__,__LINE__," Unrecognized nm %s\n", $_GET['nm']);
		}?>
	</div>
</div>
