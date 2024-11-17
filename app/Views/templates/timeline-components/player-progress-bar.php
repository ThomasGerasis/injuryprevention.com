<div class="progress">
    <div class="progress-bar bg-<?=$color?>" role="progressbar" style="width: <?=$percentage?>%;" aria-valuenow="<?=$percentage?>" aria-valuemin="0" aria-valuemax="100">
    </div>

    <div class="position-relative d-flex align-self-center w-100 px-3">
        <?php foreach ($players as $key => $player) {
            $playerDetails = $cacheHandler->getFixturePlayerDetails($player);
            $dataLogo = isset($playerDetails['logo']) ? base_url('assets/img/players/'.$playerDetails['logo'].'.svg') : '';
            ?>
            <div class="position-relative margin-left-10">
                <?php if(!empty($playerDetails['logo']))
                { ?>
                    <img class="d-block player-image" src="<?php echo base_url('assets/img/players/'.$playerDetails['logo'].'.svg'); ?>"
                        width="45" height="45" alt="<?=$player?>">
                <?php } ?>
                <span class="d-block font-fff font-bold font-size-08rem position-absolute">
                    <?=$player?>
                </span>
            </div>
        <?php } ?>
    </div>

</div>

