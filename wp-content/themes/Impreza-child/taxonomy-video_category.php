<?php
/**
 * Template for Video Category Archive (no titles under videos)
 */
get_header();
$obj = get_queried_object();
?>

<style type="text/css">
  h2.video-cat-title {
    font-weight: 600;
    font-size: 30px;
    border-bottom: 1px solid #efefef;
    padding-bottom: 10px;
    margin-bottom: 20px;
    margin-left: 10px;
  }
  /* Boxed, centered & bold About Video text */
  .term-about-video,
  .video-about-text {
    margin: 15px 10px;
    padding: 15px;
    font-size: 16px;
    line-height: 1.6;
    color: #333;

    /* new box styling */
    border: 2px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;

    /* center-align & bold text */
    text-align: center;
    font-weight: 700;
  }
  .video-container {
    max-width: 1140px;
    margin: auto;
    padding-top: 40px;
  }
  .video_row {
    display: flex;
    flex-wrap: wrap;
  }
  .video-cols {
    width: calc(50% - 20px);
    margin: 0 10px 20px;
  }
  .video-box {
    border: 1px solid rgba(0,0,0,.08);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    border-radius: .25rem!important;
    padding: 10px;
  }
  @media(max-width:768px){
    .video-cols { width: calc(100% - 20px); }
  }
  nav.sw-pagination {
    display: flex;
    justify-content: center;
    padding-bottom: 20px;
  }
</style>

<div class="video-container">

  <h2 class="video-cat-title"><?php echo esc_html( $obj->name ); ?></h2>

  <?php
  // Show term-level "about_video"
  $about_term = get_field( 'about_video', $obj );
  if ( $about_term ) {
    echo '<div class="term-about-video">' . wpautop( $about_term ) . '</div>';
  }
  ?>

  <?php
  // Main loop for videos in this category
  $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
  $args = array(
    'post_type'      => 'video',
    'post_status'    => 'publish',
    'order'          => 'DESC',
    'paged'          => $paged,
    'posts_per_page' => 20,
    'post_parent'    => 0,
    'tax_query'      => array(
      array(
        'taxonomy' => 'video_category',
        'field'    => 'slug',
        'terms'    => $obj->slug,
      ),
    ),
  );
  $loop = new WP_Query( $args );
  if ( $loop->have_posts() ) : ?>

    <div class="video_row">
    <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

      <div class="video-cols">
        <div class="video-box">

          <?php if ( get_field('add_video_url') ) : ?>
            <iframe src="https://player.vimeo.com/video/<?php the_field('add_video_url'); ?>" width="100%" height="360" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
          <?php else : ?>
            <iframe width="100%" height="360" src="https://www.youtube.com/embed/<?php the_field('add_youtube_video_url'); ?>" frameborder="0" allowfullscreen></iframe>
          <?php endif; ?>

          <?php
          // Show post-level "about_video" only if present
          $about_post = get_field( 'about_video' );
          if ( $about_post ) {
            echo '<div class="video-about-text">' . wpautop( $about_post ) . '</div>';
          }
          ?>

        </div>
      </div>

    <?php endwhile; ?>
    </div><!-- .video_row -->

    <div class="pagination">
      <nav class="sw-pagination">
        <?php
echo paginate_links( array(
      'base'     => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
      'format'   => '?paged=%#%',
      'current'  => max( 1, get_query_var('paged') ),
      'total'    => $loop->max_num_pages,
        ) );
        ?>
      </nav>
    </div>

  <?php wp_reset_postdata(); endif; ?>

  <?php
  // Display children categories
  $cat_children = get_term_children( $obj->term_id, 'video_category' );
  if ( $cat_children ) {
    foreach ( $cat_children as $child_id ) {
      $category = get_term( $child_id, 'video_category' ); ?>

      <h2 class="video-cat-title"><?php echo esc_html( $category->name ); ?></h2>

      <?php
      // Child-term "about_video"
      $about_child = get_field( 'about_video', $category );
      if ( $about_child ) {
        echo '<div class="term-about-video">' . wpautop( $about_child ) . '</div>';
      }
      ?>

      <div class="video_row">
      <?php
      $args2 = array(
        'post_type'      => 'video',
        'post_status'    => 'publish',
        'order'          => 'ASC',
        'posts_per_page' => -1,
        'tax_query'      => array(
          array(
            'taxonomy' => 'video_category',
            'field'    => 'slug',
            'terms'    => $category->slug,
          ),
        ),
      );
      $loop2 = new WP_Query( $args2 );
      while ( $loop2->have_posts() ) : $loop2->the_post(); ?>

        <div class="video-cols">
          <div class="video-box">

            <?php if ( get_field('add_video_url') ) : ?>
              <iframe src="https://player.vimeo.com/video/<?php the_field('add_video_url'); ?>" width="100%" height="360" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
            <?php else : ?>
              <iframe width="100%" height="360" src="https://www.youtube.com/embed/<?php the_field('add_youtube_video_url'); ?>" frameborder="0" allowfullscreen></iframe>
            <?php endif; ?>

            <?php
            // Post-level about for child loops
            $about_post_child = get_field( 'about_video' );
            if ( $about_post_child ) {
              echo '<div class="video-about-text">' . wpautop( $about_post_child ) . '</div>';
            }
            ?>

          </div>
        </div>

      <?php endwhile; wp_reset_postdata(); ?>
      </div><!-- .video_row -->

    <?php } // endforeach children
  } // endif children ?>

</div><!-- .video-container -->

<?php get_footer();
?>
