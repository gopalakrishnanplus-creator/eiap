<?php get_header();  ?>
<style>
  .custom-container {
   max-width: 1140px;
   margin: auto;
   padding: 0px 15px;
   }
   .medical-section {
   background-color: #f2f2f2;
   padding: 50px 0px;
   }
   .medical-section-2 {
   background-color: #fff;
   padding: 50px 0px;
   }
   .custom-row {
   display: flex;
   flex-wrap: wrap;
   margin: 0px -15px;
   }
   .col-6 {
   width: 50%;
   padding: 0px 15px;
   }
   .img-fluid {
   max-width: 100%;
   }
   @media(max-width:768px) {
   .medical {
   padding-bottom: 25px;
   }
   .revert-on-mobile {
   flex-direction: column-reverse;
   }
   .col-6 {
   width: 100%;
   }
   }
</style>

<section class="medical-section mb-5" style="padding-top: 150px;">
   <div class="custom-container">
      <div class="custom-row f-image">
         <div class="col-6" align="left">
         <iframe src="https://player.vimeo.com/video/<?php the_field('add_video_url'); ?>" width="100%" height="360" frameborder="0"
			  allow="autoplay; fullscreen" allowfullscreen></iframe>
         </div>
         <div class="col-6">
            <div class="medical">
               <h2><?php the_title(); ?></h2>
               <p><?php echo wp_trim_words( get_the_content(), 5525); ?></p>
				 <?php
//                                  $category = get_the_terms( $post->ID, 'video_category' );
//
//                                  foreach($category as $cats)
//                                  {
                                   ?>
                              <h6 class="project-item-category"><?php// echo $cats->name;  ?></h6>
                              <?php// } ?>
            </div>
         </div>
      </div>
   </div>
</section>

<?php get_footer();  ?>