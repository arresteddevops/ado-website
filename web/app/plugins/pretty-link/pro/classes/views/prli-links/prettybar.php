<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="description" content="<?php echo stripslashes($link->description); ?>" />
  <title><?php echo stripslashes($link->name); ?></title>
<style type="text/css">
html, body {
  margin: 0px;
  padding: 0px;
<?php if(!empty($bar_background_image) and $bar_background_image): ?>
  background-image: url(<?php echo $bar_background_image; ?>);
  background-repeat: repeat-x;
<?php else: ?>
  background-color: #<?php echo $bar_color; ?>;
<?php endif; ?>
  color: #<?php echo $bar_text_color; ?>;
}

#prettybar {
  position: fixed;
  top: 0;
  padding: 0px;
  margin: 0px;
  width: 100%;
  height: 65px;
  border-bottom: 2px solid black;
}

.baritems {
  margin-top: 0px;
  padding: 0px;
}

.blog-title {
  padding-top: 5px;
  margin: 0px;
  width: 200px;
}

h1,h2,h3,h4,p {
  font-family: Arial;
  padding: 0px;
  margin: 0px;
}

a {
  color: #<?php echo $bar_link_color; ?>;
  text-decoration: none;
}

a:visited {
  color: #<?php echo $bar_visited_color; ?>;
}

a:hover {
  color: #<?php echo $bar_hover_color; ?>;
}

.map {
  background-image: url(<?php echo PRLI_IMAGES_URL; ?>/bar_map.png);
  background-repeat: no-repeat;
}

.closebutton {
  background-position: -200px 0;
  height: 20px;
  width: 20px;
  overflow: hidden;
  /*text-indent: -999em;*/
  cursor: pointer;
  text-align: right;
  float: right;
}

.pb-cell {
  white-space: nowrap;
  overflow: hidden;
}

.right_container {
  float: right;
  margin-top: 8px;
  margin-right: 8px;
  text-align: right;
}

.closebutton:hover {
  background-position: -200px -30px;
}

.closebutton:active {
  background-position: -200px -60px;
}

ul.baritems li {
  display: inline;
  /*float: left;*/
  /*padding-left: 15px;*/
}

.retweet {
  padding-top: 5px;
  padding-left: 15px;
  line-height: 26px;
  width: 200px;
}

.blog-image {
  padding-top: 7px;
  padding-left: 5px;
  padding-right: 5px;
  width: 50px;
}

.small-text {
  font-size: 10px;
}

.powered-by {
  padding-top: 15px;
  text-align: right;
}

/*
td {
  border: 1px solid black;
}
*/
</style>
</head>
<body>
  <div id="prettybar">
    <table width="100%" height="65px">
      <tr>
      <td class="blog-image" valign="top">
        <div class="pb-cell">
        <a href="<?php echo $prli_blogurl; ?>" target="_top"><img src="<?php echo $bar_image; ?>" width="48px" height="48px" border="0"/></a></div>
      </td>
      <td class="blog-title" valign="top">
        <div class="pb-cell">
          <h2>
          <?php if( $bar_show_title ) { ?>
          <a href="<?php echo $prli_blogurl; ?>" title="<?php echo $shortened_title; ?>" target="_top"><?php echo $shortened_title; ?></a>
          <?php } else echo "&nbsp;"; ?>
          </h2> 
          <?php if( $bar_show_description ) { ?>
          <p title="<?php echo $prli_blogdescription; ?>"><?php echo $shortened_desc; ?></p> 
          <?php } else echo "&nbsp;"; ?>
        </div>
      </td>
      <td class="retweet" valign="top">
        <div class="pb-cell">
          <h4>
          <?php if( $bar_show_target_url_link ) { ?>
            <a href="<?php echo $target_url; ?>" title="You're viewing: <?php echo $target_url; ?>" target="_top">Viewing: <?php echo $shortened_link; ?></a>
          <?php } else echo "&nbsp;"; ?>
          </h4>
          <h4>
          <?php if( $bar_show_share_links ) { ?>
            <a href="http://twitter.com/home?status=<?php echo urlencode( $prli_blogurl . PrliUtils::get_permalink_pre_slug_uri() . $slug ); ?>" target="_top">Share on Twitter</a>
          <?php } else echo "&nbsp;"; ?>
          </h4> 
        </div>
      </td>
      <td valign="top">
        <div class="pb-cell right_container">
          <table width="100%" cellpadding="0" cellspacing="0" style="padding: 0px; margin: 0px;">
            <tr>
              <td>
                <p class="map closebutton"><a href="<?php echo $target_url; ?>" target="_top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></p>
              </td>
            <tr>
              <td>
              <?php
                $link_html =<<<LINKHTML
<p class="powered-by small-text"><?php _e('Powered by', 'pretty-link'); ?> <a href="http://blairwilliams.com/pl" target="_top"><img src="<?php echo PRLI_IMAGES_URL; ?>/pretty-link-small.png" width="12px" height="12px" border="0"/> <?php _e('Pretty Link', 'pretty-link'); ?></a></p>
LINKHTML;
                echo apply_filters('prli-display-attrib-link',$link_html);
              ?>
              </td>
            </tr>
          </table>
        </div>
      </td>
      </tr>
    </table>
  </div>
</div>
</body>
</html>
