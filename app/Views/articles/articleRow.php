<?php
helper('text');
$publishedDate = isset($articleData['date_published']) ?
    date('d.m.Y - H:i', strtotime($articleData['date_published'])) : '';
?>
<div class="article__row mb-3 mb-lg-5">
    <?php if (!empty($articleData['opener_image_id'])) { ?>
        <div class="d-block mb-2 article_image">
        <a href="<?= base_url($articleData['permalink']) ?>" title="<?php echo htmlspecialchars($articleData['title']); ?>">
            <img <?= !$ajaxRequest ? 'loading="lazy"' : '' ?>
                    width="<?= $isMobile ? '120' : '400' ?>"
                    height="<?= $isMobile ? '67' : '225' ?>"
                    src="<?php echo $cacheHandler->imageUrl($articleData['opener_image_id'], 'rect400'); ?>"
                    class="img-fluid mx-auto border-radius-10"
                    alt="<?php echo htmlspecialchars($articleData['title']); ?>"></a>
        </div>
    <?php } ?>
    <div class="article_content">
        <div class="article-date font-size-08rem font-xl-size-1rem font-fff-opacity-60 mb-lg-3 mb-1">
            <span><?php echo $publishedDate ?></span>
        </div>
        <a href="<?= base_url($articleData['permalink']) ?>"
           class="font-fff font-weight-700 font-xl-size-15rem font-size-1rem mb-lg-4 text-decoration-none article_title mb-2">
            <?php echo $articleData['title']; ?>
        </a>
        <?php if (!$isMobile) { ?>
            <div class="mt-3 font-size-11rem short-title">
                <?php
                $string = word_limiter($articleData['short_title'], 34);
                echo $string;
                ?>
            </div>
        <?php } ?>
    </div>
</div>