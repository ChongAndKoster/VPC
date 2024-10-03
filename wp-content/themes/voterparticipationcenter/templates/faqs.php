<?php
/*
 * Template Name: FAQs
 */
?>
<?php get_header(); ?>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    
    <div class="gradient-banner" role="banner">
        <div class="container mw-840px">
            <h1 class="gradient-banner__headline"><?php the_title(); ?></h1>
        </div> <!-- .container -->
    </div> <!-- .gradient-banner -->
    
    <div class="py-6">
        <div class="container mw-840px">
            
            <?php the_content(); ?>
            
            <div class="faqs mt-5" id="accordion">
                <?php 
                    $faqs = app_get_faqs();
                    while($faqs->have_posts()):
                        $faqs->the_post();
                ?>
                    <div class="faq">
                        <h2 
                            class="faq__question" 
                            id="#question-<?php the_ID(); ?>"
                            data-toggle="collapse" 
                            data-target="#answer-<?php the_ID(); ?>" 
                            aria-expanded="false" 
                            aria-controls="answer-<?php the_ID(); ?>"
                        >
                            <?php the_title(); ?>
                        </h2>
                        
                        <div 
                            class="faq__answer collapse" 
                            id="answer-<?php the_ID(); ?>" 
                            aria-labelledby="question-<?php the_ID(); ?>" 
                            data-parent="#accordion"
                        >
                            <?php the_content(); ?>
                        </div>
                    </div> <!-- .faq -->
                <?php endwhile; wp_reset_query(); ?>
            
            </div> <!-- .faqs -->
        </div> <!-- .container -->
    </div>
    
    <div class="faqs-banner-contact">
        <h2 class="faqs-banner-contact__headline">Didn’t see what you were <span class="headline__looking-for text-hide">looking for?</span></h2>
        <a href="/contact-us" class="btn btn-secondary">Contact Us</a>
    </div>
    
    <?php endwhile; endif; ?>
<?php get_footer(); ?>
