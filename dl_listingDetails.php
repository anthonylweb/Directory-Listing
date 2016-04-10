<?php

class Listing_Details_Meta_Box{
	
		public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}

	public function init_metabox() {

		add_action( 'add_meta_boxes', array( $this, 'create_details_meta_box'  )        );
		add_action( 'save_post',      array( $this, 'dl_save_listing' ), 10, 2 );

	}
	
	public function create_details_meta_box(){
		add_meta_box('listing_details', 'Listing Details', array($this, 'render_details_meta_box'), 'Directory List', 'normal', 'default');
	}
	
	public function render_details_meta_box($post){
		wp_nonce_field( 'dl_nonce_action', 'dl_nonce' );
		//Get the Values for the Details fields
		$dl_st_address1 = get_post_meta($post->ID, 'listing-st-address1', true);
        $dl_st_address2 = get_post_meta($post->ID, 'listing-st-address2', true);
        $dl_city = get_post_meta($post->ID, 'listing-city', true);
        $dl_state = get_post_meta($post->ID, 'listing-state', true);
        $dl_zip = get_post_meta($post->ID, 'listing-zip', true);
        $dl_phone = get_post_meta($post->ID, 'listing-phone', true);
        $dl_email = get_post_meta($post->ID, 'listing-email', true);
        $dl_website = get_post_meta($post->ID, 'listing-website', true);
		
		//Set Defaults
		if( empty( $dl_st_address ) ) $dl_st_address = '';
        if( empty( $dl_city_address ) ) $dl_city_address = '';
        if( empty( $dl_state_address ) ) $dl_state_address = '';
        if( empty( $dl_zip_address ) ) $dl_zip_address = '';
        if( empty( $dl_phone ) ) $dl_phone = '';
        if( empty( $dl_email ) ) $dl_email = '';
        if( empty( $dl_website ) ) $dl_website = '';

		
		//Render out the fields
        
		echo '<p><label for="listing-st-address1">Street Address: </label><input type="text" class="dl-admin-tx-input" name="listing-st-address1" id="listing-st-address1" placeholder="'.esc_attr__($dl_st_address1).'" value="'.esc_attr__($dl_st_address1).'" /></p>';
        
        echo '<p><label for="listing-st-address2">Street Address:<em>(Optional)</em> </label><input type="text" class="dl-admin-tx-input" name="listing-st-address2" id="listing-st-address2" placeholder="'.esc_attr__($dl_st_address2).'" value="'.esc_attr__($dl_st_address2).'" /></p>';
        
        echo '<p><label for="listing-city">City: </label><input type="text" class="dl-admin-tx-input" name="listing-city" id="listing-city" placeholder="'.esc_attr__( $dl_city).'" value="'.esc_attr__( $dl_city).'" /></p>';
        
        echo '<p><label for="listing-state">State: </label><input type="text" class="dl-admin-tx-input" name="listing-state" id="listing-state" placeholder="'.esc_attr__( $dl_state).'" value="'.esc_attr__( $dl_state).'" /></p>';
        
         echo '<p><label for="listing-zip">Zip: </label><input type="number" class="dl-admin-tx-input" name="listing-zip" id="listing-zip" placeholder="'.esc_attr__( $dl_zip).'" value="'.esc_attr__( $dl_zip).'" /></p>';
         
         echo '<p><label for="listing-phone">Telephone: </label><input type="tel" class="dl-admin-tx-input" name="listing-phone" id="listing-phone" placeholder="'.esc_attr__($dl_phone).'" value="'.esc_attr__( $dl_phone).'" /></p>';
         
         echo '<p><label for="listing-email">Email: </label><input type="email" class="dl-admin-tx-input" name="listing-email" id="listing-email" placeholder="'.esc_attr__( $dl_email).'" value="'.esc_attr__( $dl_email).'" /></p>';
         
         echo '<p><label for="listing-website">Website: </label><input type="url" class="dl-admin-tx-input" name="listing-website" id="listing-website" placeholder="'.esc_attr__( $dl_website).'" value="'.esc_attr__( $dl_website).'" /></p>';
        

	}
	
	
	public function dl_save_listing($post_id, $post) {
		// Add nonce for security and authentication.
        if(isset($_POST['dl_nonce'])){
		    $nonce_name   = $_POST['dl_nonce'];
		    $nonce_action = 'dl_nonce_action';
        }   
    	// Check if a nonce is set.
		if ( ! isset( $nonce_name ) )
			return;

		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
			return;

		// Check if the user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) )
			return;

		// Check if it's not a revision.
		if ( wp_is_post_revision( $post_id ) )
			return;
			
		$dl_meta['listing-st-address1'] = isset($_POST['listing-st-address1']) ? sanitize_text_field($_POST['listing-st-address1']) : '';
        $dl_meta['listing-st-address2'] = isset($_POST['listing-st-address2']) ? sanitize_text_field($_POST['listing-st-address2']) : '';
        $dl_meta['listing-city'] = isset($_POST['listing-city']) ? sanitize_text_field($_POST['listing-city']) : '';
        $dl_meta['listing-state'] = isset($_POST['listing-state']) ? sanitize_text_field($_POST['listing-state']) : '';
        $dl_meta['listing-zip'] = isset($_POST['listing-zip']) ? sanitize_text_field($_POST['listing-zip']) : '';
        $dl_meta['listing-phone'] = isset($_POST['listing-phone']) ? sanitize_text_field($_POST['listing-phone']) : '';
        $dl_meta['listing-email'] = isset($_POST['listing-email']) ? sanitize_text_field($_POST['listing-email']) : '';
        $dl_meta['listing-website'] = isset($_POST['listing-website']) ? sanitize_text_field($_POST['listing-website']) : '';

		

	    // Add values of $goals_meta as custom fields
	   foreach ($dl_meta as $key => $value) { // Cycle through the $goals_meta array!
	        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
	        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (not bloody likely, but still...)
	        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
	            update_post_meta($post->ID, $key, $value);
	        } else { // If the custom field doesn't have a value
	            add_post_meta($post->ID, $key, $value);
	        }
	        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	    } 

	}


}

new Listing_Details_Meta_Box;



?>