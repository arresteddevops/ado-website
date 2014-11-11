<?php
/*
 Template Name: Blog Page
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/
?>

<?php get_header(); ?>

      <div id="content">

        <div id="inner-content" class="wrap cf">

            <div id="main" class="m-all t-2of3 d-5of7 cf" role="main">
              <?php
              $args=array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'ignore_sticky_posts'=> 1
              );
              $my_query = null;
              $my_query = new WP_Query($args);
              if( $my_query->have_posts() ) {
                while ($my_query->have_posts()) : $my_query->the_post(); ?>
                  <p><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></p>
                  <?php
                the_excerpt();
                endwhile;
              }
              wp_reset_query();  // Restore global post data stomped by the_post().
              ?>

            </div>

          <?php get_sidebar(); ?>

        </div>

      </div>

<?php get_footer(); ?>
