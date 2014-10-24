<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');
?>

<script type="text/javascript">
jQuery(document).ready(function() {
  jQuery('.group_actions').hide();
  jQuery('.edit_group').hover(
    function() {
      jQuery(this).children(".group_actions").show();
    },
    function() {
      jQuery(this).children(".group_actions").hide();
    }
  );
});
</script>

<style type="text/css">

.advanced_toggle {
  line-height: 34px;
  font-size: 12px;
  font-weight: bold;
  padding-bottom: 10px;
}

.edit_group {
  height: 50px;
}
.group_name {
  font-size: 12px;
  font-weight: bold;
}
.group_actions {
  padding-top: 5px;
}
</style>
