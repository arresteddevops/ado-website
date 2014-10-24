<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');
?>

<script type="text/javascript">
jQuery(document).ready(function() {
  jQuery('.report_actions').hide();
  jQuery('.edit_report').hover(
    function() {
      jQuery(this).children(".report_actions").show();
    },
    function() {
      jQuery(this).children(".report_actions").hide();
    }
  );

<?php
global $prli_group;
$groups = $prli_group->getAll();

foreach($groups as $group)
{
?>
  jQuery('.group-checkbox-<?php echo $group->id; ?>').change(function() {
    if (jQuery('.group-checkbox-<?php echo $group->id; ?>').attr("checked")) {
      jQuery(".group-link-checkbox-<?php echo $group->id; ?>").attr("checked", "checked");
    }
    else {
      jQuery(".group-link-checkbox-<?php echo $group->id; ?>").removeAttr("checked");
    }
  });

  jQuery('.group-link-checkbox-<?php echo $group->id; ?>').change(function() {
    if (jQuery('.group-link-checkbox-<?php echo $group->id; ?>').attr("checked")) {
      jQuery(".group-checkbox-<?php echo $group->id; ?>").removeAttr("checked");
    }
  });
<?php
}
?>

});
</script>

<style type="text/css">

.advanced_toggle {
  line-height: 34px;
  font-size: 12px;
  font-weight: bold;
  padding-bottom: 10px;
}

.edit_report {
  height: 50px;
}
.report_name {
  font-size: 12px;
  font-weight: bold;
}
.report_actions {
  padding-top: 5px;
}
</style>
