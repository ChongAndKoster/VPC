<?php
/*
 * Template Name: Stay Informed
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
            <div class="ngp-form" data-form-url="https://secure.everyaction.com/v1/Forms/MaM5aCzG8kKqfPn0QIL9wA2" data-fastaction-endpoint="https://fastaction.ngpvan.com" data-inline-errors="true" data-fastaction-nologin="true" data-databag-endpoint="https://profile.ngpvan.com" data-databag="everybody" data-mobile-autofocus="false">
            </div>
          </div>
        </div>
      </div> <!-- .container -->
    </section> <!-- .vote-registration -->

    <?php get_template_part('partials/info-blocks'); ?>

<?php endwhile;
endif; ?>
<?php get_footer(); ?>