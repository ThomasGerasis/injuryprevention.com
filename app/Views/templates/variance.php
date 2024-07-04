<div class="step-title d-flex text-white flex-column flex-md-row justify-content-center align-items-center w-100  padding-xl-30">
    <div class="d-flex flex-column align-items-center flex-md-row col-12 col-lg-6 border-right border-1 step-title-text position-relative">
        <img class="d-block ball-image" src="<?php echo base_url('assets/img/ball.svg'); ?>"
             loading="lazy" alt="nba" width="250" height="100" id="player">
        <div class="d-flex flex-column text-center text-lg-start">
            <span class="d-block font-size-16rem ">Boston Celtics </span>
            <span class="d-block font-size-16rem "> VS <?=$gameOpponent?> </span>
            <span class="d-block font-size-12rem "> <?=$date?></span>
        </div>
    </div>
    <div class="d-flex flex-column justify-content-center flex-md-row col-12 col-lg-6 margin-top-20 margin-top-xl-0">
        <span class="d-block font-weight-bold font-size-16rem "> VARIANCE </span>
    </div>
</div>
<div class="position-relative margin-top-20 w-sm-100 w-80 d-block mx-auto px-xl-3 px-1">
    <canvas class="d-none d-xl-block" id="barChart" width="900" height="300"></canvas>
    <canvas class="d-block d-xl-none" id="barChartMobile" width="600" height="700"></canvas>
</div>