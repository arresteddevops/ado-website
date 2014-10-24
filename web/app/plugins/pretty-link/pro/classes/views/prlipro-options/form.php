<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');
?>

<div class="wrap">
<?php echo PrliAppHelper::page_title(__('Options', 'pretty-link')); ?>
<?php do_action('prlipro-options-message'); ?>
<p><a href="<?php echo admin_url("admin.php?page=pretty-link"); ?>">&laquo <?php _e('Pretty Link Admin', 'pretty-link'); ?></a>&nbsp;|&nbsp;<a href="http://prettylinkpro.com/user-manual"><?php _e('User Manual', 'pretty-link'); ?></a></p>

<form name="pagepost_form" method="post" action="<?php echo admin_url("admin.php?page=pretty-link/pro/prlipro-options.php"); ?>">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<?php wp_nonce_field('update-options'); ?>

<h3><a class="toggle keyword-toggle-button"><?php _e('Keyword & URL Replacement Options', 'pretty-link'); ?> <span class="keyword-expand" style="display: none;">[+]</span><span class="keyword-collapse">[-]</span></a></h3>
<div class="keyword-toggle-pane">
<div id="option-pane">
<ul style="list-style-type: none;">
  <li>
    <input class="pretty-link-keyord-replacement-is-on-checkbox" type="checkbox" name="<?php echo $keyword_replacement_is_on; ?>" <?php echo (($prlipro_options->keyword_replacement_is_on != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Enable Keywords and URL Pretty Link Automatic Replacement', 'pretty-link'); ?>
    <br/><span class="description"><?php _e('If checked, this will enable you to automatically replace keywords and/or URLs on your blog with pretty links. You will specify the specific keywords and urls from your Pretty Link edit page.', 'pretty-link'); ?></span>
    <div class="pretty-link-keyword-replacement-options">
      <div id="option-pane">
        <ul style="list-style-type: none; padding-top: 15px;">
          <li>
            <input class="prli-set-keyword-replacement-thresholds-checkbox" type="checkbox" name="<?php echo $set_keyword_thresholds; ?>" <?php echo (($prlipro_options->set_keyword_thresholds != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Set Keyword Replacement Thresholds', 'pretty-link'); ?>
            <br/><span class="description"><?php _e('Do not want to have too many keyword replacements per page? Select to set some reasonable keyword replacement thresholds.', 'pretty-link'); ?></span>
          </li>
        </ul>
        <div id="option-pane">
          <table class="form-table prli-set-replacement-thresholds">
            <tr class="form-field">
              <td valign="top" width="15%"><?php echo __("Maximum Keywords per Page*:", 'pretty-link').' '.$keywords_per_page; ?> </td>
              <td width="85%" class="pretty-link-keywords-per-page-input">
                <input type="text" name="<?php echo $keywords_per_page; ?>" value="<?php echo $prlipro_options->keywords_per_page; ?>" style="width: 35px;"/> <span class="description"><?php _e('Maximum number of unique keyword / keyphrases you can replace with Pretty Links per page.', 'pretty-link'); ?></span>
              </td>
            </tr>
            <tr class="form-field">
              <td valign="top" width="15%"><?php echo __("Maximum Replacements per Keyword per Page*:", 'pretty-link').' '.$keywords_per_page; ?> </td>
              <td width="85%" class="pretty-link-keyword-links-per-page-input">
                <input type="text" name="<?php echo $keyword_links_per_page; ?>" value="<?php echo $prlipro_options->keyword_links_per_page; ?>" style="width: 35px;"/> <span class="description"><?php _e('Maximum number of Pretty Link replacements per Keyword / Keyphrase per page.', 'pretty-link'); ?></span>
              </td>
            </tr>
          </table>
        </div>
        <ul style="list-style-type: none;">
          <li>
            <input type="checkbox" name="<?php echo $keyword_links_open_new_window; ?>" <?php echo (($prlipro_options->keyword_links_open_new_window != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Open Keyword Pretty Links in a new Window', 'pretty-link'); ?>
            <br/><span class="description"><?php _e('Ensure that these keyword replacement links are opened in a separate window. <strong>Note:</strong> This does not apply to url replacements--only keyword replacements.', 'pretty-link'); ?></span>
          </li>
          <li>
            <input type="checkbox" name="<?php echo $keyword_links_nofollow; ?>" <?php echo (($prlipro_options->keyword_links_nofollow != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Add the html nofollow attribute to all Keyword Pretty Links', 'pretty-link'); ?>
            <br/><span class="description"><?php _e('This adds the html <code>NOFOLLOW</code> attribute to all keyword replacement links. <strong>Note:</strong> This does not apply to url replacements--only keyword replacements.', 'pretty-link'); ?></span>
          </li>
        </ul>
        <table class="form-table">
          <tr class="form-field">
            <td valign="top" width="15%"><?php echo __("Custom CSS Styling for your Keyword Replacements:", 'pretty-link').' '.$keywords_per_page; ?> </td>
            <td width="85%" class="pretty-link-keyword-link-custom-css-input">
              <input type="text" name="<?php echo $keyword_link_custom_css; ?>" value="<?php echo $prlipro_options->keyword_link_custom_css; ?>" />
              <br/><span class="description"><?php _e('Add some custom formatting to your keyword pretty links. <strong>Note:</strong> This does not apply to url replacements--only keyword replacements.', 'pretty-link'); ?></span>
            </td>
          </tr>
          <tr class="form-field">
            <td valign="top" width="15%"><?php echo __("Custom Hover CSS Styling for your Keyword Replacements:", 'pretty-link').' '.$keywords_per_page; ?> </td>
            <td width="85%" class="pretty-link-keyword-link-hover-custom-css-input">
              <input type="text" name="<?php echo $keyword_link_hover_custom_css; ?>" value="<?php echo $prlipro_options->keyword_link_hover_custom_css; ?>" />
              <br/><span class="description"><?php _e('Add some custom formatting to the hover attribute of your keyword pretty links. <strong>Note:</strong> This does not apply to url replacements--only keyword replacements.', 'pretty-link'); ?></span>
            </td>
          </tr>
        </table>
        <ul style="list-style-type: none;">
          <li>
            <input type="checkbox" name="<?php echo $replace_urls_with_pretty_links; ?>" <?php echo (($prlipro_options->replace_urls_with_pretty_links != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Replace All non-Pretty Link URLs With Pretty Link URLs', 'pretty-link'); ?>
            <br/><span class="description"><?php _e('This feature will take each url it finds and create or use an existing pretty link pointing to the url and replace it with the pretty link.', 'pretty-link'); ?></span>
          </li>
          <li>
            <input type="checkbox" name="<?php echo $replace_keywords_in_comments; ?>" <?php echo (($prlipro_options->replace_keywords_in_comments != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Replace Keywords and URLs in Comments', 'pretty-link'); ?>
            <br/><span class="description"><?php _e('This option will enable the keyword / URL replacement routine to run in Comments.', 'pretty-link'); ?></span>
          </li>
          <li>
            <input type="checkbox" name="<?php echo $replace_keywords_in_feeds; ?>" <?php echo (($prlipro_options->replace_keywords_in_feeds != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Replace Keywords and URLs in Feeds', 'pretty-link'); ?>
            <br/><span class="description"><?php _e('This option will enable the keyword / URL replacement routine to run in RSS Feeds.<br/><strong>Note:</strong> This option can slow the load speed of your RSS feed -- unless used in conjunction with a caching plugin like W3 Total Cache or WP Super Cache.<br/><strong>Note #2</strong> This option will only work if you have "Full Text" selected in your General WordPress Reading settings.<br/><strong>Note #3:</strong> If this option is used along with "Replace Keywords and URLs in Comments" then your post comment feeds will have keywords replaced in them as well.', 'pretty-link'); ?></span>
          </li>
        </ul>
      </div>
    </div>
  </li>
</ul>
</div>
</div>

<h3><a class="toggle pagepost-toggle-button"><?php _e('Page and Post Options', 'pretty-link'); ?> <span class="pagepost-expand" style="display: none;">[+]</span><span class="pagepost-collapse">[-]</span></a></h3>
<div class="pagepost-toggle-pane" id="option-pane">
<h4><?php _e('Auto Create Pretty Links and Post to Twitter:', 'pretty-link'); ?></h4>
<div id="option-pane">
  <ul style="list-style-type: none;">
    <li>
      <input class="pretty-link-post-checkbox" type="checkbox" name="<?php echo $posts_auto; ?>" <?php echo (($prlipro_options->posts_auto != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Create Pretty Links for Posts', 'pretty-link'); ?>
      <br/><span class="description"><?php _e('Automatically Create a Pretty Link for each of your published Posts', 'pretty-link'); ?></span>
      <ul style="list-style-type: none; padding-left: 15px; padding-top: 10px;">
        <li class="pretty-link-post-dropdown">
          <select name="<?php echo $posts_group; ?>">
            <option value=""><?php _e('None', 'pretty-link'); ?></option>
          <?php
            $groups = prli_get_all_groups();
            if(is_array($groups))
            {
              foreach($groups as $group)
                echo '<option value="' . $group['id'] . '"' . (($prlipro_options->posts_group == $group['id'])?' selected="true"':'') . '>' . $group['name'] . '</option><br/>';
            }
          ?>
          </select>&nbsp;&nbsp;<a href="<?php echo $prli_siteurl; ?>/wp-admin/admin.php?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-groups.php&action=new"><?php _e('Add a New Group', 'pretty-link'); ?></a>
          <br/><span class="description"><?php _e('Group that Post Pretty Links will be automatically added to.', 'pretty-link'); ?></span>
        </li>
        <li class="pretty-link-twitter-post-button">
          <input type="checkbox" name="<?php echo $twitter_posts_button; ?>" <?php echo (($prlipro_options->twitter_posts_button != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Show Tweet Badge on Posts', 'pretty-link'); ?>
        </li>
        <li class="pretty-link-twitter-post-comments">
          <input type="checkbox" name="<?php echo $twitter_posts_comments; ?>" <?php echo (($prlipro_options->twitter_posts_comments != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Show Twitter Comments for Posts', 'pretty-link'); ?>
        </li>
        <li class="pretty-link-social-post-buttons">
          <input type="checkbox" name="<?php echo $social_posts_buttons; ?>" <?php echo (($prlipro_options->social_posts_buttons != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Show Social Network Button Bar on Posts', 'pretty-link'); ?>
        </li>
        <li class="pretty-link-twitter-auto-post-post">
          <input type="checkbox" name="<?php echo $twitter_auto_post_post; ?>" <?php echo (($prlipro_options->twitter_auto_post_post != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Automatically post to Twitter when a Post is published', 'pretty-link'); ?>
        </li>
      </ul>
    </li>
    <br/>
    <li>
      <input class="pretty-link-page-checkbox" type="checkbox" name="<?php echo $pages_auto; ?>" <?php echo (($prlipro_options->pages_auto != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Create Pretty Links for Pages', 'pretty-link'); ?>
      <br/><span class="description"><?php _e('Automatically Create a Pretty Link for each of your published Pages', 'pretty-link'); ?></span>
      <ul style="list-style-type: none; padding-left: 15px; padding-top: 10px;">
        <li class="pretty-link-page-dropdown">
          <select name="<?php echo $pages_group; ?>">
            <option value=""><?php _e('None', 'pretty-link'); ?></option>
          <?php
            $groups = prli_get_all_groups();
            if(is_array($groups))
            {
              foreach($groups as $group)
                echo '<option value="' . $group['id'] . '"' . (($prlipro_options->pages_group == $group['id'])?' selected="true"':'') . '>' . $group['name'] . '</option><br/>';
            }
          ?>
          </select>&nbsp;&nbsp;<a href="<?php echo $prli_siteurl; ?>/wp-admin/admin.php?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-groups.php&action=new"><?php _e('Add a New Group', 'pretty-link'); ?></a>
          <br/><span class="description"><?php _e('Group that Page Pretty Links will be automatically added to.', 'pretty-link'); ?></span>
        </li>
        <li class="pretty-link-twitter-page-button">
          <input type="checkbox" name="<?php echo $twitter_pages_button; ?>" <?php echo (($prlipro_options->twitter_pages_button != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Show Tweet Badge on Pages', 'pretty-link'); ?>
        </li>
        <li class="pretty-link-twitter-page-comments">
          <input type="checkbox" name="<?php echo $twitter_pages_comments; ?>" <?php echo (($prlipro_options->twitter_pages_comments != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Show Twitter Comments for Pages', 'pretty-link'); ?>
        </li>
        <li class="pretty-link-social-page-buttons">
          <input type="checkbox" name="<?php echo $social_pages_buttons; ?>" <?php echo (($prlipro_options->social_pages_buttons != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Show Social Network Button Bar on Pages', 'pretty-link'); ?>
        </li>
        <li class="pretty-link-twitter-auto-page-page">
          <input type="checkbox" name="<?php echo $twitter_auto_post_page; ?>" <?php echo (($prlipro_options->twitter_auto_post_page != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Automatically post to Twitter when a Page is published', 'pretty-link'); ?>
        </li>
      </ul>
    </li>
  </ul>
</div>
</div>

<h3><a class="toggle twitter-toggle-button"><?php _e('Twitter Options', 'pretty-link'); ?> <span class="twitter-expand" style="display: none;">[+]</span><span class="twitter-collapse">[-]</span></a></h3>
<div class="twitter-toggle-pane" id="option-pane">

<h4><?php _e('Authenticated Twitter Accounts:', 'pretty-link'); ?></h4>
<div id="option-pane">
  <ul class="twit-creds-list">
      <?php
        if( !empty($prlipro_options->twitter_oauth_tokens) )
        {
          $index_val = 0;
          foreach($prlipro_options->twitter_oauth_tokens as $oauthtok)
          {
              //( [oauth_token] => ### [oauth_token_secret] => ### [user_id] => ### [screen_name] => ### )
      ?>
      <li>
        <input type="hidden" name="prli_twitter_oauth_tokens[<?php echo $index_val; ?>][oauth_token]" value="<?php echo $oauthtok['oauth_token']; ?>" />
        <input type="hidden" name="prli_twitter_oauth_tokens[<?php echo $index_val; ?>][oauth_token_secret]" value="<?php echo $oauthtok['oauth_token_secret']; ?>" />
        <input type="hidden" name="prli_twitter_oauth_tokens[<?php echo $index_val; ?>][user_id]" value="<?php echo $oauthtok['user_id']; ?>" />
        <input type="hidden" name="prli_twitter_oauth_tokens[<?php echo $index_val; ?>][screen_name]" value="<?php echo $oauthtok['screen_name']; ?>" />
        <?php _e('Twitter Account', 'pretty-link'); ?>:&nbsp;<strong>@<?php echo $oauthtok['screen_name']; ?></strong>&nbsp;&nbsp;<a href="#" onclick="javascript:jQuery(this).parent().remove();">[x]</a>
      </li>
      <?php

            $index_val++;
          }
          
        }
      ?>
   </ul>
   <p><a href="<?php echo get_option('home'); ?>/prli-twitter-oauth/redirect"><img src="<?php echo PRLIPRO_IMAGES_URL; ?>/dark_add_twitter_account.png" /></a><br/>
   <span class="description"><?php _e('To add multiple twitter accounts you must make sure you are logged out of the previous twitter account before adding another (<a href="http://twitter.com" target="_blank">Logout of Twitter Here</a>).', 'pretty-link'); ?></span></p>
</div>

<h4><?php _e('Tweet Badge Placement:', 'pretty-link'); ?></h4><span class="description"><?php _e('This determines where your Tweet Badges should appear in relation to content on Pages and/or Posts. <code>Note:</code> If you want your badges to appear then you must enable them by selecting Create Pretty Links for Posts/Pages and then selecting Show Tweet Badge on Posts/Pages in the options above.', 'pretty-link'); ?></span>
<div id="option-pane">
  <ul style="list-style-type: none;" class="pane">
    <li>
      <input type="radio" name="<?php echo $twitter_badge_placement; ?>" value="top"<?php echo (($prlipro_options->twitter_badge_placement == 'top')?' checked="true"':''); ?>/>&nbsp;<?php _e('Top', 'pretty-link'); ?>
    </li>
    <li>
      <input type="radio" name="<?php echo $twitter_badge_placement; ?>" value="top-left-with-wrap"<?php echo (($prlipro_options->twitter_badge_placement == 'top-left-with-wrap')?' checked="true"':''); ?>/>&nbsp;<?php _e('Top Left with Text Wrap', 'pretty-link'); ?>
    </li>
    <li>
      <input type="radio" name="<?php echo $twitter_badge_placement; ?>" value="top-right-with-wrap"<?php echo (($prlipro_options->twitter_badge_placement == 'top-right-with-wrap')?' checked="true"':''); ?>/>&nbsp;<?php _e('Top Right with Text Wrap', 'pretty-link'); ?>
    </li>
    <li>
      <input type="radio" name="<?php echo $twitter_badge_placement; ?>" value="bottom"<?php echo (($prlipro_options->twitter_badge_placement == 'bottom')?' checked="true"':''); ?>/>&nbsp;<?php _e('Bottom', 'pretty-link'); ?>
    </li>
    <li>
      <input type="radio" name="<?php echo $twitter_badge_placement; ?>" value="none"<?php echo (($prlipro_options->twitter_badge_placement == 'none')?' checked="true"':''); ?>/>&nbsp;<?php _e('None', 'pretty-link'); ?><br/>
      <span class="description"><?php _e('If you select none, you can still show your Twitter badges by manually adding the <code>[tweetbadge]</code> shortcode to your blog posts or <code>&lt;?php the_tweetbadge(); ?&gt;</code> template tag to your WordPress Theme.', 'pretty-link'); ?></span>
    </li>
  </ul>
</div>

<h4><?php _e('Display Twitter Badge in Feed:', 'pretty-link'); ?></h4>
<div id="option-pane">
  <input style="width: 25px; min-width: 25px;" type="checkbox" name="<?php echo $twitter_badge_show_in_feed; ?>" <?php echo (($prlipro_options->twitter_badge_show_in_feed != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Show Twitter Badge in your RSS Feed', 'pretty-link'); ?>
</div>

<h4><?php _e('Twitter Comments Display Options:', 'pretty-link'); ?></h4>
<div id="option-pane">
    <table class="form-table">
      <tr class="form-field">
        <td valign="top" width="15%"><?php _e("Twitter Comments Headline:", $twitter_comments_header , 'pretty-link'); ?> </td>
        <td width="85%">
          <input type="text" name="<?php echo $twitter_comments_header; ?>" value="<?php echo $prlipro_options->twitter_comments_header; ?>"/>
          <br/><span class="description"><?php _e('This is what will display above the Twitter Comments Box if it is enabled in Page and Post Options.', 'pretty-link'); ?></span>
        </td>
      </tr>
      <tr class="form-field">
        <td valign="top" width="15%"><?php _e("Twitter Comments Height:", $twitter_comments_height , 'pretty-link'); ?> </td>
        <td width="85%">
          <input type="text" style="width: 50px;" name="<?php echo $twitter_comments_height; ?>" value="<?php echo $prlipro_options->twitter_comments_height; ?>"/>px
          <br/><span class="description"><?php _e('This is the height (in pixels) of the Twitter Comments Box if it is enabled in Page and Post Options. If it is left blank, there will be no limit to the height.', 'pretty-link'); ?></span>
        </td>
      </tr>
    </table>    
</div>

<h4><?php _e('Main Tweet User', 'pretty-link'); ?></h4>
<div id="option-pane">
    <table class="form-table">
        <tr class="form-field">
          <td valign="top" width="15%"><?php _e("Main Tweet User:" , 'pretty-link'); ?> </td>
          <td width="85%">
            <input type="text" name="<?php echo $twitter_handle; ?>" value="<?php echo $prlipro_options->twitter_handle; ?>"/>
            <br/><span class="description"><?php _e('Modify this to determine what twitter user to send Tweet Button tweets from.', 'pretty-link'); ?></span>
          </td>
        </tr>
    </table>    
</div>

<h4><?php _e('Tweet Hash Tags:', 'pretty-link'); ?></h4>
<div id="option-pane">
    <table class="form-table">
        <tr class="form-field">
          <td valign="top" width="15%"><?php _e("Tweet Hash Tags:", $twitter_hash_tags , 'pretty-link'); ?> </td>
          <td width="85%">
            <input type="text" name="<?php echo $twitter_hash_tags; ?>" value="<?php echo $prlipro_options->twitter_hash_tags; ?>"/>
            <br/><span class="description"><?php _e('Modify this to customize your hash tag for your auto-tweets and re-tweets.', 'pretty-link'); ?></span>
          </td>
        </tr>
    </table>    
</div>
</div>

<h3><a class="toggle social-toggle-button"><?php _e('Social Network Options', 'pretty-link'); ?> <span class="social-expand" style="display: none;">[+]</span><span class="social-collapse">[-]</span></a></h3>
<div class="social-toggle-pane" id="option-pane">
<h4>Social Network Button Bar</h4><span class="description"><?php _e('Select which buttons you want to be visible on the Social Network Button Bar. <code>Note:</code> In order for the Social Network Button Bar to be visible on Pages and or Posts, you must first enable it in the "Page &amp; Post Options" section above.', 'pretty-link'); ?></span>
<div id="option-pane">
  <table>
    <tr>
    <td class="social-button-checkbox">
      <input type="checkbox" name="<?php echo $social_buttons; ?>[delicious]" <?php echo (($prlipro_options->social_buttons['delicious'] == 'on')?'checked="true"':''); ?>/>
    </td>
    <td class="social-button-image">
      <img src="<?php echo PRLI_IMAGES_URL; ?>/delicious_32.png" width="24px" height="24px" title="Display Delicious Button" alt="Display Delicious Button" />
    </td>
    <td class="social-button-checkbox">
      <input type="checkbox" name="<?php echo $social_buttons; ?>[stumbleupon]" <?php echo (($prlipro_options->social_buttons['stumbleupon'] == 'on')?'checked="true"':''); ?>/>
    </td>
    <td class="social-button-image">
      <img src="<?php echo PRLI_IMAGES_URL; ?>/stumbleupon_32.png" width="24px" height="24px" title="Display StumbleUpon Button" alt="Display StumbleUpon Button" />
    </td>
    <td class="social-button-checkbox">
      <input type="checkbox" name="<?php echo $social_buttons; ?>[digg]" <?php echo (($prlipro_options->social_buttons['digg'] == 'on')?'checked="true"':''); ?>/>
    </td>
    <td class="social-button-image">
      <img src="<?php echo PRLI_IMAGES_URL; ?>/digg_32.png" width="24px" height="24px" title="Display Digg Button" alt="Display Digg Button" />
    </td>
    <td class="social-button-checkbox">
      <input type="checkbox" name="<?php echo $social_buttons; ?>[twitter]" <?php echo (($prlipro_options->social_buttons['twitter'] == 'on')?'checked="true"':''); ?>/>
    </td>
    <td class="social-button-image">
      <img src="<?php echo PRLI_IMAGES_URL; ?>/twitter_32.png" width="24px" height="24px" title="Display Twitter Button" alt="Display Twitter Button" />
    </td>
    <td class="social-button-checkbox">
      <input type="checkbox" name="<?php echo $social_buttons; ?>[mixx]" <?php echo (($prlipro_options->social_buttons['mixx'] == 'on')?'checked="true"':''); ?>/>
    </td>
    <td class="social-button-image">
      <img src="<?php echo PRLI_IMAGES_URL; ?>/mixx_32.png" width="24px" height="24px" title="Display Mixx Button" alt="Display Mixx Button" />
    </td>
    <td class="social-button-checkbox">
      <input type="checkbox" name="<?php echo $social_buttons; ?>[technorati]" <?php echo (($prlipro_options->social_buttons['technorati'] == 'on')?'checked="true"':''); ?>/>
    </td>
    <td class="social-button-image">
      <img src="<?php echo PRLI_IMAGES_URL; ?>/technorati_32.png" width="24px" height="24px" title="Display Technorati Button" alt="Display Technorati Button" />
    </td>
    <td class="social-button-checkbox">
      <input type="checkbox" name="<?php echo $social_buttons; ?>[facebook]" <?php echo (($prlipro_options->social_buttons['facebook'] == 'on')?'checked="true"':''); ?>/>
    </td>
    <td class="social-button-image">
      <img src="<?php echo PRLI_IMAGES_URL; ?>/facebook_32.png" width="24px" height="24px" title="Display Facebook Button" alt="Display Facebook Button" />
    </td>
    <td class="social-button-checkbox">
      <input type="checkbox" name="<?php echo $social_buttons; ?>[newsvine]" <?php echo (($prlipro_options->social_buttons['newsvine'] == 'on')?'checked="true"':''); ?>/>
    </td>
    <td class="social-button-image">
      <img src="<?php echo PRLI_IMAGES_URL; ?>/newsvine_32.png" width="24px" height="24px" title="Display NewsVine Button" alt="Display NewsVine Button" />
    </td>
    <td class="social-button-checkbox">
      <input type="checkbox" name="<?php echo $social_buttons; ?>[reddit]" <?php echo (($prlipro_options->social_buttons['reddit'] == 'on')?'checked="true"':''); ?>/>
    </td>
    <td class="social-button-image">
      <img src="<?php echo PRLI_IMAGES_URL; ?>/reddit_32.png" width="24px" height="24px" title="Display Reddit Button" alt="Display Reddit Button" />
    </td>
    <td class="social-button-checkbox">
      <input type="checkbox" name="<?php echo $social_buttons; ?>[linkedin]" <?php echo (($prlipro_options->social_buttons['linkedin'] == 'on')?'checked="true"':''); ?>/>
    </td>
    <td class="social-button-image">
      <img src="<?php echo PRLI_IMAGES_URL; ?>/linkedin_32.png" width="24px" height="24px" title="Display LinkedIn Button" alt="Display LinkedIn Button" />
    </td>
    <td class="social-button-checkbox">
      <input type="checkbox" name="<?php echo $social_buttons; ?>[yahoo]" <?php echo (($prlipro_options->social_buttons['yahoo'] == 'on')?'checked="true"':''); ?>/>
    </td>
    <td class="social-button-image">
      <img src="<?php echo PRLI_IMAGES_URL; ?>/yahoobuzz_32.png" width="24px" height="24px" title="Display Yahoo Button" alt="Display Yahoo Button" />
    </td>
    </tr>
  </table>
</div>
<h4><?php _e('Social Network Button Bar Placement:', 'pretty-link'); ?></h4><span class="description"><?php _e('This determines where your Social Network Button Bar should appear in relation to content on Pages and/or Posts. <code>Note:</code> If you want this bar to appear then you must enable it in the "Page and Post Options" above.', 'pretty-link'); ?></span>
<div id="option-pane">
  <ul style="list-style-type: none;" class="pane">
    <li>
      <input type="radio" name="<?php echo $social_buttons_placement; ?>" value="top"<?php echo (($prlipro_options->social_buttons_placement == 'top')?' checked="true"':''); ?>/>&nbsp;<?php _e('Top', 'pretty-link'); ?>
    </li>
    <li>
      <input type="radio" name="<?php echo $social_buttons_placement; ?>" value="bottom"<?php echo (($prlipro_options->social_buttons_placement == 'bottom')?' checked="true"':''); ?>/>&nbsp;<?php _e('Bottom', 'pretty-link'); ?>
    </li>
    <li>
      <input type="radio" name="<?php echo $social_buttons_placement; ?>" value="top-and-bottom"<?php echo (($prlipro_options->social_buttons_placement == 'top-and-bottom')?' checked="true"':''); ?>/>&nbsp;<?php _e('Top and Bottom', 'pretty-link'); ?>
    </li>
    <li>
      <input type="radio" name="<?php echo $social_buttons_placement; ?>" value="none"<?php echo (($prlipro_options->social_buttons_placement == 'none')?' checked="true"':''); ?>/>&nbsp;<?php _e('None', 'pretty-link'); ?>
    </li>
    <span class="description"><?php _e('If you select none, you can still show your Social Network Buttons by manually adding the <code>[social_buttons_bar]</code> shortcode to your blog posts or <code>&lt;?php the_social_buttons_bar(); ?&gt;</code> template tag to your WordPress Theme.', 'pretty-link'); ?></span>
  </ul>
  <table class="form-table prli-social-buttons-options">
    <tr class="form-field">
      <td valign="top" width="15%" style="padding-left: 0px;"><?php _e("Social Buttons Display Spacing:", $social_buttons_padding , 'pretty-link'); ?> </td>
      <td width="85%" class="pretty-link-social-buttons-padding-input">
        <input type="text" name="<?php echo $social_buttons_padding; ?>" value="<?php echo $prlipro_options->social_buttons_padding; ?>" style="width: 35px;"/>px&nbsp; &nbsp;<span class="description"><?php _e('Determines the spacing (in pixels) between the buttons on the social buttons bar.', 'pretty-link'); ?></span>
      </td>
    </tr>
  </table>
</div>

<h4><?php _e('Display Social Buttons in Feed:', 'pretty-link'); ?></h4>
<div id="option-pane">
  <input style="width: 25px; min-width: 25px;" type="checkbox" name="<?php echo $social_buttons_show_in_feed; ?>" <?php echo (($prlipro_options->social_buttons_show_in_feed != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Show Social Buttons in your RSS Feed', 'pretty-link'); ?>
</div>
</div>

<h3><a class="toggle public-toggle-button"><?php _e('Public Link Creation Options', 'pretty-link'); ?> <span class="public-expand" style="display: none;">[+]</span><span class="public-collapse">[-]</span></a></h3>
<div class="public-toggle-pane" id="option-pane">
  <h4><?php _e('Customize Public Link Creation', 'pretty-link'); ?>&nbsp;&nbsp;<small><a href="http://prettylinkpro.com/user-manual/setup-public-link-creation/"><?php _e('(help)', 'pretty-link'); ?></a></small></h4>
  <div id="option-pane">
    <input class="allow-public-link-creation-checkbox" type="checkbox" name="<?php echo $allow_public_link_creation; ?>" <?php echo (($prlipro_options->allow_public_link_creation != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Allow Public Link Creation on this website
    <br/><span class="description">This option will give you the ability to turn your website into a link shortening service for your users. Once selected, you can enable the Pretty Link Pro Sidebar Widget or just display the link creation form with the <code>[prli_create_form]</code> shortcode in any post or page on your website.', 'pretty-link'); ?></span>
    <div id="option-pane" class="use-public-link-display-page">
      <input class="use-public-link-display-page-checkbox" type="checkbox" name="<?php echo $use_public_link_display_page; ?>" <?php echo (($prlipro_options->use_public_link_display_page != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Use Public Link Display Page
      <br/><span class="description">When a link is created using the public form, the user is typically redirected to a simple page displaying their new pretty link. But, you can specify a page that you want them to be redirected to on your website, using your branding instead by selecting this box and entering the url of the page you want them to go to.', 'pretty-link'); ?></span>
      <div id="option-pane" class="public-link-creation-display-page">
        <span><?php _e('Public Pretty Link Creation Display URL:', 'pretty-link'); ?>&nbsp;&nbsp;<input type="text" name="<?php echo $public_link_display_page; ?>" value="<?php echo $prlipro_options->public_link_display_page; ?>" style="width: 50%;"/>
        <br/><span class="description"><?php _e('To set this up, create a new page on your WordPress site and make sure the <code>[prli_create_display]</code> appears somewhere on this page -- otherwise the link will never get created. Once this page is created, just enter the full URL to it here. Make sure this URL does npt end with a slash (/).', 'pretty-link'); ?></span>
      </div>
    </div>
  </div>
</div>


<h3><a class="toggle global-toggle-button"><?php _e('Global Options', 'pretty-link'); ?> <span class="global-expand" style="display: none;">[+]</span><span class="global-collapse">[-]</span></a></h3>
<div class="global-toggle-pane" id="option-pane">
  <h4><?php _e('Customize your Pretty Links Base URL', 'pretty-link'); ?>&nbsp;&nbsp;<small><a href="http://prettylinkpro.com/user-manual/use-an-alternate-base-url-for-your-pretty-links/"><?php _e('(help)', 'pretty-link'); ?></a></small></h4>
  <div id="option-pane">
    <input class="use-prettylink-url-checkbox" type="checkbox" name="<?php echo $use_prettylink_url; ?>" <?php echo (($prlipro_options->use_prettylink_url != 0)?'checked="true"':''); ?>/>&nbsp;<?php _e('Use an alternate Base Url for your Pretty Links
    <br/><span class="description">You must have another valid domain name pointing to this WordPress install before you enable this option. If you are using this option to just get rid of the www in the beginning of your url that is fine -- just make sure your domain works without the www before enabling this option.', 'pretty-link'); ?></span>
    <div id="option-pane" class="prettylink-url">
      <span><?php _e('Pretty Link Base URL:', 'pretty-link'); ?>&nbsp;&nbsp;<input type="text" name="<?php echo $prettylink_url; ?>" value="<?php echo $prlipro_options->prettylink_url; ?>" style="width: 50%;"/>
      <br/><span class="description"><?php _e('Enter a valid base url that points at this WordPress install. Make sure this URL does not end with a slash (/).', 'pretty-link'); ?></span>
    </div>
  </div>
  <h4><?php _e('Set Minimum Role Required To Access Pretty Link', 'pretty-link'); ?></h4>
  <div id="option-pane">
    <select name="<?php echo $minimum_access_role; ?>">
      <option value="add_users" <?php if($prlipro_options->min_role == 'add_users') echo 'selected="selected"'; ?>><?php _e('Administrator', 'pretty-link'); ?></option>
      <option value="delete_pages" <?php if($prlipro_options->min_role == 'delete_pages') echo 'selected="selected"'; ?>><?php _e('Editor', 'pretty-link'); ?></option>
      <option value="publish_posts" <?php if($prlipro_options->min_role == 'publish_posts') echo 'selected="selected"'; ?>><?php _e('Author', 'pretty-link'); ?></option>
      <option value="edit_posts" <?php if($prlipro_options->min_role == 'edit_posts') echo 'selected="selected"'; ?>><?php _e('Contributor', 'pretty-link'); ?></option>
      <option value="read" <?php if($prlipro_options->min_role == 'read') echo 'selected="selected"'; ?>><?php _e('Subscriber', 'pretty-link'); ?></option>
    </select>
  </div>
</div>
<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Save', 'pretty-link') ?>" />
</p>

</form>
</div>
