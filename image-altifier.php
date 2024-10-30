<?php
/**
 * Plugin Name: Image Altifier
 * Plugin URI: https://plugins.belov.digital/
 * Description: Automatically sets the ALT attribute of images in posts, pages, and custom posts using their titles for better SEO and accessibility.
 * Version: 1.0.0
 * Requires at least: 5.7
 * Requires PHP: 7.2
 * Author: Belov Digital Agency
 * Author URI: https://belovdigital.agency
 * License: GPL-2.0-or-later
 * Text Domain: image-altifier
 */

function wp_image_altifier($content) {
    global $post;

    if (is_singular() && !empty($post)) {
        $post_title = $post->post_title;

        $pattern = '/<img(?![^>]*alt=["\'])([^>]*)>/i';
        $pattern_empty_alt = '/(<img[^>]*alt=["\']\s*["\'])([^>]*)>/i';

        $replace_callback = function($matches) use ($post_title) {
            $img_tag = $matches[0];
            $img_tag_with_alt = preg_replace('/(<img)([^>]*)>/i', '$1 alt="' . esc_attr($post_title) . '"$2>', $img_tag);
            return $img_tag_with_alt;
        };

        $content = preg_replace_callback($pattern, $replace_callback, $content);
        $content = preg_replace_callback($pattern_empty_alt, $replace_callback, $content);
    }

    return $content;
}
add_filter('the_content', 'wp_image_altifier');