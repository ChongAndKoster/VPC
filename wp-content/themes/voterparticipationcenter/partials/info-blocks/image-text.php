<div 
    class="info-block info-block--<?php echo $block['type']; ?> info-block--<?php echo $block['theme']; ?>" 
>
    <div class="info-block__content">
        <img src="<?php echo $block['image']['url']; ?>" alt="<?php echo htmlentities($block['image']['alt']); ?>">
        <div class="info-block__text">
        <?php if (! empty($block['headline'])): ?>
            <h2 class="info-block__headline"><?php echo $block['headline']; ?></h2>
        <?php endif; ?>
            <p><?php echo $block['text']; ?></p>
        </div>
    </div>
</div>
