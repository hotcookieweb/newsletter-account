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
		<h3 class="entry-title"><?php esc_html_e('Update Email Subscription', 'the newsletter plugin'); ?></h3>
		<form class="woocommerce-form woocommerce-form-register register" action="<?php echo $newsletter->build_action_url('ps');?>" method="post">
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_email">Email address&nbsp;<span class="required">*</span></label>
				<input id="reg_email" type="email" class="woocommerce-Input woocommerce-Input--text input-text tnp-email" name="ne" autocomplete="email"  value="<?php echo esc_attr($user->email);?>" required>
				<label for="reg_email">A confirmation email will be sent if email address is changed.</label>
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="email_list">Email lists&nbsp;<span class="required">*&nbsp;</span>
			<?php // Lists
			$lists = $newsletter->get_lists_for_profile($language);
			foreach ($lists as $list) {
				$field = 'list_' . $list->id;
				$checked = ($user->$field == 1) ? ' checked' : ''?>
						<input id="email_list" class="input-checkbox tnp-list tnp-list-<?php echo $list->id; ?>" type="checkbox" name="nl[]" value="<?php echo $list->id ?>" <?php echo $checked;?>>
						<span class="tnp-list-label"><?php echo esc_html($list->name). '&nbsp;'?></span>
				<?php }
			$local_options = NewsletterProfile::instance()->get_options('', $language);
			?>
				</label>
			</p>
			<p class="woocommerce-form-rowform-row">
				<input type="hidden" name="nk" value="<?php echo esc_attr($user->id . '-' . $user->token);?>">
				<button type="submit" class="woocommerce-button button wp-element-button"  value="<?php echo esc_attr($local_options['save_label'])?>">Update</button>
			</p>
		</form>
	</div>
	<div class="u-column2 col-2">
		<h3 class="entry-title"><?php esc_html_e('Cancel Email Subscription', 'the newsletter plugin'); ?></h3>
		<form class="woocommerce-form woocommerce-form-register register" action="<?php echo NewsletterUnsubscription::instance()->build_action_url('uc', $user, $email);?>" method="post">
			<?php switch ($_GET['nm']) {
				case 'reactivated': ?>
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						Your subscription has been reactivated.
					</p>
				<?php
				case 'confirmed':
				case 'confirmation':
				case 'profile':
					break;
				default:
					printf(__FILE__,__LINE__," Unrecognized nm %s\n", $_GET['nm']);
			} ?>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_email">Email address&nbsp;<span class="required">*</span></label>
				<input id="reg_email" type="email" class="woocommerce-Input woocommerce-Input--text input-text tnp-email" name="ne" autocomplete="email"  value="<?php echo esc_attr($user->email);?>" readonly>
			</p>
			<p class="woocommerce-form-row form-row">
				<button type="submit" class="woocommerce-button button wp-element-button"  value="Cancel">Cancel</button>
			</p>
		</form>
	</div>
</div>
