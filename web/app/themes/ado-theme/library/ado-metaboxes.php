<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB directory)
 *
 * @category Arrested DevOps
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */
if ( file_exists(  __DIR__ .'/cmb2/init.php' ) ) {
  require_once  __DIR__ .'/cmb2/init.php';
} elseif ( file_exists(  __DIR__ .'/CMB2/init.php' ) ) {
  require_once  __DIR__ .'/CMB2/init.php';
}

/**
 * Conditionally displays a field when used as a callback in the 'show_on_cb' field parameter
 *
 * @param  CMB2_Field object $field Field object
 *
 * @return bool                     True if metabox should show
 */
function cmb2_hide_if_no_cats( $field ) {
  // Don't show this field if not in the cats category
  if ( ! has_tag( 'cats', $field->object_id ) ) {
    return false;
  }
  return true;
}

add_filter( 'cmb2_meta_boxes', 'cmb2_sample_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function cmb2_sample_metaboxes( array $meta_boxes ) {

  // Start with an underscore to hide fields from custom fields list
  $prefix = '_cmb2_';

  $meta_boxes['episode_metabox'] = array(
    'id'            => 'episode_metabox',
    'title'         => __( 'Episode Information', 'cmb2'),
    'object_types'  => array('ado_episode', ),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    'fields'        => array(
        array(
          'name'    => __( 'Summary', 'cmb2' ),
          'desc'    => __( 'A one paragraph summary of the episode. Used in podcast summary, feed, and also on the short display on the homepage', 'cmb2' ),
          'id'      => $prefix . 'ado_summary',
          'type'    => 'wysiwyg',
          'options' => array( 'textarea_rows' => 10, ),
      ), 
        array(
          'name'    => __( 'Show Notes', 'cmb2' ),
          'desc'    => __( 'All of the show notes. Go crazy.', 'cmb2' ),
          'id'      => $prefix . 'ado_show_notes',
          'type'    => 'wysiwyg',
          'options' => array( 'textarea_rows' => 10, ),
      ),                     
        array(
          'name'    => __( 'Check Outs', 'cmb2' ),
          'desc'    => __( 'Check outs for each person. You will have to write the UL list stuff by hand for now', 'cmb2' ),
          'id'      => $prefix . 'ado_checkouts',
          'type'    => 'wysiwyg',
          'options' => array( 'textarea_rows' => 5, ),
        ),
        array(
          'name'    => __( 'Sponsor 1 Text', 'cmb2' ),
          'desc'    => __( 'The text for Sponsor 1 ad. Please make sure to include inline links!', 'cmb2' ),
          'id'      => $prefix . 'ado_sponsor_1_text',
          'type'    => 'wysiwyg',
          'options' => array( 'textarea_rows' => 5, ),
        ),
        array(
          'name' => __( 'Sponsor 1 Banner', 'cmb2' ),
          'desc' => __( 'Upload the banner image for Sponsor 1 for this episode (you can also choose it from one already uploaded)', 'cmb2' ),
          'id'   => $prefix . 'sponsor_1_banner',
          'type' => 'file',
        ),
        array(
          'name' => __( 'Sponsor 1 URL', 'cmb2' ),
          'id'   => $prefix . 'ado_sponsor_1_url',
          'type' => 'text_url',
          // 'protocols' => array( 'http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet' ), // Array of allowed protocols
        ),
        array(
          'name'    => __( 'Sponsor 2 Text', 'cmb2' ),
          'desc'    => __( 'The text for Sponsor 2 ad. Please make sure to include inline links!', 'cmb2' ),
          'id'      => $prefix . 'ado_sponsor_2_text',
          'type'    => 'wysiwyg',
          'options' => array( 'textarea_rows' => 5, ),
        ),
        array(
          'name' => __( 'Sponsor 2 Banner', 'cmb2' ),
          'desc' => __( 'Upload the banner image for Sponsor 2 for this episode (you can also choose it from one already uploaded)', 'cmb2' ),
          'id'   => $prefix . 'ado_sponsor_2_banner',
          'type' => 'file',
        ),
        array(
          'name' => __( 'Sponsor 2 URL', 'cmb2' ),
          'id'   => $prefix . 'ado_sponsor_2_url',
          'type' => 'text_url',
          // 'protocols' => array( 'http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet' ), // Array of allowed protocols
        ),
    ),
  );

  /**
   * Sample metabox to demonstrate each field type included
   */
  $meta_boxes['test_metabox'] = array(
    'id'            => 'test_metabox',
    'title'         => __( 'Test Metabox', 'cmb2' ),
    'object_types'  => array( 'page', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true, // Show field names on the left
    // 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
    'fields'        => array(
      array(
        'name'       => __( 'Test Text', 'cmb2' ),
        'desc'       => __( 'field description (optional)', 'cmb2' ),
        'id'         => $prefix . 'test_text',
        'type'       => 'text',
        'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
        // 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
        // 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
        // 'on_front'        => false, // Optionally designate a field to wp-admin only
        // 'repeatable'      => true,
      ),
      array(
        'name' => __( 'Test Text Small', 'cmb2' ),
        'desc' => __( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'test_textsmall',
        'type' => 'text_small',
        // 'repeatable' => true,
      ),
      array(
        'name' => __( 'Test Text Medium', 'cmb2' ),
        'desc' => __( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'test_textmedium',
        'type' => 'text_medium',
        // 'repeatable' => true,
      ),
      array(
        'name' => __( 'Website URL', 'cmb2' ),
        'desc' => __( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'url',
        'type' => 'text_url',
        // 'protocols' => array('http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet'), // Array of allowed protocols
        // 'repeatable' => true,
      ),
      array(
        'name' => __( 'Test Text Email', 'cmb2' ),
        'desc' => __( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'email',
        'type' => 'text_email',
        // 'repeatable' => true,
      ),
      array(
        'name' => __( 'Test Time', 'cmb2' ),
        'desc' => __( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'test_time',
        'type' => 'text_time',
      ),
      array(
        'name' => __( 'Time zone', 'cmb2' ),
        'desc' => __( 'Time zone', 'cmb2' ),
        'id'   => $prefix . 'timezone',
        'type' => 'select_timezone',
      ),
      array(
        'name' => __( 'Test Date Picker', 'cmb2' ),
        'desc' => __( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'test_textdate',
        'type' => 'text_date',
      ),
      array(
        'name' => __( 'Test Date Picker (UNIX timestamp)', 'cmb2' ),
        'desc' => __( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'test_textdate_timestamp',
        'type' => 'text_date_timestamp',
        // 'timezone_meta_key' => $prefix . 'timezone', // Optionally make this field honor the timezone selected in the select_timezone specified above
      ),
      array(
        'name' => __( 'Test Date/Time Picker Combo (UNIX timestamp)', 'cmb2' ),
        'desc' => __( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'test_datetime_timestamp',
        'type' => 'text_datetime_timestamp',
      ),
 
      array(
        'name' => __( 'Test Text Area', 'cmb2' ),
        'desc' => __( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'test_textarea',
        'type' => 'textarea',
      ),
      array(
        'name' => __( 'Test Text Area Small', 'cmb2' ),
        'desc' => __( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'test_textareasmall',
        'type' => 'textarea_small',
      ),
      array(
        'name' => __( 'Test Text Area for Code', 'cmb2' ),
        'desc' => __( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'test_textarea_code',
        'type' => 'textarea_code',
      ),
      array(
        'name' => __( 'Test Title Weeeee', 'cmb2' ),
        'desc' => __( 'This is a title description', 'cmb2' ),
        'id'   => $prefix . 'test_title',
        'type' => 'title',
      ),
      array(
        'name'    => __( 'Test Select', 'cmb2' ),
        'desc'    => __( 'field description (optional)', 'cmb2' ),
        'id'      => $prefix . 'test_select',
        'type'    => 'select',
        'options' => array(
          'standard' => __( 'Option One', 'cmb2' ),
          'custom'   => __( 'Option Two', 'cmb2' ),
          'none'     => __( 'Option Three', 'cmb2' ),
        ),
      ),
      array(
        'name'    => __( 'Test Radio inline', 'cmb2' ),
        'desc'    => __( 'field description (optional)', 'cmb2' ),
        'id'      => $prefix . 'test_radio_inline',
        'type'    => 'radio_inline',
        'options' => array(
          'standard' => __( 'Option One', 'cmb2' ),
          'custom'   => __( 'Option Two', 'cmb2' ),
          'none'     => __( 'Option Three', 'cmb2' ),
        ),
      ),
      array(
        'name'    => __( 'Test Radio', 'cmb2' ),
        'desc'    => __( 'field description (optional)', 'cmb2' ),
        'id'      => $prefix . 'test_radio',
        'type'    => 'radio',
        'options' => array(
          'option1' => __( 'Option One', 'cmb2' ),
          'option2' => __( 'Option Two', 'cmb2' ),
          'option3' => __( 'Option Three', 'cmb2' ),
        ),
      ),
      array(
        'name'     => __( 'Test Taxonomy Radio', 'cmb2' ),
        'desc'     => __( 'field description (optional)', 'cmb2' ),
        'id'       => $prefix . 'text_taxonomy_radio',
        'type'     => 'taxonomy_radio',
        'taxonomy' => 'category', // Taxonomy Slug
        // 'inline'  => true, // Toggles display to inline
      ),
      array(
        'name'     => __( 'Test Taxonomy Select', 'cmb2' ),
        'desc'     => __( 'field description (optional)', 'cmb2' ),
        'id'       => $prefix . 'text_taxonomy_select',
        'type'     => 'taxonomy_select',
        'taxonomy' => 'category', // Taxonomy Slug
      ),
      array(
        'name'     => __( 'Test Taxonomy Multi Checkbox', 'cmb2' ),
        'desc'     => __( 'field description (optional)', 'cmb2' ),
        'id'       => $prefix . 'test_multitaxonomy',
        'type'     => 'taxonomy_multicheck',
        'taxonomy' => 'post_tag', // Taxonomy Slug
        // 'inline'  => true, // Toggles display to inline
      ),
      array(
        'name' => __( 'Test Checkbox', 'cmb2' ),
        'desc' => __( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'test_checkbox',
        'type' => 'checkbox',
      ),
      array(
        'name'    => __( 'Test Multi Checkbox', 'cmb2' ),
        'desc'    => __( 'field description (optional)', 'cmb2' ),
        'id'      => $prefix . 'test_multicheckbox',
        'type'    => 'multicheck',
        'options' => array(
          'check1' => __( 'Check One', 'cmb2' ),
          'check2' => __( 'Check Two', 'cmb2' ),
          'check3' => __( 'Check Three', 'cmb2' ),
        ),
        // 'inline'  => true, // Toggles display to inline
      ),
      array(
        'name'    => __( 'Test wysiwyg', 'cmb2' ),
        'desc'    => __( 'field description (optional)', 'cmb2' ),
        'id'      => $prefix . 'test_wysiwyg',
        'type'    => 'wysiwyg',
        'options' => array( 'textarea_rows' => 5, ),
      ),
      array(
        'name' => __( 'Test Image', 'cmb2' ),
        'desc' => __( 'Upload an image or enter a URL.', 'cmb2' ),
        'id'   => $prefix . 'test_image',
        'type' => 'file',
      ),
      array(
        'name'         => __( 'Multiple Files', 'cmb2' ),
        'desc'         => __( 'Upload or add multiple images/attachments.', 'cmb2' ),
        'id'           => $prefix . 'test_file_list',
        'type'         => 'file_list',
        'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
      ),
      array(
        'name' => __( 'oEmbed', 'cmb2' ),
        'desc' => __( 'Enter a youtube, twitter, or instagram URL. Supports services listed at <a href="http://codex.wordpress.org/Embeds">http://codex.wordpress.org/Embeds</a>.', 'cmb2' ),
        'id'   => $prefix . 'test_embed',
        'type' => 'oembed',
      ),
    ),
  );



  /**
   * Repeatable Field Groups
   */
  $meta_boxes['field_group'] = array(
    'id'           => 'field_group',
    'title'        => __( 'Repeating Field Group', 'cmb2' ),
    'object_types' => array( 'page', ),
    'fields'       => array(
      array(
        'id'          => $prefix . 'repeat_group',
        'type'        => 'group',
        'description' => __( 'Generates reusable form entries', 'cmb2' ),
        'options'     => array(
          'group_title'   => __( 'Entry {#}', 'cmb2' ), // {#} gets replaced by row number
          'add_button'    => __( 'Add Another Entry', 'cmb2' ),
          'remove_button' => __( 'Remove Entry', 'cmb2' ),
          'sortable'      => true, // beta
        ),
        // Fields array works the same, except id's only need to be unique for this group. Prefix is not needed.
        'fields'      => array(
          array(
            'name' => 'Entry Title',
            'id'   => 'title',
            'type' => 'text',
            // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
          ),
          array(
            'name' => 'Description',
            'description' => 'Write a short description for this entry',
            'id'   => 'description',
            'type' => 'textarea_small',
          ),
          array(
            'name' => 'Entry Image',
            'id'   => 'image',
            'type' => 'file',
          ),
          array(
            'name' => 'Image Caption',
            'id'   => 'image_caption',
            'type' => 'text',
          ),
        ),
      ),
    ),
  );

  return $meta_boxes;
}
