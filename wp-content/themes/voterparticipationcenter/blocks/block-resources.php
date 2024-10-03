<div class="block block--resources">
    <div class="container">
        <h2 class="resources__headline"><?php block_field('headline'); ?></h2>
        
    <?php if (have_rows('resources')): ?>
        <ul class="resources__list">
            
        <?php while (have_rows('resources')): the_row(); ?>
            <li>
                <a 
                <?php if (get_sub_field('type') == 'file'): ?>
                    href="<?php echo get_sub_field('file')['url']; ?>" 
                <?php else: ?>
                    href="<?php the_sub_field('link'); ?>" 
                <?php endif; ?>
                    target="_blank"
                    class="resource resource--<?php the_sub_field('type'); ?>" 
                >
                    <div class="resource__icon">
                    <?php if (get_sub_field('type') == 'file'): ?>
                        <i class="ico-download"></i>
                    <?php else: ?>
                        <i class="ico-link"></i>
                    <?php endif; ?>
                    </div>
                    <div class="resource__info">
                        <?php the_sub_field('label'); ?>
                    </div>
                </a>
            </li>
        <?php endwhile; ?>    
            
            <li class="placeholder"> </li>
            <li class="placeholder"> </li>
        </ul>
    <?php endif; ?>
    
    </div> <!-- .container -->
</div>
