<div class="step-title d-flex text-white flex-column flex-md-row justify-content-center align-items-center w-100 padding-xl-30">
    <div class="d-flex flex-column align-items-center flex-md-row col-12 col-lg-6 border-right border-1 step-title-text position-relative">
        <img class="d-block ball-image" src="<?php echo base_url('assets/img/ballv2.svg'); ?>"
             loading="lazy" alt="nba" width="170" height="100" id="player">
        <div class="d-flex flex-column text-center text-lg-start">
             <a href="#" class="d-lg-flex d-none font-weight-normal font-fff-opacity-75 text-decoration-none font-size-09rem align-items-center all-matches">
                <img class="d-block me-2" src="<?php echo base_url('assets/img/calendar.svg'); ?>" 
                loading="lazy"  alt="Arrow Right" width="17" height="18"> 
                SEE ALL MATCHES 
             </a>
             <div class="d-lg-block d-none mb-1 mt-1" style="width: 90%; height: 1px; opacity: 0.25; border: 1px white solid"></div>
            <span class="d-block font-size-12rem font-xl-size-16rem ">Boston Celtics </span>
            <span class="d-block font-size-12rem font-xl-size-16rem "> VS <?=$gameOpponent?> </span>
            <span class="d-block font-size-09rem font-xl-size-12rem "> <?=$date?></span>
        </div>
    </div>
    <?php echo view('templates/timeline-components/timeline-controls',
        [
            'chart' => $chart,
        ]
    ); ?>
</div>
<?php $timelineTexts = $cacheHandler->getOption('timelineSetup');?>
<span class="font-fff font-weight-normal d-flex align-items-center justify-content-lg-end justify-content-center w-100 mt-1 mb-1 font-xl-size-12rem">
    <?=$timelineTexts['copyright_text'] ?? '*No copyright infringement is intended.'?>  <img class="d-block me-2 ms-2" src="<?php echo base_url('assets/img/info.svg'); ?>" 
    loading="lazy"  alt="Info" width="18" height="18"> 
</span>