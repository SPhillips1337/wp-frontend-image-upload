<?php
/**
* Plugin Name: wp-frontend-image-upload
* Plugin URI: https://www.stephenphillips.co.uk/wp-frontend-image-upload
* Description: Simple HTML Frontend image upload plugin for wordpress using shortcode
* Version: 0.1
* Author: Stephen Phillips
* Author URI: https://www.stephenphillips.co.uk/
**/

add_shortcode( 'frontend_image_uploader', 'frontend_image_uploader_callback' );

function frontend_image_uploader_callback(){
	return '<form action="'.esc_url( admin_url('admin-post.php')).'" method="post" enctype="multipart/form-data">
	<input type="hidden" name="action" value="frontend_image_uploader_process_upload">
	Your Photo: <input type="file" name="image_file" />
	<input type="submit" name="submit" value="Submit" />
	</form>';
}

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

add_action( 'admin_post_nopriv_frontend_image_uploader_process_upload', 'frontend_image_uploader_process_upload' );
add_action( 'admin_post_frontend_image_uploader_process_upload', 'frontend_image_uploader_process_upload' );