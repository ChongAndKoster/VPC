<?php
    $text_block = get_field('vrb_text_block');
    $image_block = get_field('vrb_image_block');
    
    $image_block_styles = '';
    if (! empty($image_block['background_color'])) {
        $image_block_styles .= ' background-color: ' . $image_block['background_color'] . ';';
    }
    if (! empty($image_block['background_image'])) {
        $image_block_styles .= ' background-image: url(' . $image_block['background_image']['sizes']['medium_large'] . ');';
    }
    
?><div class="voter-registration-blocks">
    <div class="voter-registration-block voter-registration-block--text">
        <div class="voter-registration-block__content">
            <h2 class="voter-registration-block__headline"><?php echo $text_block['headline']; ?></h2>
            
            <?php echo $text_block['text']; ?>
            <?php
                $button_link = $text_block['button']['url'] ?? '#register';
            ?>
            
            <a 
                href="<?php echo $button_link; ?>" 
                target="<?php echo $text_block['button']['target'] ?? '_self'; ?>" 
                class="btn btn-main btn-main--gradient <?php echo substr($button_link, 0, 1) == '#' ? '' : 'link-out'; ?>" 
                <?php if (substr($button_link, 0, 1) == '#'): ?> data-scrollto="<?php echo $button_link; ?>" <?php endif; ?>
            ><?php echo $text_block['button']['title'] ?? 'Register to Vote'; ?></a>
        </div>
    </div> <!-- .voter-registration-block -->
    
    <div 
        class="voter-registration-block voter-registration-block--image"
        style="<?php echo esc_attr($image_block_styles); ?>"
    >
        <div class="voter-registration-block__content">
            
        <?php if ($image = $image_block['image']): ?>
            <img src="<?php echo esc_attr($image['sizes']['medium_large']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
        <?php endif; ?>
        
        </div>
    </div> <!-- .voter-registration-block -->
</div> <!-- .voter-registration-blocks -->