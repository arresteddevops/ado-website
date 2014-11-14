<?php
/*
 Template Name: ADO Home Page
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

            <div id="main" class="span_9 cf" role="main">

            <div class="row">

              <?php
              $args=array(
                'post_type' => 'ado_episode',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'ignore_sticky_posts'=> 1
              );
              $my_query = null;
              $my_query = new WP_Query($args);
              if( $my_query->have_posts() ) {
 
                while ($my_query->have_posts()) : $my_query->the_post();
                 
                echo('<!-- individual post -->');
                $post_id = get_the_ID();
                $episode_summary = get_post_meta($post_id, '_cmb2_ado_summary', true); 

                ?>


                <div id="post" class="span_4 col" role="post">
                  <?php the_post_thumbnail('bones-thumb-200-square'); ?>
                  <br />
                  <b><?php the_title(); ?></b>
                  <?php echo( wpautop( $episode_summary ) ); ?>
                </div>  
                  <?php
                //end the individual part
                endwhile;
              }
              wp_reset_query();  // Restore global post data stomped by the_post().
              ?>
              </div>
            </div>

          <?php get_sidebar(); ?>

        </div>

      </div>

<?php get_footer(); ?>
