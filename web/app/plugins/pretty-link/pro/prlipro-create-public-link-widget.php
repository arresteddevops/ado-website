<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

if(class_exists('WP_Widget'))
{
  class PrliCreatePublicLinkWidget extends WP_Widget {
    // widget actual processes
    function PrliCreatePublicLinkWidget() {
      parent::WP_Widget(false, $name = 'Create a Short URL');
    }
    
    // outputs the content of the widget
    function widget($args, $instance) {
      require_once( PRLIPRO_PATH . '/prlipro-public.php' );
      extract( $args );
    ?>
      <?php echo $before_widget; ?>
        <?php echo $before_title . $after_title; ?>
          <?php echo prlipro_display_public_form($instance['label'], $instance['button'], $instance['redirect_type'],$instance['track'],$instance['group']); ?>
        <?php echo $after_widget; ?>
      <?php
    }
    
    // processes widget options to be saved
    function update($new_instance, $old_instance) {
      return $new_instance;
    }
    
    // outputs the options form on admin
    function form($instance) {
      $selected = ' selected="selected"';
  
      $label         = esc_attr($instance['label']);
      $button        = esc_attr($instance['button']);
      $redirect_type = esc_attr($instance['redirect_type']);
      $track         = esc_attr($instance['track']);
      $group         = esc_attr($instance['group']);
      $saved_before  = esc_attr($instance['saved_before']);
    ?>
      <input type="hidden" id="<?php echo $this->get_field_id('saved_before'); ?>" name="<?php echo $this->get_field_name('saved_before'); ?>" value="1" />
      <p><label for="<?php echo $this->get_field_id('label'); ?>"><?php _e('Label Text:', 'pretty-link'); ?> <input class="widefat" id="<?php echo $this->get_field_id('label'); ?>" name="<?php echo $this->get_field_name('label'); ?>" type="text" value="<?php echo (($saved_before != '1')?'Enter a URL:&nbsp;':$label); ?>" /></label></p>
      <p><label for="<?php echo $this->get_field_id('button'); ?>"><?php _e('Button Text:', 'pretty-link'); ?> <input class="widefat" id="<?php echo $this->get_field_id('button'); ?>" name="<?php echo $this->get_field_name('button'); ?>" type="text" value="<?php echo (($saved_before != '1')?'Shrink':$button); ?>" /></label><br/><small>(<?php _e('if left blank, no button will display', 'pretty-link'); ?>)</small></p>
      <p><strong><?php _e('Pretty Link Options', 'pretty-link'); ?></strong></p>
      <p>
        <label for="<?php echo $this->get_field_id('redirect_type'); ?>"><?php _e('Redirection:', 'pretty-link'); ?>
          <select id="<?php echo $this->get_field_id('redirect_type'); ?>" name="<?php echo $this->get_field_name('redirect_type'); ?>">
            <option value="-1"><?php _e('Default', 'pretty-link'); ?>&nbsp;</option>
            <option value="301"<?php echo (($redirect_type == '301')?$selected:''); ?>><?php _e('Permanent/301', 'pretty-link'); ?>&nbsp;</option>
            <option value="307"<?php echo (($redirect_type == '307')?$selected:''); ?>><?php _e('Temporary/307', 'pretty-link'); ?>&nbsp;</option>
            <option value="prettybar"<?php echo (($redirect_type == 'prettybar')?$selected:''); ?>><?php _e('PrettyBar', 'pretty-link'); ?>&nbsp;</option>
            <option value="cloak"<?php echo (($redirect_type == 'cloak')?$selected:''); ?>><?php _e('Cloak', 'pretty-link'); ?>&nbsp;</option>
          </select>
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('track'); ?>"><?php _e('Tracking Enabled:', 'pretty-link'); ?>
          <select id="<?php echo $this->get_field_id('track'); ?>" name="<?php echo $this->get_field_name('track'); ?>">
            <option value="-1"><?php _e('Default', 'pretty-link'); ?>&nbsp;</option>
            <option value="1"<?php echo (($track == '1')?$selected:''); ?>><?php _e('Yes', 'pretty-link'); ?>&nbsp;</option>
            <option value="0"<?php echo (($track == '0')?$selected:''); ?>><?php _e('No', 'pretty-link'); ?>&nbsp;</option>
          </select>
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('group'); ?>"><?php _e('Group:', 'pretty-link'); ?>
          <select id="<?php echo $this->get_field_id('group'); ?>" name="<?php echo $this->get_field_name('group'); ?>">
            <option value="-1"><?php _e('None', 'pretty-link'); ?>&nbsp;</option>
            <?php
            $groups = prli_get_all_groups();
            foreach($groups as $g)
            {
            ?>
            <option value="<?php echo $g['id']; ?>"<?php echo (($group == $g['id'])?$selected:''); ?>><?php echo $g['name']; ?>&nbsp;</option>
            <?php
            }
            ?>
          </select>
        </label>
      </p>
    <?php
    }
  }
}
