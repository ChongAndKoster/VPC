<div 
    class="info-block info-block--<?php echo $block['type']; ?> info-block--<?php echo $block['theme']; ?>" 
>
    <div class="info-block__content">
        <div class="info-block__html">
            <?php echo $block['custom_html']; ?>
        </div>
        <div class="info-block__text">
        <?php if (! empty($block['headline'])): ?>
            <h2 class="info-block__headline"><?php echo $block['headline']; ?></h2>
        <?php endif; ?>
            <p><?php echo $block['text']; ?></p>
        </div>
    </div>
</div>
