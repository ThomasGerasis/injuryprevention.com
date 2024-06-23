<div class="step-title d-flex text-white flex-column flex-md-row justify-content-center align-items-center w-100 padding-5 padding-xl-30">
     <div class="d-flex flex-column align-items-center flex-md-row col-12 col-lg-6 border-right border-1 step-title-text position-relative">
         <img class="d-block ball-image margin-bottom-10 margin-bottom-xl-0" src="<?php echo base_url('assets/img/ball.svg'); ?>"
              loading="lazy" alt="nba" width="250" height="100" id="player">
         <div class="d-flex flex-column text-center text-lg-start">
             <span class="d-block font-size-16rem ">Boston Celtics </span>
             <span class="d-block font-size-16rem "> VS <?=$gameOpponent?> </span>
             <span class="d-block font-size-12rem "> <?=$date?></span>
         </div>
     </div>
     <div class="d-flex flex-column justify-content-center margin-top-20 margin-top-xl-0 flex-md-row col-12 col-lg-6">
         <span class="d-block font-weight-bold font-size-18rem "> Boston Celtics Team's Players </span>
     </div>
</div>
<?php $randomId = getToken(8); ?>
<div class="position-relative players-swiper-container swiper-container margin-top-20 w-100 px-3" data-breakpoint="type2" data-prefix="players" id="players-swipe-<?php echo $randomId;?>">
    <div class="swiper players-swiper">
        <div class="swiper-wrapper">
            <?php foreach ($players as $player) {
                $playerDetails = $cacheHandler->getFixturePlayerDetails($player['Player']);
                $dataLogo = isset($playerDetails['logo']) ? base_url('assets/img/players/'.$playerDetails['logo'].'.svg') : '';
                ?>
                <div class="swiper-slide player-row-slide d-flex align-items-center text-center font-fff" data-name="<?=$playerDetails['name']?>" data-img="<?=$dataLogo?>">
                    <div class="player-box position-relative" style="background-image: url('<?=base_url('assets/img/player_frame.svg')?>')">
                        <?php if(!empty($playerDetails['logo']))
                        { ?>
                            <img class="d-block player-image" src="<?php echo base_url('assets/img/players/'.$playerDetails['logo'].'.svg'); ?>"
                                 loading="lazy" alt="nba" width="150" height="150" id="<?=$playerDetails['name']?>">
                        <?php } ?>
                        <span class="d-block font-fff font-bold font-size-09rem position-absolute" style="bottom: 15%"><?=$playerDetails['name']?></span>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="swiper-pagination"></div>
</div>