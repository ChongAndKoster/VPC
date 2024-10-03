<?php
/*
 * Template Name: Custom Tool - CivEmbed (CVI)
 */
 get_header();

 $page_banner_type      = get_field('page_banner_type');
 $page_background_color = get_field('page_background_color');
 
 if (have_posts()) : while (have_posts()) : the_post(); ?>

 <?php if ($page_banner_type == 'gradient'): ?>
     <div class="gradient-banner" role="banner">
         <div class="container mw-840px">
             <h1 class="gradient-banner__headline"><?php the_title(); ?></h1>
         </div> <!-- .container -->
     </div> <!-- .gradient-banner -->
 <?php endif; ?>

 <?php if ($page_banner_type == 'image'): ?>
     <div class="image-banner" role="banner" style="background-image: url(<?php echo app_asset_url('img/_image-banner.png'); ?>">
         <div class="container mw-840px">
             <h1 class="image-banner__headline"><?php the_title(); ?></h1>
         </div> <!-- .container -->
     </div> <!-- .image-banner -->
 <?php endif; ?>

 <?php if ($page_banner_type == 'none' || is_null($page_banner_type)): ?>
     <div class="page__header container">
         <h1 class="page__headline"><?php the_title(); ?></h1>
     </div>
 <?php endif; ?>
     
 <div class="
     blocks 
     <?php echo $page_banner_type != 'none' && ! is_null($page_banner_type) ? 'py-6' : ''; ?> 
     <?php echo 'bg-' . $page_background_color; ?> 
 ">
    <?php
        $iframe_src = 'https://civembed.com/r2v-form.html?appUse=ext&appStyle=1&appIntent=vbm&appBranding=false&org=center-for-voter-information&utm_source=center-for-voter-information&utm_medium=embed&utm_campaign=cvi082022';
            if($_GET['lrx']){
                $lrx_var = sanitize_text_field( $_GET['lrx'] );
                $iframe_src = $iframe_src.'&lrx='.$lrx_var;
            }
    ?>

    <iframe id="r2v-iframe" name="r2v-iframe" title="Voter Registration Form" referrerpolicy="unsafe-url" style="display:block;min-height:500px;min-width:90%;margin:auto;padding:0;border:0" src="<?php echo $iframe_src; ?>"></iframe>

    <script src="https://civembed.com/js/iframeResizer.min.js"> </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const childFrame = iFrameResize({
                log: false,
                autoResize: true,
                checkOrigin: false,
                heightCalculationMethod: 'taggedElement'
            }, '#r2v-iframe');
        });
    </script>
    <style>
        .page__headline { display: none; }
        .blocks .block.block--photo-callout {
            min-height: unset;
            height: 50vh !important;
        }
    </style>
 
 </div>
 
 <?php endwhile; endif; ?>
<?php get_footer(); ?>
