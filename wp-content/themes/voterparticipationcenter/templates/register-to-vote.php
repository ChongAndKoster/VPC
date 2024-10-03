<?php
/*
 * Template Name: Register to Vote
 */
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <section class="vote-registration">
            <div class="container">
                <h1 class="vote-registration__headline"><?php the_field('page_headline'); ?></h1>

                <div class="vote-registration__content">
                    <div class="content__header rich-text">
                        <?php the_field('page_intro_text'); ?>
                    </div>

                    <div class="content__form">
                        <!-- <div id="spinner"><img src="<?php echo app_asset_url('img/spinner.gif'); ?>" alt=""></div> -->
                        <iframe src="https://register.rockthevote.com/registrants/new?partner=101<?php echo !empty($_GET['source']) ? '&amp;source=' . htmlentities($_GET['source']) : ''; ?>" width="100%" height="100%" frameborder="0" onload="document.getElementById('spinner').style.display='none';"></iframe>
                    </div>
                </div>
            </div> <!-- .container -->
        </section> <!-- .vote-registration -->

        <?php get_template_part('partials/info-blocks'); ?>

<?php endwhile;
endif; ?>
<?php get_footer(); ?>