<?php 
    $testimonials = app_get_testimonials();
    if ($testimonials->have_posts()):
        $first_testimonial_id   = $testimonials->posts[0]->ID ?? null;
        $first_testimonial_bkgd = get_field('testimonial_background_image', $first_testimonial_id);
        $first_testimonial_link = get_field('testimonial_link', $first_testimonial_id);
?>
    <section class="testimonials-carousel testimonials-slideshow">
        <div class="testimonials-carousel__bkgds">
            <?php 
                $i = 0;
                while ($testimonials->have_posts()): 
                    $testimonials->the_post(); 
                    $bkgd = get_field('testimonial_background_image');
                ?>
                <div class="bkgd <?php echo ($i == 0) ? 'bkgd--active' : ''; $i++; ?>" style="background-image: url(<?php echo esc_attr(($bkgd['sizes']['banner'] ?? '')); ?>);"></div>
            <?php endwhile; wp_reset_query(); ?>
        </div>
        <div class="container">
            <h2 class="testimonials-carousel__headline"><?php echo get_field('g_testimonials_headline', 'options'); ?></h2>

            <div class="testimonials-carousel__slides slides" data-slick='{"dots": true}'>
                
            <?php while ($testimonials->have_posts()): $testimonials->the_post(); ?>
                
                <div 
                    class="testimonials-carousel__slide testimonials-carousel__slide--<?php echo esc_attr(get_field('testimonial_font_size')); ?> slide" 
                    
                <?php if ($link = get_field('testimonial_link')): ?>
                    data-link-url="<?php echo esc_attr(($link['url'] ?? '')); ?>" 
                    data-link-target="<?php echo esc_attr(($link['target'] ?? '')); ?>" 
                    data-link-title="<?php echo esc_attr(($link['title'] ?? '')); ?>" 
                <?php endif; ?>

                <?php if ($bkgd_image = get_field('testimonial_background_image')): ?>
                    data-bkgd="<?php echo esc_attr(($bkgd_image['sizes']['banner'] ?? '')); ?>"
                <?php endif; ?>

                >
                    <blockquote class="slide__content">
                        <footer class="content__attribution"><?php the_title(); ?></footer>
                        <div class="content__testimonial"><?php the_content(); ?></div>
                    </blockquote>
                </div>

            <?php endwhile; wp_reset_query(); ?>
            
            </div> <!-- .slides -->

            <a 
                href="<?php echo esc_attr(($first_testimonial_link['url'] ?? '')); ?>" 
                target="<?php echo esc_attr(($first_testimonial_link['target'] ?? '')); ?>" 
                class="testimonials-carousel__link <?php echo ! empty($first_testimonial_link['url']) ? '' : 'hidden'; ?>"
            >
                <span><?php echo $first_testimonial_link['title'] ?? ''; ?></span> <i class="ico-angle-right" aria-hidden="true"></i>
            </a>

        </div> <!-- .container -->
    </section>
<?php endif; ?>
