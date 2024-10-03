<section 
    class="stat-cards"
<?php if ($bkgd_color = get_field('stat_cards_background_color')): ?>
    style="background-color: <?php echo $bkgd_color; ?>; color: <?php echo $bkgd_color; ?>;"
<?php endif; ?>
>
    <div class="container">
        <h2 class="stat-cards__headline"><?php the_field('stat_cards_headline'); ?></h2>

    <?php if (have_rows('stat_cards_cards')): ?>
        <div class="stat-cards__cards">
            
        <?php while (have_rows('stat_cards_cards')): the_row(); ?>
            
            <?php if ($link = get_sub_field('link')): ?>
                <a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="stat-cards__card">
            <?php else: ?>
                <div class="stat-cards__card">
            <?php endif; ?>
            
                <?php
                    if (get_sub_field('value_type') == 'image'):
                        $image = get_sub_field('value_image');
                        $hover_image = get_sub_field('value_hover_image') ?: $image;
                ?>
                    <div class="card__icon">
                        <img src="<?php echo $image['url']; ?>" alt="" class="icon">
                        <img src="<?php echo $hover_image['url']; ?>" alt="" class="icon--hover">
                    </div>
                <?php else: ?>
                    <span class="card__value"><?php the_sub_field('value_text'); ?></span>
                <?php endif; ?>
                
                    <span class="card__label"><?php the_sub_field('label'); ?></span>
                    
                <?php if ($link): ?>
                    <i class="card__arrow ico-arrow-right-circle" aria-hidden="true"></i>
                <?php endif; ?>
            <?php if ($link): ?>
                </a>
            <?php else: ?>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>
            
            <div class="stat-cards__card placeholder"></div>
            <div class="stat-cards__card placeholder"></div>
            <div class="stat-cards__card placeholder"></div>
        </div>
    <?php endif; ?>
        
    </div> <!-- .container -->
</section> <!-- .stat-cards -->
