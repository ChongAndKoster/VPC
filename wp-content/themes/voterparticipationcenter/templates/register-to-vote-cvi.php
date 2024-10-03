<?php
/*
 * Template Name: Register to Vote CVI
 */
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <section class="vote-registration">
            <div class="container">
                <h1 class="vote-registration__headline">Register to Vote</h1>

                <div class="vote-registration__content">
                    <div class="content__header rich-text">
                        <?php the_field('page_intro_text'); ?>
                    </div>

                    <div class="content__form">
                        <div id="spinner"><img src="<?php echo app_asset_url('img/spinner.gif'); ?>" alt=""></div>
                        <script onload="document.getElementById('spinner').style.display='none';" type="text/javascript" src="https://register.rockthevote.com/assets/rtv-iframe.js"></script>
                        <script type="text/javascript">
                            RtvIframe.init({
                                partner: 101
                            })
                        </script>
                    </div>
                </div>
            </div> <!-- .container -->
        </section> <!-- .vote-registration -->

        <?php get_template_part('partials/info-blocks'); ?>

<?php endwhile;
endif; ?>

<style>
    .app-header,
    .app-footer {
        display: none;
    }

    .app-main {
        padding-top: 0;
    }

    .app-main .vote-registration {
        height: 100vh;
    }
</style>

<?php get_footer(); ?>