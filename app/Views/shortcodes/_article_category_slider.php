<?php $randomId = getToken(8);?>
<div class="position-relative article-category-swiper-container swiper-container px-3" data-breakpoint="type1" data-prefix="article-category" id="category-swipe-<?php echo $randomId;?>">
    <div class="swiper article-category-swiper">
        <div class="swiper-wrapper">
            <?php $allArticleIds = array();
            $limit = 4;?>
            <?php foreach($attrs['tabs'] as $tab){
                if(empty($tab['article_category_id'])) continue;?>
                <?php $articleCategoryData = $cacheHandler->getArticleCategory($tab['article_category_id']);
                $articleIds = array();
                if(empty($articleCategoryData)) continue;
                if(!empty($tab['article_ids'])){
                    foreach($tab['article_ids'] as $articleId){
                        if(empty($articleId)) continue;
                        if(!in_array($articleId, $articleIds) && !in_array($articleId, $allArticleIds)){
                            $articleData = $cacheHandler->getArticle($articleId);
                            if(empty($articleData)) continue;
                            $articleIds[] = $articleId;
                            //$allArticleIds[] = $articleId;
                        }
                    }
                }
                if(count($articleIds) < $limit){
                    $feedArticleIds = array(); $feedArticleSort = array();
                    $articleFeed = $cacheHandler->getCategoryFeed(1,$tab['article_category_id']);
                    foreach($articleFeed as $articleId){
                        if(!in_array($articleId, $articleIds) && !in_array($articleId, $feedArticleIds) && !in_array($articleId, $allArticleIds)){
                            $articleData = $cacheHandler->getArticle($articleId);
                            if(empty($articleData)) continue;
                            $feedArticleIds[] = $articleId;
                            $feedArticleSort[] = $articleData['date_published'];
                            //$allArticleIds[] = $articleId;
                        }
                    }
                    if(count($feedArticleIds)){
                        array_multisort($feedArticleSort,SORT_DESC,$feedArticleIds);
                        foreach($feedArticleIds as $articleId){
                            $articleIds[] = $articleId;
                            if(count($articleIds) == $limit) break;
                        }
                    }
                }
                if(empty($articleIds)) continue;?>
                <div class="swiper-slide">
                    <div class="mb-2 article-category-title">
                        <a href="<?php echo base_url($articleCategoryData['permalink']);?>" class="d-inline-block ms-3 px-2 py-1 background-yellow border-radius-5 font-000 font-size-1rem font-weight-700"><?php echo $articleCategoryData['title'];?></a>
                    </div>
                    <div class="articles">
                        <?php $articleData = $cacheHandler->getArticle($articleIds[0]);?>
                        <div class="first-article mb-2">
                            <a href="<?php echo base_url($articleData['permalink']);?>" class="d-block mb-2 position-relative">
                                <?php if(!empty($articleData['opener_image_id'])){?>
                                    <img loading="lazy" width="400" height="225" src="<?php echo $cacheHandler->imageUrl($articleData['opener_image_id'],'rect400');?>" class="img-fluid mx-auto border-radius-10">
                                <?php } ?>
                                <div class="article-shadow position-absolute border-radius-10"></div>
                                <div class="px-3 article-text position-absolute font-fff font-weight-700 font-size-11rem z2-index"><?php echo $articleData['title'];?></div>
                            </a>
                        </div>
                        <div class="more-articles py-3 px-3 border-radius-10 background-grey">
                            <?php foreach($articleIds as $j=>$articleId){
                                if($j==0) continue;
                                $articleData = $cacheHandler->getArticle($articleId);?>
                                <a href="<?php echo base_url($articleData['permalink']);?>" class="article-title font-fff font-size-11rem"><?php echo $articleData['title'];?></a>
                                <?php if($j < (count($articleIds) - 1)){?>
                                    <div class="my-3 article-separator"></div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="swiper-button-next swiper-button-lock border-circle"><img src="<?php echo base_url('assets/img/chevron_right.svg'); ?>" alt="next slide" width="12" height="22" class=""></div>
    <div class="swiper-button-prev swiper-button-lock border-circle"><img src="<?php echo base_url('assets/img/chevron_right.svg'); ?>" alt="previus slide" width="12" height="22" class=""></div>
</div>
<div class="vertical-space"></div>