<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   WP Sticky Social
 * @author    Vladislav Musilek
 * @license   GPL-2.0+
 * @copyright 2013 Vladislav Musilek
 */
 
$social_profiles = array(
        'facebook'    =>'Facebook',
        'twitter'     =>'Twitter',
        'pinterest'   =>'Pinterest',
        'github'      =>'Github',
        'linkedin'    =>'Linkedin',
        'dribble'     =>'Dribble',
        'stumble_upon'=>'Stumble Upon',
        'behance'     =>'Behance',
        'reddit'      =>'Reddit',
        'google_plus' =>'Google plus',
        'youtube'     =>'Youtube',
        'vimeo'       =>'Vimeo',
        'clickr'      =>'Flickr',
        'slideshare'  =>'Slideshare',
        'picassa'     =>'Picasa',
        'skype'       =>'Skype',
        'instagram'   =>'Instagram',
        'foursquare'  =>'Foursquare',
        'delicious'   =>'Delicious',
        'tumblr'      =>'Tumblr',
        'digg'        =>'Digg',
        'wordpress'   =>'Wordpress'
           
    ); 
    
if(	!empty(	$_POST['sticky-social-setting'] ) ) {

	if ( isset( $_POST['sticky_social_nonce'] ) && wp_verify_nonce( $_POST['sticky_social_nonce'], 'wp_sticky_social_save_form' ) ) {

	$setting = array();

	foreach( $social_profiles as $key => $item ){ 
  		$setting[$key.'_link']   = sanitize_text_field( $_POST[$key.'_link'] );
  		$setting[$key.'_active'] = sanitize_text_field( $_POST[$key.'_active'] );
	} 
  	$setting['sticky_social_top_margin'] 			= sanitize_text_field( $_POST['sticky_social_top_margin'] );
  	$setting['sticky_social_background_color'] 		= sanitize_text_field( $_POST['sticky_social_background_color'] );
  	$setting['sticky_social_text_color'] 			= sanitize_text_field( $_POST['sticky_social_text_color'] );
  	$setting['sticky_social_icon_color'] 			= sanitize_text_field( $_POST['sticky_social_icon_color'] );
  	$setting['sticky_social_position'] 				= sanitize_text_field( $_POST['sticky_social_position'] );
  	$setting['sticky_social_margin'] 				= sanitize_text_field( $_POST['sticky_social_margin'] );
  	$setting['sticky_social_target'] 				= sanitize_text_field( $_POST['sticky_social_target'] );
  
	update_option( 'wp-sticky-social', $setting );

	}

	wp_redirect(home_url().'/wp-admin/options-general.php?page=wp-sticky-social');

}    
 
$option = get_option( 'wp-sticky-social' );
$nonce  = wp_create_nonce( 'wp_sticky_social_save_form' );
?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
  	<form method="post" action="">
		<div class="col_full">
    		<div class="col_half">
      			<p class="split-title"><?php esc_html_e( 'Social profil setting', 'wp-sticky-social' ); ?></p>
      			<table class="table-form">
      			<?php foreach( $social_profiles as $key => $item ){ ?>
      				<tr>                          
        				<td>
          					<label for="<?php echo $key.'_link'; ?>"><strong><?php echo $item; ?></strong></label>
        				</td>
        				<td>
          					<input type="text" name="<?php echo $key.'_link'; ?>" value="<?php echo $option[$key.'_link'] ?>" />
        				</td>
        				<td>
          					<label for="<?php echo $key.'_active'; ?>"><?php esc_html_e('Active', 'wp-sticky-social'); ?></label>
        				</td>
        				<td>  
          					<input type="checkbox" name="<?php echo $key.'_active'; ?>"<?php if($option[$key.'_active']=='on'){ echo 'checked="checked"'; } ?> />
        				</td>  
      				</tr>
      			<?php } ?>      
      			</table>
    		</div>
    		<div class="col_half">
      			<p class="split-title"><?php esc_html_e('Style setting','wp-sticky-social'); ?></p>
      			<table class="table-form">
					<tr>
						<td><label for="sticky_social_top_margin"><?php esc_html_e('Top margin', 'wp-sticky-social'); ?></label></td>
						<td><input type="number" name="sticky_social_top_margin" value="<?php echo $option['sticky_social_top_margin']; ?>" /></td>
					</tr>
					<tr>
						<td><label for="sticky_social_margin"><?php esc_html_e('Offset margin', 'wp-sticky-social'); ?></label></td>
						<td><input type="number" name="sticky_social_margin" value="<?php echo $option['sticky_social_margin']; ?>" /></td>
					</tr>
					<tr>
						<td><label for="sticky_social_background_color"><?php esc_html_e('Background color', 'wp-sticky-social'); ?></label></td>
						<td><input type="text" name="sticky_social_background_color" id="sticky_social_background_color" value="<?php echo $option['sticky_social_background_color']; ?>" /></td>
					</tr>
					<tr>
						<td><label for="sticky_social_text_color"><?php esc_html_e('Text color', 'wp-sticky-social'); ?></label></td>
						<td><input type="text" name="sticky_social_text_color" id="sticky_social_text_color" value="<?php echo $option['sticky_social_text_color']; ?>" /></td>
					</tr>
					<tr>
						<td><label for="sticky_social_icon_color"><?php esc_html_e('Icon color', 'wp-sticky-social'); ?></label></td>
						<td><input type="text" name="sticky_social_icon_color" id="sticky_social_icon_color" value="<?php echo $option['sticky_social_icon_color']; ?>" /></td>
					</tr>
					<tr>
						<td>
							<label for="sticky_social_position"><?php esc_html_e('Left side position', 'wp-sticky-social'); ?></label>
							<input type="radio" name="sticky_social_position" value="left" <?php  if($option['sticky_social_position']=='left'){ echo 'checked="checked"'; } ?> />
						</td>
						<td>
							<label for="sticky_social_position"><?php esc_html_e('Right side position', 'wp-sticky-social'); ?></label>
							<input type="radio" name="sticky_social_position" value="right" <?php  if($option['sticky_social_position']=='right'){ echo 'checked="checked"'; } ?> />
						</td>
					</tr>
					<tr>
          				<td>
            				<label for="sticky_social_target"><?php esc_html_e('Open in next window', 'wp-sticky-social'); ?></label>
          				</td>
          				<td>  
            				<input type="checkbox" name="sticky_social_target"<?php if($option['sticky_social_target']=='on'){ echo 'checked="checked"'; } ?> />
          				</td>
        			</tr>        
      			</table>
    		</div>
  		</div>
  		<?php wp_nonce_field( 'wp_sticky_social_save_form', 'sticky_social_nonce' ); ?>
  		<input type="hidden" name="sticky-social-setting" value="ok" />
  		<input type="submit" class="button-primary" name="save-setting" id="save-setting" value="<?php esc_html_e('Save setting', 'wp-sticky-social'); ?>" />
  	</form>

</div>
