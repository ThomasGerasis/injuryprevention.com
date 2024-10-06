
<?php $randomId = getToken(8); ?>
<div class="position-relative injury-swiper-container swiper-container margin-top-20 w-100 px-3" data-breakpoint="type5" data-prefix="injury" id="injury-swipe-<?php echo $randomId;?>">
    <div class="swiper injury-swiper">
        <div class="swiper-wrapper">
            <?php foreach ($players as $key => $player) 
            {
                $playerDetails = $cacheHandler->getFixturePlayerDetails($player['Player']);
                $dataLogo = isset($playerDetails['logo']) ? base_url('assets/img/players/'.$playerDetails['logo'].'.svg') : '';
                ?>
                <div class="swiper-slide injury-slide d-flex flex-wrap align-items-center text-center font-fff" data-key="<?=$player['Player']?>"  data-name="<?=$playerDetails['name']?>" data-img="<?=$dataLogo?>">
                        <div class="d-flex flex-column align-items-center col-lg-6 col-12">
                            <a href="#" class="d-none d-lg-flex pb-1 font-weight-normal text-center font-fff-opacity-75 text-decoration-none justify-content-center font-size-11rem align-items-center all-matches"> 
                                <img class="d-block me-2" src="<?php echo base_url('assets/img/calendar.svg'); ?>" 
                                loading="lazy"  alt="Arrow Right" width="17" height="18"> 
                                SEE ALL MATCHES 
                            </a>
                            <div class="m-auto d-none d-lg-block mb-1 mt-1" style="width: 70%; height: 1px; opacity: 0.25; border: 1px white solid"></div>
                            <span class="d-block font-xl-size-14rem">Boston Celtics Injuries </span>
                            <span class="d-block font-xl-size-12rem"> <?=$date?></span>

                            <img class="d-block player-image" alt="<?=$playerDetails['name']?>" height="300" width="250" src="<?= $dataLogo; ?>">
                            <span class="d-block font-weight-bold font-fff font-size-16rem">
                                <?=$playerDetails['name']?>
                            </span>

                        </div>

                        <div class="d-flex flex-column align-items-center col-lg-6 col-12">

                            <img class="d-block ball-image margin-bottom-2 margin-bottom-xl-40" src="<?php echo base_url('assets/img/injury.svg'); ?>"
                            loading="lazy" alt="nba" width="150" height="100" id="injury">

                            <span class="d-block font-weight-bold font-fff font-size-16rem">
                                <?=$player['Injury']?>
                            </span>
                            <?php if(!isset($player['Date of return']) || $player['Date of return'] != '') { ?>
                                <span class="d-block font-xl-size-12rem text-center">
                                    Date Of Return : <?=$player['Date of return'];?>
                                </span>
                            <?php } ?>
                            <?php $timelineTexts = $cacheHandler->getOption('timelineSetup');?>
                            <span class="font-fff font-weight-normal d-flex align-items-center justify-content-center w-100 mt-1 mb-1 font-xl-size-12rem">
                                <?=$timelineTexts['copyright_text'] ?? '*No copyright infringement is intended.'?>  <img class="d-block me-2 ms-2" src="<?php echo base_url('assets/img/info.svg'); ?>" 
                                loading="lazy"  alt="Info" width="18" height="18"> 
                            </span>

                        </div>

               

                       
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="swiper-pagination"></div>
</div>

<?php echo view('templates/timeline-components/see-all-matches-mobile', []); ?>