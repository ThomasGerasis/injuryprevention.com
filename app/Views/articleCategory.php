<div class="container font-fff">
    <div class="heading-container mb-lg-5 mt-lg-5 mb-3">
        <h1 class="font-fff font-weight-700 border-radius-40 px-4"><?= $pageData['title'] ?></h1>
    </div>

    <div class="article-content">
        <?php echo $pageContent; ?>
    </div>

    <?php $categories = $cacheHandler->getArticleCategories(); ?>

    <div class="category-tab-wrapper mb-4">
        <?php
        foreach ($categories as $ID => $category) {
            $categoryFeedLength = $cacheHandler->getCategoryFeedLength($ID);
            if (empty($categoryFeedLength)) {
                continue;
            }
            if ($ID == $pageData['id']) {
                echo '<span class="selected category-tab d-block text-center font-blackish font-weight-700">
                ' . $category .'
                </span>';
            } else {
                $categoryData = $cacheHandler->getArticleCategory($ID);
                echo '<a href="' . base_url($categoryData['permalink']) . '"class="category-tab d-block text-center font-fff">
                ' . $category .
                '</a>';
            }
        }
        ?>
    </div>

    <div class="paginated-content-container mt-lg-5 mt-3 mb-5 position-relative" id="categoryArticles">
        <?php
        $articles = $cacheHandler->getCategoryFeed($pageNumber, $pageData['id']);
        if (!empty($articles)) {
            foreach ($articles as $articleID) {
                $articleData = $cacheHandler->getArticle($articleID);
                if (empty($articleData)) {
                    continue;
                }
                echo view('articles/articleRow',
                    ['articleData' => $articleData,
                        'isMobile' => $isMobile,
                        'cacheHandler' => $cacheHandler,
                        'ajaxRequest' => false
                    ]
                );
            }

            if ($totalPages > 1) {
                echo view('components/pagination',
                    ['totalPages' => $totalPages,
                        'permalink' => $pageData['permalink'],
                        'slug' => $pageData['permalink'],
                        'id' => $pageData['id'],
                        'ajaxPrefix' => 'articleCategoryFeed',
                        'page' => $pageNumber
                    ]
                );
            }
        }
        ?>
    </div>

</div>