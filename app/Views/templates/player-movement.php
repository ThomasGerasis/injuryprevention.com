<div class="d-flex text-white flex-column flex-md-row justify-content-center w-100">
    <div class="d-flex flex-column align-items-center col-12 col-lg-6 player-movement-box align-items-center">
        <div class="d-flex flex-column">
             <a href="#" class="d-none d-lg-flex pb-1 font-weight-normal text-center font-fff-opacity-75 text-decoration-none justify-content-center font-size-11rem align-items-center all-matches"> 
                <img class="d-block me-2" src="<?php echo base_url('assets/img/calendar.svg'); ?>" 
                loading="lazy"  alt="Arrow Right" width="17" height="18"> 
                SEE ALL MATCHES 
             </a>
             <div class="m-auto d-none d-lg-block mb-1 mt-1" style="width: 70%; height: 1px; opacity: 0.25; border: 1px white solid"></div>
            <span class="d-block font-xl-size-14rem">Boston Celtics VS <?=$gameOpponent?> </span>
            <span class="d-block font-xl-size-12rem"> <?=$date?></span>
        </div>

        <img class="d-block player-image" alt="<?=$playerName?>" height="300" width="250" src="<?= $playerLogo; ?>">
        <span class="d-block font-weight-bold font-fff font-size-16rem"> <?=$playerName?></span>

    </div>

    <div class="d-flex flex-column justify-content-center col-12 col-lg-6 margin-top-20 margin-top-xl-0 movement-chart position-relative">
        <h3>Player Movement Analysis</h3>
        <div class="d-block m-auto outer-circle position-relative">
            <canvas id="radarChart" width="200" height="200"></canvas>
        </div>
        <a href="#" class="main-button m-auto font-fff mt-2 primary-gradient variancePlayersButton">
            <span class="button-slanted-content">SEE PLAYER ON VARIANCE</span>
        </a>
        <?php $timelineTexts = $cacheHandler->getOption('timelineSetup');?>
        <span class="font-fff font-weight-normal d-block w-100 mt-2 text-center font-xl-size-12rem">
             <?=$timelineTexts['copyright_text'] ?? '*No copyright infringement is intended.'?>
        </span>
    </div>

    <div class="d-block d-lg-none mb-1 mt-3" style="width: 90%; height: 1px; opacity: 0.25; border: 1px white solid"></div>
    <a href="#" class="d-flex d-lg-none w-100 pb-1 font-weight-normal text-center font-fff-opacity-75 text-decoration-none justify-content-center font-size-11rem align-items-center all-matches"> SEE ALL MATCHES 
        <img class="d-block ms-2" src="<?php echo base_url('assets/img/arrow-right.svg'); ?>" 
        loading="lazy"  alt="Arrow Right" width="13" height="17"> 
    </a>

</div>
