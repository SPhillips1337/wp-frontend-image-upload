<?php
/**
* Plugin Name: wp-frontend-image-upload
* Plugin URI: https://www.stephenphillips.co.uk/wp-frontend-image-upload
* Description: Simple HTML Frontend image upload plugin for wordpress using shortcode
* Version: 0.3
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
	echo '<strong>Version</strong>: 0.2<br>';
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
	// options
	/*
	echo '<h2>Options</h2>';
	echo '<p>The options are used to change the behaviour of the image uploader.</p>';
	echo '<p>The options are as follows:</p>';
	echo '<pre>[frontend-image-uploader options="{ \'upload_text\': \'Upload Image\', \'delete_text\': \'Delete Image\', \'upload_button_class\': \'button button-primary\', \'delete_button_class\': \'button button-secondary\', \'image_class\': \'image-uploader-image\', \'image_id_class\': \'image-uploader-image-id\', \'image_url_class\': \'image-uploader-image-url\', \'image_delete_class\': \'image-uploader-image-delete\', \'image_upload_class\': \'image-uploader-image-upload\', \'image_upload_button_class\': \'button button-primary\', \'image_upload_button_text\': \'Upload Image\', \'image_delete_button_class\': \'button button-secondary\', \'image_delete_button_text\': \'Delete Image\' }]</pre>';
	echo '<p>The options are as follows:</p>';
	echo '<pre>upload_text: Upload Image<br>delete_text: Delete Image<br>upload_button_class: button button-primary<br>delete_button_class: button button-secondary<br>image_class: image-uploader-image<br>image_id_class: image-uploader-image-id<br>image_url_class: image-uploader-image-url<br>image_delete_class: image-uploader-image-delete<br>image_upload_class: image-uploader-image-upload<br>image_upload_button_class: button button-primary<br>image_upload_button_text: Upload Image<br>image_delete_button_class: button button-secondary<br>image_delete_button_text: Delete Image</pre>';
	*/

	// TODO: add configuration options and form label customization
	/*
	echo '<hr>';
	echo '<h2>Configuration</h2>';
	echo '<form action="options.php" method="post">';
	settings_fields('frontend_image_uploader_options');
	do_settings_sections('frontend_image_uploader_options');
	submit_button();
	echo '</form>';
	*/

}

// create shortcode to place the frontend form
add_shortcode( 'frontend_image_uploader', 'frontend_image_uploader_callback' );

// call back which renders the frontend form HTML
function frontend_image_uploader_callback(){
	// return the HTML 5 image upload form
	return '<form action="'.esc_url( admin_url('admin-post.php')).'" method="post" enctype="multipart/form-data">
	<input type="hidden" name="action" value="frontend_image_uploader_process_upload">
	Your Photo: <input type="file" name="image_file" accept="image/*" />
	<input type="submit" name="submit" value="Submit" />
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