<?php
/*
Plugin Name: Picture Element Responsive and Retina Images
Description: A plugin that helps you generate a picture element for creating responsive images. Retina support is included out of the box.
Version: 1.0.0
Author: Christopher Rolfe
Author URI: https://chrisrolfe.info
License: MIT
*/

$content_width = 3000;

if ( !function_exists( 'perri_generate_picture' ) ) {
	function perri_generate_picture( $picture_id, array $images, $attrs = [] ) {
		$source_function = apply_filters( 'responsive_retina_images_source_function', 'wp_get_attachment_image_src' );
		$array_index = apply_filters( 'responsive_retina_images_source_function_index', 0 );

		if ( function_exists( $source_function ) ) {

			$html = "<picture data-id='$picture_id'>";

			foreach ( $images as $size => $media ) {
				$mime = ( get_post_mime_type( $picture_id ) ?: false );

				// TODO: Rewrite this so that 2x and 3x times aren't loaded if they are the same size as the 1x
				$image_url = $source_function( $picture_id, $size )[$array_index];
				$image_url_2x = $source_function( $picture_id, $size . '-2x' )[$array_index];
				$image_url_3x = $source_function( $picture_id, $size . '-3x' )[$array_index];

				$html .= "<source";
				$html .= " srcset='$image_url 1x, $image_url_2x 2x, $image_url_3x 3x'";
				$html .= " media='$media'";

				if ( $mime ) {
					$html .= " type='$mime'";
				}

				foreach ( $attrs as $name => $value ) {
					$html .= " $name='$value'";
				}

				$html .= " data-size='$size'>";
			}

			$html .= wp_get_attachment_image($picture_id, 'full', false, $attrs);

			$html .= '</picture>';

			return $html;
		}
		return;
	}
}

if ( !function_exists( 'perri_add_image_size_for_picture' ) ) {
	function perri_add_image_size_for_picture( $name, $width = -1, $height = -1, $crop = false ) {
		$add_size_function = apply_filters( 'responsive_retina_images_add_size_function', 'wp_get_attachment_image_src' );

		if ( function_exists( $add_size_function ) ) {
			$add_size_function( $name, $width, $height, $crop );
			$add_size_function( "$name-2x", $width * 2, $height * 2, $crop );
			$add_size_function( "$name-3x", $width * 3, $height * 3, $crop );
		}
	}
}
