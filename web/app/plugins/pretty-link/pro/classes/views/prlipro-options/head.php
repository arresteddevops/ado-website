<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');
?>

<script type="text/javascript">
jQuery(document).ready(function() {
  jQuery('.pagepost-expand').show();
  jQuery('.pagepost-collapse').hide();
  jQuery('.pagepost-toggle-pane').hide();

  jQuery('.pagepost-toggle-button').click(function() {
    jQuery('.pagepost-toggle-pane').toggle();
    jQuery('.pagepost-expand').toggle();
    jQuery('.pagepost-collapse').toggle();
  });

  jQuery('.twitter-expand').show();
  jQuery('.twitter-collapse').hide();
  jQuery('.twitter-toggle-pane').hide();

  jQuery('.twitter-toggle-button').click(function() {
    jQuery('.twitter-toggle-pane').toggle();
    jQuery('.twitter-expand').toggle();
    jQuery('.twitter-collapse').toggle();
  });

  jQuery('.social-expand').show();
  jQuery('.social-collapse').hide();
  jQuery('.social-toggle-pane').hide();

  jQuery('.social-toggle-button').click(function() {
    jQuery('.social-toggle-pane').toggle();
    jQuery('.social-expand').toggle();
    jQuery('.social-collapse').toggle();
  });


  jQuery('.keyword-expand').show();
  jQuery('.keyword-collapse').hide();
  jQuery('.keyword-toggle-pane').hide();

  jQuery('.keyword-toggle-button').click(function() {
    jQuery('.keyword-toggle-pane').toggle();
    jQuery('.keyword-expand').toggle();
    jQuery('.keyword-collapse').toggle();
  });

  jQuery('.public-expand').show();
  jQuery('.public-collapse').hide();
  jQuery('.public-toggle-pane').hide();

  jQuery('.public-toggle-button').click(function() {
    jQuery('.public-toggle-pane').toggle();
    jQuery('.public-expand').toggle();
    jQuery('.public-collapse').toggle();
  });

  jQuery('.global-expand').show();
  jQuery('.global-collapse').hide();
  jQuery('.global-toggle-pane').hide();

  jQuery('.global-toggle-button').click(function() {
    jQuery('.global-toggle-pane').toggle();
    jQuery('.global-expand').toggle();
    jQuery('.global-collapse').toggle();
  });

  if (jQuery('.pretty-link-keyord-replacement-is-on-checkbox').is(':checked')) {
    jQuery('.pretty-link-keyword-replacement-options').show();
  }
  else {
    jQuery('.pretty-link-keyword-replacement-options').hide();
  }

  jQuery('.pretty-link-keyord-replacement-is-on-checkbox').change(function() {
    if (jQuery('.pretty-link-keyord-replacement-is-on-checkbox').is(':checked')) {
      jQuery('.pretty-link-keyword-replacement-options').show();
    }
    else {
      jQuery('.pretty-link-keyword-replacement-options').hide();
    }
  });

  if (jQuery('.prli-set-keyword-replacement-thresholds-checkbox').is(':checked')) {
    jQuery('.prli-set-replacement-thresholds').show();
  }
  else {
    jQuery('.prli-set-replacement-thresholds').hide();
  }

  jQuery('.prli-set-keyword-replacement-thresholds-checkbox').change(function() {
    if (jQuery('.prli-set-keyword-replacement-thresholds-checkbox').is(':checked')) {
      jQuery('.prli-set-replacement-thresholds').show();
    }
    else {
      jQuery('.prli-set-replacement-thresholds').hide();
    }
  });

  if (jQuery('.prli-keyword-enable-content-cache-checkbox').is(':checked')) {
    jQuery('.prli-keyword-enable-content-cache').show();
  }
  else {
    jQuery('.prli-keyword-enable-content-cache').hide();
  }

  jQuery('.prli-keyword-enable-content-cache-checkbox').change(function() {
    if (jQuery('.prli-keyword-enable-content-cache-checkbox').is(':checked')) {
      jQuery('.prli-keyword-enable-content-cache').show();
    }
    else {
      jQuery('.prli-keyword-enable-content-cache').hide();
    }
  });

  if (jQuery('.pretty-link-post-checkbox').is(':checked')) {
    jQuery('.pretty-link-post-dropdown').show();
    jQuery('.pretty-link-twitter-post-button').show();
    jQuery('.pretty-link-twitter-post-comments').show;
    jQuery('.pretty-link-twitter-auto-post-post').show();
    jQuery('.pretty-link-social-post-buttons').show()
  }
  else {
    jQuery('.pretty-link-post-dropdown').hide();
    jQuery('.pretty-link-twitter-post-button').hide();
    jQuery('.pretty-link-twitter-post-comments').hide();
    jQuery('.pretty-link-twitter-auto-post-post').hide();
    jQuery('.pretty-link-social-post-buttons').hide()
  }

  jQuery('.pretty-link-post-checkbox').change(function() {
    if (jQuery('.pretty-link-post-checkbox').is(':checked')) {
      jQuery('.pretty-link-post-dropdown').show();
      jQuery('.pretty-link-twitter-post-button').show();
      jQuery('.pretty-link-twitter-post-comments').show();
      jQuery('.pretty-link-twitter-auto-post-post').show();
      jQuery('.pretty-link-social-post-buttons').show()
    }
    else {
      jQuery('.pretty-link-post-dropdown').hide();
      jQuery('.pretty-link-twitter-post-button').hide();
      jQuery('.pretty-link-twitter-post-comments').hide();
      jQuery('.pretty-link-twitter-auto-post-post').hide();
      jQuery('.pretty-link-social-post-buttons').hide()
    }
  });


  if (jQuery('.pretty-link-page-checkbox').is(':checked')) {
    jQuery('.pretty-link-page-dropdown').show();
    jQuery('.pretty-link-twitter-page-button').show();
    jQuery('.pretty-link-twitter-page-comments').show();
    jQuery('.pretty-link-twitter-auto-page-page').show();
    jQuery('.pretty-link-social-page-buttons').show()
  }
  else {
    jQuery('.pretty-link-page-dropdown').hide();
    jQuery('.pretty-link-twitter-page-button').hide();
    jQuery('.pretty-link-twitter-page-comments').hide();
    jQuery('.pretty-link-twitter-auto-page-page').hide();
    jQuery('.pretty-link-social-page-buttons').hide()
  }

  jQuery('.pretty-link-page-checkbox').change(function() {
    if (jQuery('.pretty-link-page-checkbox').is(':checked')) {
      jQuery('.pretty-link-page-dropdown').show();
      jQuery('.pretty-link-twitter-page-button').show();
      jQuery('.pretty-link-twitter-page-comments').show();
      jQuery('.pretty-link-twitter-auto-page-page').show();
      jQuery('.pretty-link-social-page-buttons').show()
    }
    else {
      jQuery('.pretty-link-page-dropdown').hide();
      jQuery('.pretty-link-twitter-page-button').hide();
      jQuery('.pretty-link-twitter-page-comments').hide();
      jQuery('.pretty-link-twitter-auto-page-page').hide();
      jQuery('.pretty-link-social-page-buttons').hide()
    }
  });

  if (jQuery('.allow-public-link-creation-checkbox').is(':checked')) {
    jQuery('.use-public-link-display-page').show();
  }
  else {
    jQuery('.use-public-link-display-page').hide();
  }

  jQuery('.allow-public-link-creation-checkbox').change(function() {
    if (jQuery('.allow-public-link-creation-checkbox').is(':checked')) {
      jQuery('.use-public-link-display-page').show();
    }
    else {
      jQuery('.use-public-link-display-page').hide();
    }
  });

  if (jQuery('.use-public-link-display-page-checkbox').is(':checked')) {
    jQuery('.public-link-creation-display-page').show();
  }
  else {
    jQuery('.public-link-creation-display-page').hide();
  }

  jQuery('.use-public-link-display-page-checkbox').change(function() {
    if (jQuery('.use-public-link-display-page-checkbox').is(':checked')) {
      jQuery('.public-link-creation-display-page').show();
    }
    else {
      jQuery('.public-link-creation-display-page').hide();
    }
  });

  if (jQuery('.use-prettylink-url-checkbox').is(':checked')) {
    jQuery('.prettylink-url').show();
  }
  else {
    jQuery('.prettylink-url').hide();
  }

  jQuery('.use-prettylink-url-checkbox').change(function() {
    if (jQuery('.use-prettylink-url-checkbox').is(':checked')) {
      jQuery('.prettylink-url').show();
    }
    else {
      jQuery('.prettylink-url').hide();
    }
  });

  //jQuery('.add-twit-creds').click(function() {
  //});
});

function add_twit_creds()
{
  var index_val = parseInt(new Date().getTime().toString().substring(0, 10)) + jQuery('.twit-creds-list').children().size();
  var html_str = '<li><?php _e('Twitter Username', 'pretty-link'); ?>*:&nbsp;@<input type="text" name="prli_twitter_alt_creds[' + index_val + '][username]"/>&nbsp;&nbsp;<?php _e('Password:', 'pretty-link'); ?>&nbsp;<input type="password" name="prli_twitter_alt_creds[' + index_val + '][password]"/>&nbsp;<a href="#" onclick="javascript:jQuery(this).parent().remove();">[x]</a></li>'; 
  jQuery('.twit-creds-list').append(html_str);
}
  
</script>

<style type="text/css">
.toggle {
  cursor: pointer;
}

#option-pane {
  padding-left: 15px;
}

td.social-button-checkbox {
  padding: 0px;
  margin: 0px;
  padding-right: 3px;
}

td.social-button-image {
  padding: 0px;
  margin: 0px;
  padding-right: 10px;
}

</style>
