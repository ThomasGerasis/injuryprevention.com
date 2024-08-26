<div class="progress">
    <div class="progress-bar bg-<?=$color?>" role="progressbar" style="width: <?=$percentage?>%;" aria-valuenow="<?=$percentage?>" aria-valuemin="0" aria-valuemax="100">
        <div class="d-flex ps-2 pe-2 align-items-center justify-content-between w-100 h-100">
            <span class="font-size-06rem"><?=$percentage?>%</span>
        </div>
    </div>

    <?php foreach($players as $key => $player){
         $playerDetails = $cacheHandler->getFixturePlayerDetails($player);
        ?>
        <div class="player-box avatar d-flex flex-column justify-content-center align-items-center position-relative tooltip show shown" data-bs-toggle="tooltip" data-bs-placement="top" title="<?=$player?>" >
            <img class="d-block player-image" src="<?php echo base_url('assets/img/players/'.$playerDetails['logo'].'.svg'); ?>"
            loading="lazy" alt="nba" width="40" height="40" alt="<?=$player?>">
        </div>
    <?php } ?>

</div>