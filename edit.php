<?php
function custom_user_profile_fields( $profileuser ) {
	$hs_user_details = esc_attr( get_the_author_meta( 'hs_user_details', $profileuser->ID ) );
	$hs_user_details = json_decode($hs_user_details);
	$loc = $hs_user_details["location"];
	$loc1= $hs_user_details->location1;
?>
	<h2>Haysky User Fields</h2>
	<table class="form-table">
		<tr>
			<th>
				<label for="user_location"><?php esc_html_e( 'Location' ); ?></label>
			</th>
			<td>
				<input type="text" name="user_location" id="user_location" value="<?php echo $loc ?>" class="regular-text" />
				<br><span class="description"><?php esc_html_e( 'Your location.', 'text-domain' ); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="user_location1"><?php esc_html_e( 'Location1' ); ?></label>
			</th>
			<td>
				<input type="text" name="user_location1" id="user_location1" value="<?php echo $loc1 ?>" class="regular-text" />
				<br><span class="description"><?php esc_html_e( 'Your location.', 'text-domain' ); ?></span>
			</td>
		</tr>
	</table>
<?php
}
add_action( 'show_user_profile', 'custom_user_profile_fields', 10, 1 );
add_action( 'edit_user_profile', 'custom_user_profile_fields', 10, 1 );

 add_action('edit_user_profile_update', 'update_extra_profile_fields');
 
 function update_extra_profile_fields($user_id) {
     if ( current_user_can('edit_user',$user_id) )
     	$hs_user_details['location'] = $_POST["user_location"];
     	$hs_user_details['location1'] = $_POST["user_location1"];
     	$hs_user_details = json_encode($hs_user_details);
		update_user_meta($user_id, 'hs_user_details', $hs_user_details);
 }
?>