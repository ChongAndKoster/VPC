<?php
    $left_block = get_field('info_blocks_left_block');
    $right_block = get_field('info_blocks_right_block');
?>
<section class="info-blocks">
    <?php 
        if ($left_block) {
            $block = $left_block; 
            include(locate_template('partials/info-blocks/' . $block['type'] . '.php', false, false)); 
        }
        
        if ($right_block) {
            $block = $right_block; 
            include(locate_template('partials/info-blocks/' . $block['type'] . '.php', false, false));
        }
    ?>
</section> <!-- .info-blocks -->
