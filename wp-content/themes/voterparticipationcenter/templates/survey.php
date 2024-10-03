<?php
/*
 * Template Name: Survey
 */

// Add custom classes to the submit button.
add_filter('gform_submit_button', 'form_submit_button', 10, 2);
function form_submit_button($button, $form) {
    return '<input type="submit" class="button btn btn-primary" id="gform_submit_button_' . $form['id'] . '" value="' . $form['button']['text'] . '">';
}

?>
<?php get_header(); ?>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <style>
        h1 {
            margin: 0 0 .875rem 0;
            color: #fd2f6a;
            font-size: 2.25rem;
            font-weight: 700;
            line-height: 1.1;
        }
        .gform_wrapper.gravity-theme .gfield {
            margin-bottom: 1.25rem;
        }
        .gform_wrapper.gravity-theme .gfield-choice-input+label {
            vertical-align: text-top;
        }
        .app-footer {
            border-top: 1px solid #ddd;
        }
    </style>

    <div class="container py-4 py-lg-6">
        <h1><?php the_title(); ?></h1>

        <?php the_content(); ?>
    </div>
    
    <?php endwhile; endif; ?>
<?php get_footer(); ?>
