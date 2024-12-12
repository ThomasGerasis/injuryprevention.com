<div class="container font-fff mt-4 mb-4">

    <?php if (strpos($pageContent,'<h1') === false) { ?>
        <div class="heading-container">
            <h1 class="font-fff font-weight-700 text-center d-block border-radius-40">
                <?php echo $pageData['title'];?>
            </h1>
        </div>
        <div class="vertical-space"></div>
    <?php } ?>

    <div class="page-content">
        <?php echo $pageContent;?>
    </div>

    <?php if(!empty($pageData['faqCategories'])){
        $faqCategories = $cacheHandler->getFaqCategories();
        $randomFaqId = getToken(10);?>
        <div class="filtered-content filter-container" id="tabbed_container_<?php echo $randomFaqId;?>">
            <div class="w-100 overflow-hidden">
                <div class="d-flex align-items-center justify-content-lg-between noscroolb toggle-filters">
                    <?php $tab_counter = 0; $selectedCategoryId = 0;
                    foreach($faqCategories as $faqCategoryId=>$faqCategoryTitle) {
                        if(!in_array($faqCategoryId, $pageData['faqCategories'])) continue; 
                        if($tab_counter ===0 ) $selectedCategoryId = $faqCategoryId; ?>
                        <div class="me-3 me-xl-0 px-3 py-1 px-lg-4 px-xxl-5 default-filter <?php echo $tab_counter === 0 ? 'active-filter' : '';?> border-radius-50 font-weight-700 cursor-pointer no-wrap toggle-filter" data-target="faq_<?php echo $faqCategoryId.'_'.$randomFaqId;?>"><?php echo $faqCategoryTitle;?></div>
                    <?php $tab_counter++;
                    } ?>
                </div>
            </div>
            <div class="vertical-space"></div>
            <div class="d-flex flex-wrap">
                <div class="col-12 col-lg-7 col-xl-8 order-1 order-lg-2">
                    <?php foreach($pageData['faqs'] as $faq){?>
                        <div class="faq_<?php echo $randomFaqId;?> filter-content faq-block background-fff mb-3 closed toggle-content border-radius-5">
                            <div class="faq-question position-relative border-radius-5 position-relative cursor-pointer"><?php echo $faq['question'];?>
                                <svg class="faq-question-icon position-absolute" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="16" height="2" x="2" y="9" class="hor"/>
                                    <rect width="2" height="16" x="9" y="2" class="ver" />
                                </svg>
                            </div>
							<div class="faq-answer font-000">
                                <?php echo $faq['answer'];?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="vertical-space"></div>
                </div>
                <div class="col-12 col-lg-5 col-xl-4 order-2 order-lg-1">
                    <div class="pe-lg-4">
                        <<?php echo empty($pageData['faq_heading']) ? 'div' : $pageData['faq_heading'];?> class="font-secondary font-weight-700 font-size-15rem mb-3">
                            <?php echo empty($pageData['faq_title']) ? 'Frequently ask questions' : $pageData['faq_title'];?>
                        </<?php echo empty($pageData['faq_heading']) ? 'div' : $pageData['faq_heading'];?>>
                        <?php if(!empty($pageData['faq_subtitle'])){?>
                            <div class="font-fff font-size-11rem mb-3">
                                <?php echo $pageData['faq_subtitle'];?>
                            </div>
                        <?php } ?>
                        <?php if(!empty($pageData['faq_content'])){?>
                            <div class="font-fff mb-3">
                                <?php echo $pageData['faq_content'];?>
                            </div>
                        <?php } ?>
                        <div class="mb-3 border-top border-333"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?> 
</div>