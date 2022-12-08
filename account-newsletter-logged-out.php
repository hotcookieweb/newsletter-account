<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="u-columns col2-set">
	<div id="primary" class="u-column1 col-1">
		<h3 class="entry-title"><?php esc_html_e('Unsubscribe from Email List', 'the newsletter plugin'); ?></h3>
		<?php
		$newsletter = NewsletterSubscription::instance();
		$user = $newsletter->get_user_from_request(false);
		$language = $newsletter->get_user_language($user);
		$options = $newsletter->get_options('profile', $language);
		?>
		<div class="tnp tnp-profile">
			<form action="<?php echo $newsletter->build_action_url('ps');?>" method="post">
				<input type="hidden" name="nk" value="<?php echo esc_attr($user->id . '-' . $user->token);?>">
				<div class="tnp-field tnp-field-email">
					<input type="hidden" class="tnp-email" type="text" name="ne" required value="<?php echo esc_attr($user->email);?>">
				</div>
				<?php
				// Lists
				$lists = $newsletter->get_lists_for_profile();
				foreach ($lists as $list) {
					$field = 'list_' . $list->id;
					$checked = ($user->$field == 1) ? ' checked' : ''?>
					<div class="tnp-field tnp-field-list">
					<label>
						<input class="tnp-list tnp-list-<?php echo $list->id; ?>" type="checkbox" name="nl[]" value="<?php echo $list->id ?>" <?php echo $checked;?>>
						<span class="tnp-list-label"><?php echo esc_html($list->name)?></span>
					</label>
				</div>
				<?php }
				$local_options = NewsletterProfile::instance()->get_options('', $language);
				?>
				<div class="tnp-field tnp-field-button">
					<input class="tnp-submit" type="submit" value="<?php echo esc_attr($local_options['save_label'])?>">
				</div>
			</form>
		</div>
	</div>
</div>
