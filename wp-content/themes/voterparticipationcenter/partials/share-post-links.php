<ul class="share-links">
    <li>
        <a 
            href="http://twitter.com/intent/tweet?text=<?php echo urlencode(get_the_title()); ?>&amp;url=<?php echo urlencode(get_the_permalink()); ?>" 
            target="_blank" 
            title="Twitter"
        >
            <i class="ico-twitter" aria-hidden="true"></i>
        </a>
    </li>
    <li>
        <a 
            href="https://www.facebook.com/sharer/sharer.php?t=<?php echo urlencode(get_the_title()); ?>&amp;u=<?php echo urlencode(get_the_permalink()); ?>" 
            target="_blank" 
            title="Facebook"
        >
            <i class="ico-facebook" aria-hidden="true"></i>
        </a>
    </li>
    <li>
        <a 
            href="https://www.linkedin.com/shareArticle?mini=true&amp;title=<?php echo urlencode(get_the_title()); ?>&amp;url=<?php echo urlencode(get_the_permalink()); ?>" 
            target="_blank" 
            title="LinkedIn"
        >
            <i class="ico-linkedin" aria-hidden="true"></i>
        </a>
    </li>
</ul>
