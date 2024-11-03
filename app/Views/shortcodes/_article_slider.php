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

<div class="position-relative article-swiper-container mt-5 mb-5 swiper-container px-3" data-breakpoint="type1" data-prefix="article" id="swipe-<?php echo $randomId;?>">
    <div class="swiper article-swiper">
        <div class="swiper-wrapper">
            <?php foreach($articleIds as $articleId){
                $articleData = $cacheHandler->getArticle($articleId);
                if(empty($articleData)) continue;?>
                <div class="swiper-slide py-2">
                    <?php if(!empty($articleData['opener_image_id'])){?>
                        <a href="<?php echo base_url($articleData['permalink']);?>" class="d-block mb-2">
                            <img loading="lazy" width="400" height="225" src="<?php echo $cacheHandler->imageUrl($articleData['opener_image_id'],'rect400');?>" class="img-fluid mx-auto border-radius-10">
                        </a>
                    <?php } ?>
                    <div class="mb-2 article-category-title">
                        <?php $articleCategoryData = $cacheHandler->getArticleCategory($articleData['article_category_id']);?>
                        <?php if(!empty($articleCategoryData)){?>
                            <a href="<?php echo base_url($articleCategoryData['permalink']);?>" class="font-fff text-decoration-none font-size-11rem font-weight-500"><?php echo $articleCategoryData['title'];?></a>
                        <?php } ?>
                    </div>
                    <div class="article-title">
                        <a href="<?php echo base_url($articleData['permalink']);?>" class="font-fff font-size-12rem d-block overflow-hidden"><?php echo $articleData['title'];?></a>
                    </div>
                    <div class="read-more">
                        <?php if(!empty($articleData['is_locked'])){ ?>
                                <a class="main-button font-fff font-size-12rem primary-gradient cursor-pointer loadAuthAjaxModal" data-url="<?=base_url($articleData['permalink'])?>">
                                    <span class="button-slanted-content">READ MORE</span>         
                                </a>
                            <?php }else{ ?>
                                <a href="<?php echo base_url($articleData['permalink']);?>" class="main-button font-fff font-size-12rem primary-gradient">
                                    <span class="button-slanted-content">READ MORE</span>
                                </a>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="swiper-button-next swiper-button-lock border-circle"><img src="<?php echo base_url('assets/img/chevron_right.svg'); ?>" alt="next slide" width="12" height="22" class=""></div>
    <div class="swiper-button-prev swiper-button-lock border-circle"><img src="<?php echo base_url('assets/img/chevron_right.svg'); ?>" alt="previus slide" width="12" height="22" class=""></div>
</div>
<div class="vertical-space"></div>