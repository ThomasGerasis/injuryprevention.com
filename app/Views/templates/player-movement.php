<div class="d-flex text-white flex-column flex-md-row justify-content-center w-100">
    <div class="d-flex flex-column align-items-center col-12 col-lg-6 player-movement-box align-items-center">
        <div class="d-flex flex-column">
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

    </div>

</div>
