<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');
?>

<div class="wrap">
<?php echo PrliAppHelper::page_title(__('Add Link Report', 'pretty-link')); ?>

<?php
  require(PRLI_VIEWS_PATH.'/shared/errors.php');
?>

<form name="form1" method="post" action="?page=<?php echo PRLI_PLUGIN_NAME ?>/pro/prlipro-reports.php">
<input type="hidden" name="action" value="create">
<?php wp_nonce_field('update-options'); ?>
<input type="hidden" name="id" value="<?php echo $id; ?>">

<table class="form-table">
  <tr class="form-field">
    <td width="75px" valign="top"><?php _e('Name*:', 'pretty-link'); ?> </td>
    <td><input type="text" name="name" value="<?php echo ((isset($_POST['name']))?$_POST['name']:''); ?>" size="75">
      <br/><span class="description"><?php _e("This is how you'll identify your Report.", 'pretty-link'); ?></span></td>
  </tr>
</table>
<table class="form-table">
  <tr class="form-field" valign="top">
    <td width="50%" valign="top">
      <h3><?php _e('Select Links to Analyze in this Report:', 'pretty-link'); ?></h3>
      <div style="height: 400px; width: 95%; border: 1px solid #8cbdd5; overflow: auto;">
        <ul width="100%">
          <?php
          $write_group = false;
          $first_group = true;
          for($i = 0; $i < count($links); $i++)
          {
            if(!$write_group)
            {
              if($i > 0)
                $prev_link = $link;
              else
                $prev_link = 0;

              $link = $links[$i];
            }

            if( !$write_group and
                ( ( !is_object($prev_link) and $prev_link == 0 and !empty($link->group_name) ) or
                  ( is_object($prev_link) and $prev_link->group_name != $link->group_name ) ) )
            {
              if(!$first_group)
              {
              ?>

                </ul>
              </li>
              <?php
              }
              ?>
                <li width="100%"><span style="padding: 5px; margin: 0px; font-size: 14px; background-color: #ababab; display: block;"><input type="checkbox" class="group-checkbox-<?php echo $link->group_id; ?>" style="width: 15px;" name="group-row-<?php echo $link->group_id; ?>" <?php echo (((isset($_POST['group-row-'.$link->group_id]) and $_POST['group-row-'.$link->group_id] == 'on'))?'checked="true"':''); ?>/>&nbsp;<?php echo substr(stripslashes($link->group_name),0,50); ?></span>

                  <ul class="group-links-<?php echo $link->group_id; ?>" style="list-style-type: none;">
              <?php

              $i--; // decrement -- this group row shouldn't throw off the numbering
              $write_group = true;
              $first_group = false;
              continue;
            }

            if($write_group)
              $write_group = false;

            ?>
            <li class="link-list-item" style="<?php echo (($i%2)?'background-color: #efefef; ':'background-color: #dedede; '); ?>padding: 5px; <?php echo ((!$first_group)?'padding-left: 25px; ':''); ?>margin: 0px; "><input type="checkbox" style="width: 15px;" class="group-link-checkbox-<?php echo $link->group_id; ?>" name="link[<?php echo $link->id; ?>]" <?php echo (((isset($_POST['link'][$link->id]) and $_POST['link'][$link->id] == 'on'))?'checked="true"':''); ?>/>&nbsp;<?php echo substr(stripslashes($link->name),0,50) . " <strong>(" . $link->slug . ")</strong>"; ?></li>
            <?php
          }
          if(!$first_group)
          {
          ?>
            </ul></li>
          <?php
          }
          ?>
        </ul>
      </div>
      <span class="description"><?php _e('Select some links to be analyzed in this report.', 'pretty-link'); ?></span></td>
    </td>
    <td valign="top" width="50%">
      <h3><?php _e('Select Your Goal Link (optional):', 'pretty-link'); ?> </h3>
      <div style="height: 400px; width: 95%; border: 1px solid #8cbdd5; overflow: auto;">
        <table width="100%" cellspacing="0">
          <thead style="background-color: #dedede; padding: 0px; margin: 0px; line-height: 8px; font-size: 14px;">
            <th width="75%" style="padding-left: 5px; margin: 0px;"><strong><?php _e('Name', 'pretty-link'); ?></strong></th>
            <th width="25%" style="padding-left: 5px; margin: 0px;"><strong><?php _e('Group', 'pretty-link'); ?></strong></th>
          </thead>
          <?php
          for($i = 0; $i < count($links); $i++)
          {
            $link = $links[$i];
            ?>
            <tr <?php echo (($i%2)?' style="background-color: #efefef;"':''); ?>>
              <td style="padding: 5px; margin: 0px;" width="50%"><input type="radio" style="width: 15px;" name="goal_link_id" value="<?php echo $link->id; ?>" <?php echo (((isset($_POST['goal_link_id']) and $_POST['goal_link_id'] == $link->id))?'checked="true"':''); ?>/>&nbsp;<?php echo substr(stripslashes($link->name),0,25) . " <strong>(" . $link->slug . ")</strong>"; ?></td>
              <td style="padding: 0px; margin: 0px;" width="50%"><?php echo substr(stripslashes($link->group_name),0,20); ?></td>
            </tr>
            <?php
            
          }
          ?>
        </table>
      </div>
      <span class="description"><?php _e('If you want to enable conversion tracking in this report then select a goal link.', 'pretty-link'); ?></span></td>
    </td>
  </tr>
</table>
</div>

<p class="submit">
<input type="submit" name="Submit" value="Create" />&nbsp;<?php _e('or', 'pretty-link'); ?>&nbsp;<a href="?page=<?php echo PRLI_PLUGIN_NAME ?>/pro/prlipro-reports.php&action=list"><?php _e('Cancel', 'pretty-link'); ?></a>
</p>

</form>
</div>
