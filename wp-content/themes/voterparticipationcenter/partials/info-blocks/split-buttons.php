<div 
    class="info-block info-block--<?php echo $block['type']; ?>" 
>
    <div class="info-block__east">
        <div 
            class="info-block__bkgd info-block__bkgd--mobile" 
            style="background-image: url(<?php echo $block['split_buttons']['left_mobile_background_image']['url'] ?? $block['split_buttons']['left_background_image']['url']; ?>);">
        </div>
        <div 
            class="info-block__bkgd" 
            style="background-image: url(<?php echo $block['split_buttons']['left_background_image']['url']; ?>);">
        </div>
        
    <?php if (! empty($block['split_buttons']['left_button'])): ?>
        <a 
            href="<?php echo $block['split_buttons']['left_button']['url']; ?>" 
            target="<?php echo $block['split_buttons']['left_button']['target']; ?>" 
            class="btn btn-main btn-main--arrow btn-main--<?php echo $block['split_buttons']['left_button_style']; ?>"
        >
            <span class="btn__icon d-lg-none"><i class="ico-magnifying-glass"></i></span> 
            <?php echo $block['split_buttons']['left_button']['title']; ?>
        </a>
    <?php endif; ?>
    </div> <!-- .info-block__east -->
    
    <div class="info-block__west">
        <div 
            class="info-block__bkgd info-block__bkgd--mobile" 
            style="background-image: url(<?php echo $block['split_buttons']['right_mobile_background_image']['url'] ?? $block['split_buttons']['right_background_image']['url']; ?>);">
        </div>
        <div 
            class="info-block__bkgd" 
            style="background-image: url(<?php echo $block['split_buttons']['right_background_image']['url']; ?>);">
        </div>
        
        <?php if (! empty($block['split_buttons']['right_button'])): ?>
        <a 
            href="<?php echo $block['split_buttons']['right_button']['url']; ?>" 
            target="<?php echo $block['split_buttons']['right_button']['target']; ?>" 
            class="btn btn-main btn-main--arrow btn-main--<?php echo $block['split_buttons']['right_button_style']; ?>"
        >
            <span class="btn__icon d-lg-none"><i class="ico-magnifying-glass"></i></span> 
            <?php echo $block['split_buttons']['right_button']['title']; ?>
        </a>
    <?php endif; ?>
    </div> <!-- .info-block__west -->
</div> <!-- .info-block -->
