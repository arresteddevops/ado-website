<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

?>
  <tr class="form-field" id="double_redirect_row">
    <td width="75px" valign="top"><?php _e("Double Redirect:", 'pretty-link') ?></td>
    <td>
      <label for="double_redirect">
        <input type="checkbox" name="double_redirect" style="width:15px;" <?php echo stripslashes($double_redirect); ?>/>
        <span><?php _e('Use a double redirect to erase all referrer information', 'pretty-link'); ?></span>
      </label>
    </td>
  </tr>

<?php if( $prlipro_options->keyword_replacement_is_on ): ?>
  <tr class="form-field">
    <td valign="top"><?php _e("Keywords:", 'pretty-link'); ?></td>
    <td><input type="text" name="keywords" value="<?php echo stripslashes(htmlspecialchars($keywords)); ?>" />
    <br/>
      <span class="description"><?php _e("Enter a comma separated list of keywords / keyword phrases that you'd like to replace with this link in your Posts &amp; Pages.", 'pretty-link'); ?></span></td>
  </tr>
  <tr class="form-field">
    <td width="75px" valign="top"><?php _e("URL Replacements:", 'pretty-link'); ?></td>
    <td><input type="text" name="url_replacements" value="<?php echo stripslashes(htmlspecialchars($url_replacements)); ?>" />
    <br/>
      <span class="description"><?php _e("Enter a comma separated list of the URLs that you'd like to replace with this Pretty Link in your Posts &amp; Pages. These must be formatted as URLs for example: <code>http://example.com</code> or <code>http://example.com?product_id=53</code>", 'pretty-link'); ?></span></td>
  </tr>
<?php endif; ?>

  <tr class="form-field">
    <td width="100%" colspan="2" valign="top"><?php _e("Target URL Rotations:", 'pretty-link'); ?>
    <br/>
      <span class="description"><?php _e("Enter the Target URLs that you'd like to rotate through when this Pretty Link is Clicked. These must be formatted as URLs example: <code>http://example.com</code> or <code>http://example.com?product_id=53</code>", 'pretty-link'); ?></span>
      <br/><br/>
      <ol style="width: 50%;">
        <li><input style="width: 65%;" readonly="true" type="text" value="<?php echo (!empty($target_url)?htmlspecialchars($target_url):'Target URL (above)'); ?>" />&nbsp;&nbsp;<?php _e('weight:', 'pretty-link'); ?> <?php prlipro_rotation_weight_select_helper((!empty($target_url_weight)?$target_url_weight:'100'),"target_url_weight"); ?></li>
      <?php for($i=0;$i<4;$i++) {
              $rotation = (isset($url_rotations[$i]) and !empty($url_rotations[$i]))?htmlspecialchars($url_rotations[$i]):'';
              $weights  = (isset($url_rotation_weights[$i])?$url_rotation_weights:0);
 ?>
        <li><input style="width: 65%;" type="text" name="url_rotations[]" value="<?php echo $rotation; ?>" />&nbsp;&nbsp;<?php _e('weight:', 'pretty-link'); ?> <?php prlipro_rotation_weight_select_helper($weights[$i]); ?>
        </li>
      <?php } ?>
      </ol>
    </td>
  </tr>
  <tr>
    <td width="100%" colspan="2" valign="top">
      <input class="prlipro-enable-split-test" type="checkbox" name="enable_split_test" <?php echo (($enable_split_test != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e("Split Test This Link", 'pretty-link'); ?>
      <br/><span class="description"><?php _e("This works best when you have multiple link rotation URLs entered -- that's the whole point of split testing ...", 'pretty-link'); ?></span>
    </td>
  </tr>
  <tr class="form-field prlipro-split-test-goal-link">
    <td colspan="2">
      <div style="padding-left: 25px;">
      <h3><?php _e("Split Test Goal Link:", 'pretty-link'); ?> </h3>
      <div style="height: 200px; width: 95%; border: 1px solid #8cbdd5; overflow: auto;">
        <ul style="width: 100%; list-style-type: none;">
          <li style="background-color: #dedede; padding-top: 5px; padding-bottom: 5px; margin: 0px; line-height: 25px; font-size: 14px;">
            <div style="width: 50%; min-width: 50%; float: left;">&nbsp;&nbsp;<strong><?php _e("Name", 'pretty-link'); ?></strong></div>
            <div>&nbsp;&nbsp;<strong><?php _e("Group", 'pretty-link'); ?></strong></div>
          </li>
          <?php
          for($i = 0; $i < count($links); $i++) {
            $link = $links[$i];
            ?>
            <li style="padding-top: 2px; padding-bottom: 2px; line-height: 20px; font-size: 12px;<?php echo (($i%2)?' background-color: #efefef;':''); ?>">
              <div style="float:left; min-width: 50%; width: 50%;">&nbsp;&nbsp;<input type="radio" name="split_test_goal_link" style="display: inline; width: 15px;" value="<?php echo $link->id; ?>" <?php echo ((($split_test_goal_link and $split_test_goal_link == $link->id))?'checked="true"':''); ?>/>&nbsp;&nbsp;<?php echo substr(stripslashes($link->name),0,25) . " <strong>(" . $link->slug . ")</strong>"; ?></div>
              <div>&nbsp;&nbsp;<?php echo substr(stripslashes($link->group_name),0,25); ?></div>
            </li>
            <?php
          }
          ?>
        </ul>
      </div>
      <span class="description"><?php _e("This is the goal link for your split test.", 'pretty-link'); ?></span>
      </div>
    </td>
  </tr>
