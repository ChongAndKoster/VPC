<?php
/*
 * Template Name: Register to Vote (B-Day Option A)
 */
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <?php get_template_part('partials/animation-banner'); ?>

        <section class="vote-registration vote-registration--gray-bkgd pt-lg-6" id="register">
            <div class="container">
                <h1 class="vote-registration__headline"><?php the_field('page_headline'); ?></h1>

                <div class="vote-registration__content">
                    <div class="content__header rich-text">
                        <?php the_field('page_intro_text'); ?>
                    </div>

                    <div class="content__form">
                        <div id="spinner"><img src="<?php echo app_asset_url('img/spinner.gif'); ?>" alt=""></div>
                        <iframe src="https://register.rockthevote.com/registrants/new?partner=101<?php echo !empty($_GET['source']) ? '&amp;source=' . htmlentities($_GET['source']) : ''; ?>" width="100%" height="100%" frameborder="0" onload="document.getElementById('spinner').style.display='none';"></iframe>
                    </div>
                </div>
            </div> <!-- .container -->
        </section> <!-- .vote-registration -->

        <?php get_template_part('partials/voter-registration-blocks'); ?>

        <div class="text-banner text-banner--lg text-banner--has-icon">
            <div class="container">
                <img src="<?php echo app_asset_url('img/stopwatch.svg'); ?>" alt="" class="mb-2">
                <h2 class="text-banner__headline font-weight-normal"><?php the_field('text_banner_headline'); ?></h2>
            </div>
        </div>

        <?php get_template_part('partials/info-blocks'); ?>

<?php endwhile;
endif; ?>
<?php get_footer(); ?>