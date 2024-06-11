<div class="position-relative main-home-container pt-5">

    <div class="main-content pb-5">

        <h1 class="font-fff margin-0 d-block text-center font-xl-size-27rem font-size-22rem">
            WELCOME
        </h1>

        <span class="d-block text-center font-fff font-xl-size-22rem font-size-16rem">
            TO THE NON-INVASIVE ERA
        </span>

        <img class="mx-auto margin-top-20 margin-bottom-20 d-block" src="<?php echo base_url('assets/img/circle_button.svg'); ?>" loading="lazy" alt="Circle Button" width="50" height="50">

        <div class="about-us font-fff w-100 text-center">
            <h3 class="d-block mx-auto font-fff margin-bottom-20 font-xl-size-22rem font-size-16rem">ABOUT US</h3>
            <p class="margin-bottom-10 font-fff d-block">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type
                and scrambled it to make a type specimen book.
            </p>

            <a href="/about-us" class="main-button font-fff primary-gradient">
                <span class="button-slanted-content"> READ MORE</span>
            </a>

        </div>
        
    </div>

</div>

<?php $matches = $cacheHandler->getFixtures(); ?>
<div class="homepage-content bg-white margin-top-100 text-center pb-2 pb-xl-5">

    <div class="matches-container slider-container position-absolute">
        <div class="cutted-border-left d-flex flex-wrap h-100 padding-50 m-auto position-relative outer-container align-items-start">
            <?php echo view('templates/timeline-slider',
                [
                    'cacheHandler' => $cacheHandler,
                    'matches' => $matches,
                ]
            ); ?>
        </div>

        <div class="controls" style="display: none;">
            <div class="main-button font-fff matches">
                <span class="button-slanted-content">MATCHES</span>
            </div>

            <div class="main-button font-fff players" style="display: none;">
                <span class="button-slanted-content">PLAYERS</span>
            </div>


        </div>

    </div>

    <div class="container font-secondary font-xl-size-14rem font-size-11rem">
        <?php echo $pageContent;?>
    </div>
</div>

<?php if(!empty($pageData['faqs'])){
    $faqCategories = $cacheHandler->getFaqCategories();
    $randomFaqId = getToken(10);?>
    <div class="position-relative main-faq-container pb-5 pt-5">
        <div class="d-flex flex-wrap container">
                <div class="col-12 text-center font-secondary mb-3">
                    <div class="pe-lg-4">
                        <<?php echo empty($pageData['faq_heading']) ? 'div' : $pageData['faq_heading'];?> class="font-weight-700 font-size-12rem font-xl-size-18rem mb-3">
                        <?php echo empty($pageData['faq_title']) ? 'Frequently ask questions' : $pageData['faq_title'];?>
                    </<?php echo empty($pageData['faq_heading']) ? 'div' : $pageData['faq_heading'];?>>
                    <?php if(!empty($pageData['faq_subtitle'])){?>
                        <div class="font-size-xl-16rem font-size-11rem mb-3">
                            <?php echo $pageData['faq_subtitle'];?>
                        </div>
                    <?php } ?>
                    <?php if(!empty($pageData['faq_content'])){?>
                        <div class="mb-3 font-xl-size-14rem font-size-11rem">
                            <?php echo $pageData['faq_content'];?>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="col-12 d-flex flex-column align-items-center align-content-center">
                <?php foreach($pageData['faqs'] as $faq){?>
                    <div class="faq_<?php echo $faq['faq_category_id'].'_'.$randomFaqId;?> filter-content faq-block background-fff mb-1 closed toggle-content border-radius-5">
                        <div class="faq-question position-relative font-xl-size-14rem font-size-11rem border-radius-5 position-relative cursor-pointer"><?php echo $faq['question'];?>
                            <svg class="faq-question-icon position-absolute bi bi-chevron-up" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#f16138" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708z"/>
                            </svg>
                        </div>
                        <div class="faq-answer font-000"><?php echo $faq['answer'];?></div>
                    </div>
                <?php } ?>
                <div class="vertical-space"></div>
            </div>

    </div>
</div>
<?php } ?>