<div class="position-relative main-home-container">

    <video autoplay muted loop id="bg-video">
        <source src="<?php echo base_url('assets/img/homepage.webm'); ?>" type="video/webm">
    </video>

<?= view("mobile-menu", ["cacheHandler" => $cacheHandler]) ?>
<?php echo view('menu', ['cacheHandler'=>$cacheHandler]); ?>

    <div class="main-content mt-5 pb-5">

        <h1 class="font-fff margin-0 d-block text-center font-xl-size-27rem font-size-22rem">
            <?php echo $pageData['welcome_title'] ?? 'Welcome'; ?>
        </h1>

        <span class="d-block text-center font-fff font-xl-size-22rem font-size-16rem">
            <?php echo $pageData['welcome_text'] ?? ''; ?>
        </span>

        <div class="arrow-container d-flex justify-content-center align-items-center overflow-hidden">
         <img class="arrow mx-auto margin-top-20 margin-bottom-20 d-block" 
            src="<?php echo base_url('assets/img/arrow-down.svg'); ?>" 
            loading="lazy" 
            alt="Arrow Down" width="50" height="50">
        </div>

        <div class="about-us font-fff w-100 text-center">
            <h3 class="d-block mx-auto font-fff margin-bottom-20 font-xl-size-22rem font-size-16rem">ABOUT US</h3>
            <p class="margin-bottom-10 font-fff d-block">
              <?php echo $aboutUsText ?? ''; ?>
            </p>

            <a href="/about-us" class="main-button font-fff primary-gradient">
                <span class="button-slanted-content"> READ MORE</span>
            </a>

        </div>
        
    </div>

</div>

<?php $matches = $cacheHandler->getFixtures(true); ?>
<div class="homepage-content bg-white margin-top-100 text-center pb-2 pb-xl-5">

    <div class="matches-container slider-container position-absolute">
        <div class="cutted-border-left d-flex flex-wrap h-100 padding-30 m-auto position-relative outer-container align-items-start">
            <?php echo view('templates/timeline-slider',
                [
                    'cacheHandler' => $cacheHandler,
                    'matches' => $matches,
                ]
            ); ?>
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
                            <svg class="faq-question-icon position-absolute" width="20" height="20" viewBox="0 0 28 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M27.74 6.86995L13.87 -4.69889e-05L5.09675e-07 6.86995L0 12.6999L13.87 5.82994L27.74 12.7L27.74 6.86995Z" fill="#F26239"/>
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