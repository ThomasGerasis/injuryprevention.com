<?php $randomId = getToken(8);?>
<?php $articleIds = array();
if(!empty($attrs['article_ids'])){
    foreach($attrs['article_ids'] as $articleId){
        if(empty($articleId)) continue;
        if(!in_array($articleId, $articleIds)) $articleIds[] = $articleId;
    }
}

if(!empty($attrs['article_category_ids']) && count($articleIds) < $attrs['limit']){
    //check categories if any
    $feedArticleIds = array(); $feedArticleSort = array();
    foreach($attrs['article_category_ids'] as $articleCategoryId){
        $articleFeed = $cacheHandler->getCategoryFeed(1,$articleCategoryId);
        foreach($articleFeed as $articleId){
            if(!in_array($articleId, $articleIds) && !in_array($articleId, $feedArticleIds)){
                $articleData = $cacheHandler->getArticle($articleId);
                if(empty($articleData)) continue;
                $feedArticleIds[] = $articleId;
                $feedArticleSort[] = $articleData['date_published'];
            }
        }
    }
    if(count($feedArticleIds)){
        array_multisort($feedArticleSort,SORT_DESC,$feedArticleIds);
        foreach($feedArticleIds as $articleId){
            $articleIds[] = $articleId;
            if(count($articleIds) == $attrs['limit']) break;
        }
    }
}
if(count($articleIds) < $attrs['limit']){
    //check general feed
    $feedArticleIds = array(); $feedArticleSort = array();
    $articleFeed = $cacheHandler->getGenenalCategoryFeed(1);
    foreach($articleFeed as $articleId){
        if(!in_array($articleId, $articleIds) && !in_array($articleId, $feedArticleIds)){
            $articleData = $cacheHandler->getArticle($articleId);
            if(empty($articleData)) continue;
            $feedArticleIds[] = $articleId;
            $feedArticleSort[] = $articleData['date_published'];
        }
    }
    if(count($feedArticleIds)){
        array_multisort($feedArticleSort,SORT_DESC,$feedArticleIds);
        foreach($feedArticleIds as $articleId){
            $articleIds[] = $articleId;
            if(count($articleIds) == $attrs['limit']) break;
        }
    }
}?>


<div class="article__row mt-5 mb-5">
    <?php foreach($articleIds as $articleId){
        $articleData = $cacheHandler->getArticle($articleId,true);
        if(empty($articleData)) continue;?>
        <div class="article-box text-left py-2">
            <?php if(!empty($articleData['opener_image_id'])){?>
                <a href="<?php echo base_url($articleData['permalink']);?>" class="d-block mb-2 img-box">
                    <div class="image-overlay"></div>
                    <img loading="lazy" width="400" height="auto" src="<?php echo $cacheHandler->imageUrl($articleData['opener_image_id'],'rect400');?>" class="w-100 mx-auto border-radius-10">
                </a>
            <?php } ?>
            <div class="mb-2 article-category-title">
                <?php $articleCategoryData = $cacheHandler->getArticleCategory($articleData['article_category_id']);?>
                <?php if(!empty($articleCategoryData)){?>
                    <a href="<?php echo base_url($articleCategoryData['permalink']);?>" class="font-secondary font-size-11rem font-weight-500">
                        <?php echo $articleCategoryData['short_title'] ?? '';?>
                    </a>
                <?php } ?>
            </div>
            <div class="article-title">
                <span class="font-secondary text-decoration-none font-size-11rem d-block overflow-hidden">
                    <?php echo $articleData['short_title'];?>
                </span>
            </div>
            <div class="read-more">
                <a href="<?php echo base_url($articleData['permalink']);?>" class="main-button font-fff font-size-12rem primary-gradient">
                    <span class="button-slanted-content"> READ MORE</span>
                </a>
            </div>
        </div>
    <?php } ?>
</div>
