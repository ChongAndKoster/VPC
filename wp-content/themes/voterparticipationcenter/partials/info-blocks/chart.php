<div 
    class="info-block info-block--<?php echo $block['type']; ?>" 
>
    <div class="info-block__content">
        <h2 class="info-block__headline"><?php echo $block['headline']; ?></h2>
        <p><?php echo $block['text']; ?></p>
    </div>
    <div class="info-block__chart">
    <?php if (! empty(trim($block['chart_code']))): ?>
        <?php echo $block['chart_code']; ?>
    <?php else: ?>
        <img src="<?php echo $block['chart_image']['url']; ?>" alt="<?php echo htmlentities($block['chart_image']['alt']); ?>">
    <?php endif; ?>
    </div> <!-- .info-block__chart -->
</div>
