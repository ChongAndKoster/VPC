<?php
/*
 * Template Name: Restore Your Vote
 */
?>
<?php get_header(); ?>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    
    <section class="restore-your-vote">
        <div class="container">
            <h1 class="restore-your-vote__headline"><?php the_title(); ?></h1>
            
            <div class="restore-your-vote__content">
                <div class="content__form">
                    <div id="spinner"><img src="<?php echo app_asset_url('img/spinner.gif'); ?>" alt=""></div>
                    <iframe src="https://campaignlegal.org/ryv/iframe<?php echo ! empty($_GET['source']) ? '?source=' . htmlentities($_GET['source']) : ''; ?>" width="100%" height="100%" frameborder="0" onload="document.getElementById('spinner').style.display='none';"></iframe>
                </div>
            </div>
        </div> <!-- .container -->
    </section> <!-- .restore-your-vote -->
    
    <?php get_template_part('partials/info-blocks'); ?>
    
    <?php endwhile; endif; ?>
<?php get_footer(); ?>
