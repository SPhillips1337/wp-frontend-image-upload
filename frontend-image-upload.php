<?php
/**
* Plugin Name: wp-frontend-image-upload
* Plugin URI: https://www.stephenphillips.co.uk/wp-frontend-image-upload
* Description: Simple HTML Frontend image upload plugin for wordpress using shortcode
* Version: 0.5
* Author: Stephen Phillips
* Author URI: https://www.stephenphillips.co.uk/
**/

// add admin menu item
add_action('admin_menu', 'frontend_image_uploader_menu');
 
function frontend_image_uploader_menu(){
    add_menu_page( 'WP Frontend Image Uploader Menu', 'WP Frontend Image Uploader Plugin', 'manage_options', 'wp-frontend_image_upload-plugin', 'frontend_image_uploader_admin_init' );
}
 
function frontend_image_uploader_admin_init(){

	echo '<h1>WP Frontend Image Uploader</h1>';
	echo '<hr>';
	echo '<strong>Plugin Name</strong>: wp-frontend-image-upload<br>';
	echo '<strong>Plugin URI</strong>: <a href="https://www.stephenphillips.co.uk/wp-frontend-image-upload" target="_blank">https://www.stephenphillips.co.uk/wp-frontend-image-upload</a><br>';
	echo '<strong>Description</strong>: Simple HTML Frontend image upload plugin for wordpress using shortcode<br>';
	echo '<strong>Version</strong>: 0.5<br>';
	echo '<strong>Author</strong>: Stephen Phillips<br>';
	echo '<strong>Author URI</strong>: <a href="https://www.stephenphillips.co.uk/" target="_blank">https://www.stephenphillips.co.uk/</a><br>';
	echo '<hr>';
	// usage
	echo '<h2>Usage</h2>';
	// add a simple text form to copy shortcode from
	echo '<form action="">';
	echo '<input type="text" id="shortcode" name="shortcode" value="[frontend-image-uploader]" size="50">';
	echo '</form>';
	echo '<br>Click the above text to copy the shortcode to your clipboard';
	echo '<hr>';
	// add javascript to copy shortcode to clipboard on click
	echo '<script>';
	echo 'document.getElementById("shortcode").onclick = function() {';
	echo 'var copyText = document.getElementById("shortcode");';
	echo 'copyText.select();';
	echo 'document.execCommand("copy");';
	echo 'alert("Copied the text: " + copyText.value);';
	echo '}';
	echo '</script>';
	
	//shortcode
	echo '<h2>Shortcode</h2>';
	echo '<p>The shortcode is used to display the image uploader on a page or post.</p>';
	echo '<p>The shortcode is as follows:</p>';
	echo '<pre>[frontend-image-uploader]</pre>';
	echo '<p>The shortcode can be used on any page or post.</p>';
	echo '<hr>';

}
/************************************************************************** */
// configuration options and form label customization
function frontend_image_uploader_add_settings_page() {
    add_options_page( 'Frontend Image Uploader', 'Frontend Image Uploader', 'manage_options', 'frontend_image_uploader_settings', 'frontend_image_uploader_plugin_settings_page' );
}
add_action( 'admin_menu', 'frontend_image_uploader_add_settings_page' );

function frontend_image_uploader_plugin_settings_page() {
    ?>
    <h2>Frontend Image Uploader Settings</h2>
    <form action="options.php" method="post">
        <?php 
        settings_fields( 'frontend_image_uploader_plugin_options' );
        do_settings_sections( 'frontend_image_uploader_plugin' ); ?>
        <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
    </form>
    <?php
}

function frontend_image_uploader_register_settings() {
    register_setting( 'frontend_image_uploader_plugin_options', 'frontend_image_uploader_plugin_options', 'frontend_image_uploader_plugin_options_validate' );
    add_settings_section( 'configuration_settings', 'Configuration', 'frontend_image_uploader_section_text', 'frontend_image_uploader_plugin' );

    add_settings_field( 'frontend_image_uploader_setting_text_label', 'Text Label', 'frontend_image_uploader_setting_text_label', 'frontend_image_uploader_plugin', 'configuration_settings' );
	add_settings_field( 'frontend_image_uploader_setting_button_text', 'Button Text', 'frontend_image_uploader_setting_button_text', 'frontend_image_uploader_plugin', 'configuration_settings' );

}
add_action( 'admin_init', 'frontend_image_uploader_register_settings' );

function frontend_image_uploader_plugin_options_validate( $input ) {
	/*
    $newinput['api_key'] = trim( $input['api_key'] );
    if ( ! preg_match( '/^[a-z0-9]{32}$/i', $newinput['api_key'] ) ) {
        $newinput['api_key'] = '';
    }

    return $newinput;*/
	return $input;

}

function frontend_image_uploader_section_text() {
    echo '<p>Here you can set all the options for using the plugin</p>';
}

function frontend_image_uploader_setting_text_label() {
	// fetch our plugin options
    $options = get_option( 'frontend_image_uploader_plugin_options' );
	// assign plugin option for text label
	$text_label = ($options['text_label']=="") ? 'Your Photo' : $options['text_label'];
    echo "<input id='frontend_image_uploader_setting_text_label' name='frontend_image_uploader_plugin_options[text_label]' type='text' value='" . $text_label . "' placeholder='Your Photo' />";
}

function frontend_image_uploader_setting_button_text() {
	// fetch our plugin options
    $options = get_option( 'frontend_image_uploader_plugin_options' );
	// assign plugin option for button text
	$button_text = ($options['button_text']=="") ? 'Submit' : $options['button_text'];
    echo "<input id='frontend_image_uploader_setting_button_text' name='frontend_image_uploader_plugin_options[button_text]' type='text' value='" . $button_text . "' placeholder='Submit' />";
}

/************************************************************************* */

// create shortcode to place the frontend form
add_shortcode( 'frontend_image_uploader', 'frontend_image_uploader_callback' );

// call back which renders the frontend form HTML
function frontend_image_uploader_callback(){
	// fetch our plugin options
    $options = get_option( 'frontend_image_uploader_plugin_options' );
	// assign plugin option for text label
	$text_label = ($options['text_label']=="") ? 'Your Photo' : $options['text_label'];
	// assign plugin option for button text
	$button_text = ($options['button_text']=="") ? 'Submit' : $options['button_text'];	
	// return the HTML 5 image upload form
	return '<form action="'.esc_url( admin_url('admin-post.php')).'" method="post" enctype="multipart/form-data">
	<input type="hidden" name="action" value="frontend_image_uploader_process_upload">
	'. $text_label .': <input type="file" name="image_file" accept="image/*" />
	<input type="submit" name="submit" value="'. $button_text . '" />
	</form>';

	// NOTE: adding capture="camera" to the form will capture a photo from the camera only on mobile devices (Might offer as an option in admin panel)
}

// admin post action for frontend form which handles the image upload
function frontend_image_uploader_process_upload(){
	// WordPress environmet
	require( ABSPATH . 'wp-load.php' );

	// it allows us to use wp_handle_upload() function
	require_once( ABSPATH . 'wp-admin/includes/file.php' );

	// you can add some kind of validation here
	if( empty( $_FILES[ 'image_file' ] ) ) {
		wp_die( 'No files selected.' );
	}

	$upload = wp_handle_upload( 
		$_FILES[ 'image_file' ], 
		array( 'test_form' => false ) 
	);

	if( ! empty( $upload[ 'error' ] ) ) {
		wp_die( $upload[ 'error' ] );
	}

	// lets check that the file type is an image
	$wp_filetype = wp_check_filetype( $upload['file'] );
	if( $wp_filetype['type'] != 'image/jpeg' && $wp_filetype['type'] != 'image/png' ) {
		wp_die( 'Image type not allowed.' );
	}

	// it is time to add our uploaded image into WordPress media library
	$attachment_id = wp_insert_attachment(
		array(
			'guid'           => $upload[ 'url' ],
			'post_mime_type' => $upload[ 'type' ],
			'post_title'     => basename( $upload[ 'file' ] ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$upload[ 'file' ]
	);

	if( is_wp_error( $attachment_id ) || ! $attachment_id ) {
		wp_die( 'Upload error.' );
	}

	// update medatata, regenerate image sizes
	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	wp_update_attachment_metadata(
		$attachment_id,
		wp_generate_attachment_metadata( $attachment_id, $upload[ 'file' ] )
	);

	// just redirect to the uploaded file
	wp_safe_redirect( $upload[ 'url' ] );
	exit;


}
// admin post hooks which link post to upload process function from frontend form when submit
add_action( 'admin_post_nopriv_frontend_image_uploader_process_upload', 'frontend_image_uploader_process_upload' );
add_action( 'admin_post_frontend_image_uploader_process_upload', 'frontend_image_uploader_process_upload' );