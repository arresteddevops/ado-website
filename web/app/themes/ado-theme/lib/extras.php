<?php
/**
 * Clean up the_excerpt()
 */
function roots_excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'roots') . '</a>';
}
add_filter('excerpt_more', 'roots_excerpt_more');

add_filter('roots/wrap_base', 'roots_wrap_base_cpts'); // Add our function to the roots_wrap_base filter

function roots_wrap_base_cpts($templates) {
  $cpt = get_post_type(); // Get the current post type
  if ($cpt) {
     array_unshift($templates, 'base-' . $cpt . '.php'); // Shift the template to the front of the array
  }
  return $templates; // Return our modified array with base-$cpt.php at the front of the queue
}


// Bug testing only. Not to be used on a production site!!
add_action('wp_footer', 'roots_wrap_info');

function roots_wrap_info() {
$format = '<h6>The %s template being used is: %s</h6>';
$main   = Roots_Wrapping::$main_template;
global $template;

printf($format, 'Main', $main);
printf($format, 'Base', $template);
}

