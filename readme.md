=== Picture Element Responsive and Retina Images ===
Contributors: christhewanderer
Tags: picture, responsive-images, retina-images
Requires at least: 4.0.0
Tested up to: 4.7.2
Stable tag: 1.0.0
License: MIT

A plugin that helps you generate a picture element for creating responsive images. Retina support is included out of the box.

== Description ==
WordPress has introduced the srcset feature into core which is great but this plugin goes a step further to help theme developers use the picture element with a fallback to a standard image tag and srcset.

This plugin also offers support for on the fly image generation plugins. In fact it's hugely encouraged to use one as this plugin can cause image uploads to slow down otherwise.

It also offers support for 2x and 3x images for retina screens so you can serve the best image possible for your visitors.

= Define image sizes =
First you need to define your image sizes, similar to add_image_size. This particular function is optional but recommended because it also creates 2x and 3x images for retina.
E.G. `add_image_size_for_picture('mobile_images', 320, 0, true);`

= Using images in your template =
Once your sizes are defined you can call them in your template like so:
`
generate_picture($some_attachment_id, [
    'mobile_images' => '(max-width: 320px)'
], [
    'class' => 'some-class-name',
    'data-item' => 1
]);
`

The second argument array is in the format of `image size name : media query` that way you can specify as many different responsive sizes as you need to.
The third argument allows you to add attributes to the source/img tag.

= Integrating with on the fly image generation plugins =
Adding upload sizes to wordpress and slow down the image upload significantly as we generate three times as many images that you add a size for. This can cause the upload to time out if you are using a server with a low amount of cpu cores.

To solve this we recommend using a plugin that will create these images independent of the upload and not affect users. For this purpose we have three filters available for you to change the functions we call underneath the hood.

`responsive_retina_images_add_size_function` is the function to call when adding a new size, by default this calls `add_image_size`
`'responsive_retina_images_source_function` is the function to call when pulling the image from the database, by default this calls `wp_get_attachment_image_src`
`responsive_retina_images_source_function_index` is the index at which to access the url returned from `responsive_retina_images_source_function`, by default this is `0`

For example if you were to integrate with Fly Dynamic Image Resizer you could add the following to your functions.php.

`
add_filter('responsive_retina_images_add_size_function', function() { return 'fly_add_image_size'; });
add_filter('responsive_retina_images_source_function', function() { return 'fly_get_attachment_image_src'; });
add_filter('responsive_retina_images_source_function_index', function() { return 'src'; });
`

== Installation ==
1. Upload the plugin or install via the WordPress interface and activate it.
2. Add your image sizes using the `add_image_size_for_picture`, this follows the same syntax as `add_image_size`
3. In your template call `generate_picture` with the arguments of the attachment_id, an array of the image sizes you want to use, and then any attributes you want to add to the source/img tags.

== Changelog ==
1.0.0 - Initial version of the plugin
