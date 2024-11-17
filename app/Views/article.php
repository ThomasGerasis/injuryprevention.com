<?php
$articleCategoryData = $cacheHandler->getArticleCategory($pageData['article_category_id']);
$publishedDate = isset($pageData['date_published']) ? date('d F Y - H:i', strtotime($pageData['date_published'])) : '';
?>
<div class="container font-fff <?=$pageData['is_locked'] ? 'locked':''?>">
    <div class="article-container d-block mx-auto">
            <?php if (!empty($articleCategoryData)) { ?>
                <div class="mb-3 d-flex justify-content-center w-100">
                    <a href="<?php echo base_url($articleCategoryData['permalink']); ?>"
                       class="background-yellow font-size-11rem font-weight-500 d-inline-block px-2 border-radius-5 font-fff">
                        <?php echo $articleCategoryData['title']; ?>
                    </a>
                </div>
            <?php } ?>
            <div class="user-details mb-4">
                <div class="d-flex align-items-center">
                    <div class="user-img border-circle border-secondary border p-1">
                        <img width="<?= $isMobile ? '32' : '40' ?>" height="<?= $isMobile ? '32' : '40' ?>" alt="round logo"
                             src="<?php echo base_url('assets/img/logo.svg'); ?>">
                    </div>
                    <span class="font-fff ps-2">Somalab</span>
                    <div class="article-date ps-2 font-fff-opacity-60">
                        <span><?php echo $publishedDate ?></span>
                    </div>
                </div>
            </div>

            <h1 class="font-fff font-weight-700 font-xl-size-21rem font-size-15rem font-md-size-17rem font-size-21rem mb-5">
                <?php echo $pageData['title']; ?>
            </h1>

            <?php if (!empty($pageData['short_title'])) { ?>
                <div class="mb-5 font-size-12rem">
                    <?php echo $pageData['short_title'] ?? ''; ?>
                </div>
            <?php } ?>

            <?php if (!empty($pageData['opener_image_id'])) { ?>
                <div class="text-center mb-5">
                    <img loading="lazy" width="1100" height="619"
                         src="<?php echo $cacheHandler->imageUrl($pageData['opener_image_id'], 'rect1100'); ?>"
                         class="d-none d-lg-block img-fluid mx-auto"
                         alt="Article Image">
                    <img loading="lazy" width="850" height="478"
                         src="<?php echo $cacheHandler->imageUrl($pageData['opener_image_id'], 'rect850'); ?>"
                         class="d-block d-lg-none img-fluid mx-auto"
                         alt="Article Image">
                </div>
            <?php } ?>
            <div class="article-content mb-3 font-size-1rem font-xl-size-11rem">
                <?php echo $pageContent; ?>
            </div>
    </div>

    <?php
    $articlesSlider = $cacheHandler->getOption('articlesSlider');
    $sliderTitle = $articlesSlider['title'] ?? 'Related Articles';
    $sliderLimit = $articlesSlider['limit'] ?? 4;
    $sliderTitleType = $articlesSlider['heading_type'] ?? 'h2';
    ?>
    
    <?php
    // get articles by article category id
    $articlesFeed = $cacheHandler->getCategoryFeed(1, $pageData['article_category_id']);
    $articleIds = [];
    // echo build related articles ids array
    foreach ($articlesFeed as $articleId) {
        if ($articleId !== $pageData['id'] && !in_array($articleId, $articleIds, true)) {
            $articleData = $cacheHandler->getArticle($articleId);
            if (empty($articleData)) {
                continue;
            }
            $articleIds[] = $articleId;
        }
        if (count($articleIds) === $sliderLimit) {
            break;
        }
    }
    if (count($articleIds)) { ?>
        <div class="related-articles-container mt-5">
            <div class="heading-container">
                <<?php echo $sliderTitleType; ?> class="font-fff font-weight-700 border-radius-40 px-3 m-0">
                    <?php echo $sliderTitle; ?>
                </<?php echo $sliderTitleType; ?>>
            </div>
            <div class="vertical-space"></div>
            <?php
            // echo articles slider
            echo view('shortcodes/_article_slider',
                array('attrs' => array(
                    'article_ids' => $articleIds,
                    'limit' => count($articleIds)),
                    'cacheHandler' => $cacheHandler
                )
            );?>
        </div>
    <?php } ?>
</div>
