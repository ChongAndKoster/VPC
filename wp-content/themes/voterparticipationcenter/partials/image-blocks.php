
    <div class="image-blocks__blocks">
        
    <?php 
        $index = 0;
        while (have_rows('image_blocks')): 
            the_row();
        ?>
        <div class="image-block wow fadeIn" data-wow-delay="<?php echo ($index * .25); ?>s">
            <div class="image-block__image"> 
            <?php if ($code = get_sub_field('image_code')): ?>
                <?php echo $code; ?>
            <?php else: $image = get_sub_field('image'); ?>
                <img src="<?php echo $image['url']; ?>" alt="<?php echo htmlentities($image['alt']); ?>">
            <?php endif; ?>
            
            </div>
            <div class="image-block__content">
                <h3 class="image-block__headline"><?php the_sub_field('headline'); ?></h3>
            <?php if ($note = get_sub_field('note')): ?>
                <span class="image-block__note"><?php echo $note; ?></span>
            <?php endif; ?>
            </div>
        </div> <!-- .image-block -->
    <?php 
        $index++;
        endwhile;
    ?>
        
    </div> <!-- .image-blocks__blocks -->
