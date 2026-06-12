<?php
/**
 * Template Name: Search page
 */
get_header();
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
  .video-container {
    max-width: 1140px;
    margin: auto;
    padding-top: 200px;
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
  .video-about-text {
    margin: 15px 0 0;
    padding: 10px;
    font-size: 16px;
    line-height: 1.6;
    color: #333;
    border: 2px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
    text-align: center;
    font-weight: 700;
  }
  @media (max-width: 768px) {
    .video-cols {
      width: calc(100% - 20px);
    }
  }
  nav.sw-pagination {
    display: flex;
    justify-content: center;
    padding-bottom: 20px;
  }
  .pagination .page-numbers {
    font-size: 1.2rem;
    display: inline-block;
    line-height: 2.5rem;
    height: 2.5rem;
    width: 2.5rem;
    overflow: hidden;
    border-radius: 50%;
    transition: none;
  }
  .pagination .prev, .pagination .next {
    font-size: 18px;
  }
</style>

<div class="video-container">

  <h2 class="video-cat-title">Search Results</h2>

  <?php
  $s = get_search_query();
  $paged = get_query_var('paged') ? get_query_var('paged') : 1;
  $args = [
    's'              => $s,
    'posts_per_page' => 20,
    'paged'          => $paged,
  ];
  $the_query = new WP_Query($args);

  if ( $the_query->have_posts() ) :
    echo '<h5 style="font-weight:bold;color:#000;margin-left:10px;">Search Results for: ' . esc_html( $s ) . '</h5>';
  ?>

    <div class="video_row">
      <?php
      while ( $the_query->have_posts() ) : $the_query->the_post();

        // Only show posts with a Vimeo URL
        if ( get_field('add_video_url') ) : ?>
          <div class="video-cols">
            <div class="video-box">

              <!-- Vimeo embed -->
              <iframe
                src="https://player.vimeo.com/video/<?php the_field('add_video_url'); ?>"
                width="100%" height="360" frameborder="0"
                allow="autoplay; fullscreen" allowfullscreen>
              </iframe>

              <!-- NEW: Post-level “about_video” text -->
              <?php
              $about_post = get_field('about_video');
              if ( $about_post ) {
                echo '<div class="video-about-text">' . wpautop( $about_post ) . '</div>';
              }
              ?>

            </div>
          </div>
        <?php
        endif;

      endwhile;
      ?>
    </div> <!-- .video_row -->

    <!-- Pagination -->
    <div class="pagination">
      <nav class="sw-pagination">
        <?php
        echo paginate_links( [
          'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
          'format'    => '?paged=%#%',
          'current'   => max( 1, $paged ),
          'total'     => $the_query->max_num_pages,
          'mid_size'  => 2,
          'prev_text' => __('« Previous'),
          'next_text' => __('Next »'),
        ] );
        ?>
      </nav>
    </div>

  <?php
  else :
    // No results found
  ?>
    <h2 style="font-weight:bold;color:#000;">Nothing Found</h2>
    <div class="alert alert-info">
      <p>Sorry, but nothing matched your search criteria. Please try again with different keywords.</p>
    </div>
  <?php
  endif;

  // Restore original post data
  wp_reset_postdata();
  ?>

</div> <!-- .video-container -->

<?php get_footer(); ?>
