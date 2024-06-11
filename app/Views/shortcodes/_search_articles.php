<div class="search-articles py-3 py-md-5 text-center">
    <div class="d-flex justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="mb-3 font-fff font-size-15rem">
                <?php echo @$attrs['search_title'];?>
            </div>
            <?php if (!empty($attrs['search_subtitle'])) { ?>
                <div class="mb-3 font-fff font-size-1rem">
                    <?php echo $attrs['search_subtitle'];?>
                </div>
            <?php } ?>
        </div>
    </div>
    <div id="searchContainer" class="search-container position-relative">
        <div class="d-flex justify-content-center">
            <div id="searchInnerContainer" class="col-12 col-lg-10 col-xl-8 border-radius-25 my-3 search-container-box d-flex align-items-center background-fff position-relative">
                <div class="flex-fill position-relative z2-index text-right">
                    <input type="search" class="search-input" id="searchInput" placeholder="<?php echo (empty($attrs['search_placeholder']) ? 'Which casino you need help with?' : $attrs['search_placeholder']);?>">
                </div>
                <div class="submit-box text-center py-2 px-5 position-relative font-weight-500 font-size-11rem font-000 z2-index">
                    Search
                </div>
                <div class="search-results-container position-absolute background-fff border-radius-5 px-3 pt-1 pb-3">
                    <div class="search-results-text py-1 mb-2 font-80 text-left">Write something</div>
                    <div class="search-results loading font-000 text-left">
                    </div>
                </div>
            </div>
        </div>
        <div class="top-search-articles position-absolute background-fff border-radius-5 px-3 py-1">
            <div class="py-1 mx-2 mb-2 font-80 text-left">Top articles</div>
            <div class="d-flex flex-wrap align-items-center">
                <?php if(!empty($attrs['article_ids'])) {
                    $article_ids = $attrs['article_ids'];
                }else{
                    $article_ids = array();
                    $articleFeed = $cacheHandler->getGenenalCategoryFeed(1);//page length 6...
                    foreach($articleFeed as $articleId) {
                        $articleData = $cacheHandler->getArticle($articleId);
                        if(empty($articleData)) continue;
                        $article_ids[] = $articleId;
                        if(count($article_ids) === 8) break;
                    }
                    $articleFeed = $cacheHandler->getGenenalCategoryFeed(2);
                    foreach($articleFeed as $articleId) {
                        $articleData = $cacheHandler->getArticle($articleId);
                        if(empty($articleData)) continue;
                        $article_ids[] = $articleId;
                        if(count($article_ids) === 8) break;
                    }
                }
                foreach($article_ids as $articleId) {
                    $articleData = $cacheHandler->getArticle($articleId);
                    if (empty($articleData)) { continue; }
                    ?>
                    <div class="col-12 col-md-4 col-xl-3 mb-3">
                        <a href="<?php echo base_url($articleData['permalink']);?>" class="d-block search-top-article font-000 mx-2 py-2 px-2 overflow-hidden text-left"><?php echo $articleData['title'];?></a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="vertical-space"></div>