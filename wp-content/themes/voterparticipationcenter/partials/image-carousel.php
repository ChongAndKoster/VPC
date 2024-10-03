<?php if (have_rows('image_carousel_slides')): ?>
    <section class="image-carousel slideshow">
        <div class="slides" data-slick='{"dots": true}'>
            
        <?php while (have_rows('image_carousel_slides')): the_row(); ?>
            <div class="image-carousel__slide slide" style="background-image: url(<?php echo get_sub_field('image')['sizes']['banner']; ?>);">
                <div class="image-carousel__content">
                    <div class="wow fadeInUp">
                        <h2 class="image-carousel__headline"><?php the_sub_field('headline'); ?></h2>
                        <p><?php the_sub_field('text'); ?></p>
                    <?php if ($button = get_sub_field('button')): ?>
                        <a href="<?php echo $button['url']; ?>" target="<?php echo $button['target']; ?>" class="btn wow fadeInUp"><?php echo $button['title']; ?></a>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        
        </div> <!-- .slides -->
    </section>
<?php endif; ?>
