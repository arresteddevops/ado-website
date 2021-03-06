<?php
/*
 * CUSTOM POST TYPE TEMPLATE
 *
 * This is the custom post type post template. If you edit the post type name, you've got
 * to change the name of this template to reflect that name change.
 *
 * For Example, if your custom post type is "register_post_type( 'bookmarks')",
 * then your single template should be single-bookmarks.php
 *
 * Be aware that you should rename 'custom_cat' and 'custom_tag' to the appropiate custom
 * category and taxonomy slugs, or this template will not finish to load properly.
 *
 * For more info: http://codex.wordpress.org/Post_Type_Templates
*/
?>

<?php get_header(); ?>

<div id="content">

  <div id="inner-content" class="wrap cf">

    <div id="main" class="m-all t-2of3 d-5of7 cf" role="main">

      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <?php
        $post_id = get_the_ID();
        $episode_number = get_post_meta($post_id, 'episode_number', true);
        if ($episode_number == NULL)
        {
          $episode_title = the_title("","",FALSE);
        }
        else
        {
          $episode_title = "Episode Number " . $episode_number . " - ". the_title("","",FALSE);
        }
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article">

          <header class="article-header">

            <h1 class="single-title custom-post-type-title"><?= $episode_title ?></h1>

          </header>

          <section class="entry-content cf">
          <?php the_post_thumbnail('bones-thumb-500-square'); ?>
          <?php $episode_summary = get_post_meta($post_id, '_cmb2_ado_summary', true); ?>
          <?php $episode_shownotes  = get_post_meta($post_id, '_cmb2_ado_show_notes', true); ?>
          <?php echo( wpautop( $episode_summary ) ); ?>
          <?php //the_excerpt(); ?>
          <?php 
            if ($episode_shownotes <> NULL){

              echo do_shortcode('[powerpress]');
              echo "<h2>Show Notes</h2>";
              echo( wpautop( $episode_shownotes ) );
              //the_content();
            }
            ?>


            <?php

            //$guestlist = get_post_meta($post_id, 'guest', $single = false);
            //if($guestlist <> NULL){
            //echo "<h2>Guests</h2>";
            // foreach ($guestlist as $guest) {
            //    echo do_shortcode('[wp_biographia user="' . $guest . '"]');
            //  }
            //}
            ?>

            <h2>Guests</h2>
            <?php $coauthors = get_coauthors(); ?>
            <?php foreach( $coauthors as $coauthor ) : ?>
            <?php if ($coauthor->user_level <> '10' ) {  ?>
            <?php echo do_shortcode('[wp_biographia user="' . $coauthor->nickname . '"]');}?>
            <?php endforeach; ?>
            
            <?php
              global $post;
              $checkout = get_post_meta( $post->ID, '_cmb2_ado_checkouts', true );
              if ($checkout <> NULL){
                echo('<h2>Check Outs</h2>');
                echo( wpautop( $checkout ) );
              } ?>

              <hr />

              <h3>Thanks to our awesome sponsors!</h3>

              <?php
              $sponsor_1_url =  get_post_meta( $post->ID, '_cmb2_ado_sponsor_1_url', true );
              $sponsor_1_banner =  get_post_meta( $post->ID, '_cmb2_sponsor_1_banner', true );
              $sponsor_1_text = get_post_meta( $post->ID, '_cmb2_ado_sponsor_1_text', true );
              $sponsor_2_url =  get_post_meta( $post->ID, '_cmb2_ado_sponsor_2_url', true );

              $sponsor_2_banner =  get_post_meta( $post->ID, '_cmb2_ado_sponsor_2_banner', true );
              $sponsor_2_text = get_post_meta( $post->ID, '_cmb2_ado_sponsor_2_text', true );
              if ($sponsor_1_banner <> NULL){
                echo('<a href = "' . $sponsor_1_url . '"><img src ="' . $sponsor_1_banner . '"></a>');
              }

              if ($sponsor_2_banner <> NULL){
                echo('<a href = "' . $sponsor_2_url . '"><img src ="' . $sponsor_2_banner . '"></a>');
              }
              ?>

              <hr />
            
          </section> <!-- end article section -->

          <footer class="article-footer">
            <p class="tags"><?php echo get_the_term_list( get_the_ID(), 'custom_tag', '<span class="tags-title">' . __( 'Custom Tags:', 'bonestheme' ) . '</span> ', ', ' ) ?></p>

          </footer>

          <?php comments_template(); ?>

        </article>

      <?php endwhile; ?>

    <?php else : ?>

      <article id="post-not-found" class="hentry cf">
        <header class="article-header">
          <h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
        </header>
        <section class="entry-content">
          <p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
        </section>
        <footer class="article-footer">
          <p><?php _e( 'This is the error message in the single-custom_type.php template.', 'bonestheme' ); ?></p>
        </footer>
      </article>

    <?php endif; ?>

  </div>

  <?php get_sidebar(); ?>

</div>

</div>

<?php get_footer(); ?>
