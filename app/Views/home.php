<div class="position-relative main-home-container">

    <video autoplay muted loop playsinline id="bg-video">
        <source src="<?php echo base_url('assets/img/homepage.webm'); ?>" type="video/webm">
        <source src="<?php echo base_url('assets/img/homepage.mp4'); ?>" type="video/mp4">
    </video>

<?= view("mobile-menu", ["cacheHandler" => $cacheHandler]) ?>
<?php echo view('menu', ['cacheHandler'=>$cacheHandler]); ?>

    <div class="main-content mt-5 pb-5">

        <h1 class="font-fff margin-top-xl-100 d-block text-center font-xl-size-40rem font-size-22rem">
            <?php echo $pageData['welcome_title'] ?? 'Welcome'; ?>
        </h1>

        <span class="d-block text-center font-fff font-xl-size-29rem font-size-16rem">
            <?php echo $pageData['welcome_text'] ?? ''; ?>
        </span>


        <div class="arrow-container d-none d-lg-flex justify-content-center margin-top-10 margin-top-xl-50 align-items-center overflow-hidden">
         <img class="arrow mx-auto margin-top-20 margin-bottom-20 d-block" 
            src="<?php echo base_url('assets/img/arrow-down.svg'); ?>" 
            alt="Arrow Down" width="50" height="50">
        </div>


        <div class="about-us font-fff w-100 text-center">
            <h3 class="d-none d-lg-block mx-auto font-fff margin-bottom-20 font-xl-size-29rem font-size-12rem">ABOUT US</h3>
            <div class="margin-bottom-10 font-xl-size-12rem font-size-08rem font-fff d-block">
                <?php echo $pageData['about_us_text'] ?? ''; ?>
            </div>

            <a href="/about-us" class="main-button font-fff primary-gradient">
                <span class="button-slanted-content"> READ MORE</span>
            </a>

        </div>


        <div class="arrow-container d-flex d-lg-none justify-content-center margin-top-10 margin-top-xl-50 align-items-center overflow-hidden">
         <img class="arrow mx-auto margin-top-20 margin-bottom-20 d-block" 
            src="<?php echo base_url('assets/img/arrow-down.svg'); ?>" 
            loading="lazy" 
            alt="Arrow Down" width="30" height="30">
        </div>


    </div>

</div>

<?php
$matches = $cacheHandler->getFixtures();
$timelineTexts = $cacheHandler->getOption('timelineSetup');
?>
<div class="homepage-content bg-white text-center pb-2 pb-xl-5">

    <div class="matches-container slider-container position-absolute">
        <div class="cutted-border-left d-flex flex-wrap h-100 padding-top-20 padding-left-30 padding-right-30 m-auto position-relative outer-container align-items-start">
            <?php echo view('templates/timeline-slider',
                [
                    'cacheHandler' => $cacheHandler,
                    'matches' => $matches,
                    'timelineTexts' => $timelineTexts
                ]
            ); ?>
        </div>
    </div>

    <div class="d-none matches-container position-absolute slider-step-container info-box-container">
        <div class="cutted-border-left info-box d-flex flex-column w-100 h-100 padding-30 m-auto align-items-start">
            <div class="d-flex justify-content-between align-items-center w-100">
                <span class="d-block text-center font-fff font-xl-size-14rem font-size-11rem info-title"></span>
                <img width="12" height="12"
                class="close-info-box"
                src="<?php echo base_url('assets/img/close_icon.svg'); ?>"
                loading="lazy" alt="close icon">
            </div>
            <div class="d-block mb-1 mt-1 w-100 margin-bottom-20" style="height: 1px; opacity: 0.25; border: 1px white solid;"></div>
            
            <span class="font-fff font-weight-normal w-100 mt-1 mb-1 font-xl-size-12rem font-size-08rem margin-bottom-20 info-text">
            </span>

            <span class="font-fff font-weight-normal d-flex align-items-center w-100 mt-1 mb-1 font-xl-size-12rem position-absolute" style="bottom:15px;">
                *No copyright infringement is intended.
                <img class="d-block me-2 ms-2" src="<?php echo base_url('assets/img/info.svg'); ?>" 
                loading="lazy"  alt="Info" width="18" height="18"> 
            </span>
        </div>
    </div>

    <div class="container content font-secondary font-xl-size-14rem font-size-11rem">
        <?php echo $pageContent;?>
    </div>
</div>

<?php if(!empty($pageData['faqs'])){
    $faqCategories = $cacheHandler->getFaqCategories();
    $randomFaqId = getToken(10);?>
    <div class="position-relative main-faq-container pb-5 pt-5">

        <img src="<?php echo base_url('assets/img/logo-plain.svg'); ?>" class="faq-logo" loading="lazy" alt="logo" width="150" height="150">

        <div class="d-flex flex-wrap container questions-container">
                <div class="col-12 text-center font-secondary mb-3">
                    <div class="pe-lg-4">
                        <<?php echo empty($pageData['faq_heading']) ? 'div' : $pageData['faq_heading'];?> class="font-weight-700 font-size-16rem font-xl-size-29rem mb-3">
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
                <?php foreach($pageData['faqs'] as $faq){ ?>
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