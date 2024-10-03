<?php
    $block_id = 'vpc-' . uniqid();
?><div 
    id="<?php echo $block_id; ?>"
    class="block block--photo-callout" 
    style="
        height: auto; 
        background-image: url(<?php block_field('photo'); ?>); 
    "
>
    <div class="container">
        <?php block_field('content'); ?>
    </div>
</div>
<?php 
    $mobile_height = block_value('mobile-height');
    $tablet_height = block_value('tablet-height');
    $desktop_height = block_value('desktop-height');
    
    if ($mobile_height || $tablet_height || $desktop_height): ?>
    <style>
    <?php if ($mobile_height): ?>
        @media (max-width: 767px) {
            #<?php echo $block_id; ?> {
                min-height: unset;
                height: <?php echo $mobile_height; ?> !important;
            }
        }
    <?php endif; ?>
    
    <?php if ($tablet_height): ?>
        @media (min-width: 768px) and (max-width: 991px) {
            #<?php echo $block_id; ?> {
                min-height: unset;
                height: <?php echo $tablet_height; ?> !important;
            }
        }
    <?php endif; ?>
    
    <?php if ($desktop_height): ?>
        @media (min-width: 992px) {
            #<?php echo $block_id; ?> {
                min-height: unset;
                height: <?php echo $desktop_height; ?> !important;
            }
        }
    <?php endif; ?>
    </style>
<?php endif; ?>
