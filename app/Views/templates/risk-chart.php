<div class="step-title d-flex text-white flex-column flex-md-row justify-content-center align-items-center w-100 padding-5 padding-xl-30">
    <div class="d-flex flex-column align-items-center flex-md-row w-50 border-right border-1">
        <img class="d-block ball-image" src="<?php echo base_url('assets/img/ball.svg'); ?>"
             loading="lazy" alt="nba" width="250" height="100" id="risk">
        <div class="d-flex flex-column text-left">
            <span class="d-block font-xl-size-16rem ">Boston Celtics </span>
            <span class="d-block font-xl-size-16rem "> VS <?=$gameOpponent?> </span>
            <span class="d-block font-xl-size-12rem "> <?=$date?></span>
        </div>
    </div>
    <div class="d-flex flex-column justify-content-center flex-md-row w-50">
        <span class="d-block font-weight-bold font-xl-size-16rem "> TEAMS </span>
    </div>
</div>
<div class="position-relative margin-top-20 w-100 d-flex flex-wrap mx-auto px-3">
    <div class="col-12 col-lg-6">
        <canvas id="riskChart" width="900" height="300"></canvas>
    </div>

    <div class="col-12 col-lg-6" id="numberOfAnalysis">

    </div>
</div>