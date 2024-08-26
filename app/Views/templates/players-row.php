<?php echo view('templates/timeline-components/step-title',
        [
            'date' => $date,
            'gameOpponent' => $gameOpponent,  
            'chart' => 'players',
        ]
); ?>

<?php $randomId = getToken(8); ?>
<div class="position-relative players-swiper-container swiper-container margin-top-20 w-100 px-3" data-breakpoint="type2" data-prefix="players" id="players-swipe-<?php echo $randomId;?>">
    <div class="swiper players-swiper">
        <div class="swiper-wrapper">
            <?php foreach ($players as $key => $player) {
                $playerDetails = $cacheHandler->getFixturePlayerDetails($player['Player']);
                $dataLogo = isset($playerDetails['logo']) ? base_url('assets/img/players/'.$playerDetails['logo'].'.svg') : '';
                ?>
                <div class="swiper-slide player-row-slide d-flex align-items-center text-center font-fff" data-key="<?=$player['Player']?>"  data-name="<?=$playerDetails['name']?>" data-img="<?=$dataLogo?>">
                    <div class="player-box position-relative" style="background-image: url('<?=base_url('assets/img/player_frame.svg')?>')">
                        <?php if(!empty($playerDetails['logo']))
                        { ?>
                            <img class="d-block player-image" src="<?php echo base_url('assets/img/players/'.$playerDetails['logo'].'.svg'); ?>"
                                 loading="lazy" alt="nba" width="150" height="150" alt="<?=$playerDetails['name']?>">
                        <?php } ?>
                        <span class="d-block font-fff font-bold font-size-09rem position-absolute" style="bottom: 15%">
                            <?=$playerDetails['name']?>
                        </span>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="swiper-pagination"></div>
</div>

<?php echo view('templates/timeline-components/see-all-matches-mobile', []); ?>