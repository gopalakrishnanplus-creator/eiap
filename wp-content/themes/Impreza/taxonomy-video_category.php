<?php get_header();
   $obj = get_queried_object();   ?>
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
   }.video-cols {
   width: calc(50% - 20px);
   margin: 0px 10px;
   margin-bottom: 20px;
   }.video-box {
   border: 1px solid rgba(0,0,0,.08);
   box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
   border-radius: 0.25rem!important;
   padding: 10px;
   }a.video_link {
   text-align: center;
   display: block;
   color: #000;
   font-size: 20px;
   display:none;
   }
   @media(max-width:768px){
   .video-cols {
   width: calc(100% - 20px);
   margin: 0px 10px;
   margin-bottom: 20px;
   }}

   nav.sw-pagination {
    display: flex;
    justify-content: center;
	padding-bottom: 20px;
}
</style>
<div class="video-container">
   <h2 class="video-cat-title">
      <?php 
         echo $obj->name ;
           ?>
   </h2>
   <div class="video_row">
      <?php 
       $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
         $args = array(  
            'post_type' => 'video',
            'post_status' => 'publish',
            'order'     =>'ASC',
             'paged'         => $paged,
            'posts_per_page' => 20, 
            'post_parent' => 0,
            'tax_query'         => array(
                array(
                'taxonomy' => 'video_category',
                'field' => 'slug',
                'terms' => $obj,
                )      )   );
            
            $loop = new WP_Query( $args ); 
              if ($loop->have_posts()) :
            
            while ( $loop->have_posts() ) : $loop->the_post(); 
             
            ?>
      <div class="video-cols">
         <div class="video-box">
            <iframe src="https://player.vimeo.com/video/<?php the_field('add_video_url'); ?>" width="100%" height="360" frameborder="0"
               allow="autoplay; fullscreen" allowfullscreen></iframe>
            <a class="video_link" href="<?php the_permalink(); ?>">
            <?php the_title(); ?>
            </a>
         </div>
      </div>
      <?php   //   endwhile;  wp_reset_postdata(); ?> <?php   endwhile; ?>
   </div>

    <div class="pagination">
                    
                        <div class="vedhak-pagination text-center mt-30 wow fadeInUp">
                        <?php
                      echo "<ul>";
                      echo "<nav class=\"sw-pagination\">";
    $big = 999999999; // need an unlikely integer
    echo paginate_links( array(
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format' => '?paged=%#%',
        'current' => max( 1, get_query_var('paged') ),
        'total' => $loop->max_num_pages
    ) );
    echo "</nav>";
    echo "</ul>";
endif;

wp_reset_query(); ?> </div> 
                        
                  
                </div>







   <?php 
      $cat_children = get_term_children( $obj->term_id, 'video_category' );
      if($cat_children){ 
          foreach($cat_children as $cat_id){
              $category = get_term( $cat_id, 'video_category' );    ?>
   <h2 class="video-cat-title">
      <?php 
         echo $category->name ;
           ?>
   </h2>
   <div class="video_row">
      <?php 
         $args = array(  
            'post_type' => 'video',
            'post_status' => 'publish',
            'order'     =>'ASC',
            'posts_per_page' => -1, 
            'tax_query'         => array(
                array(
                'taxonomy' => 'video_category',
                'field' => 'slug',
                'terms' => $category->slug,
                )      )   );
            
            $loop = new WP_Query( $args ); 
            
            while ( $loop->have_posts() ) : $loop->the_post(); 
           //$add_video_url=get_field('add_video_url');
			  
            ?>
      <div class="video-cols">
         <div class="video-box">
        <?php if( get_field('add_video_url') ){ ?>  
            <iframe src="https://player.vimeo.com/video/<?php the_field('add_video_url'); ?>" width="100%" height="360" frameborder="0"
               allow="autoplay; fullscreen" allowfullscreen></iframe>
         <?php    } else {   ?>  
                     <iframe width="560" height="315" src="https://www.youtube.com/embed/9xwazD5SyVg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                
                  <?php } ?>
            <a class="video_links" href="<?php the_permalink(); ?>">
            <?php the_title(); ?>
            </a>
         </div>
      </div>
      <?php      endwhile;  wp_reset_postdata(); ?>
   </div>
   <?php } }?>
</div>
<?php get_footer();  ?>