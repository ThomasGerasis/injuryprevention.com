<h1 class="font-fff font-bold d-block w-100 text-center mt-lg-0 mt-3 margin-bottom-5 margin-bottom-xl-10">
    <?php echo $timelineTexts['main_title'] ??  'IN-GAME' ;?>
</h1>
<span class="font-fff d-block w-100 text-center font-xl-size-14rem">

    <?php echo $timelineTexts['main_description'] ?? 'BOSTON CELTICS 2017-2018 NO WEARABLES HUMAN MOVEMENT ANALYSIS INTO INJURY RISK
    AND INJURIES OCCURED AFTER OUR DANGER OF INJURY REPORT';?>

</span>
<?php $randomId = getToken(8); ?>
<div class="position-relative timeline-swiper-container swiper-container margin-top-xl-20 w-100 px-3 matches-slider" data-breakpoint="type1" data-prefix="timeline" id="timeline-swipe-<?php echo $randomId;?>">
    <div class="swiper timeline-swiper">
        <div class="swiper-wrapper">
            <?php
            $i =0;
            foreach ($matches as $date => $game) {
                $isInjury = isset($game[0]['Injury']) ? true : false;
                $slideImge = $isInjury ? 'injury.svg' : 'basketball.svg';

                $date = $isInjury ? str_replace('injury', '', $date) : $date;
                $timestamp = strtotime($date);
                $month = date('n', $timestamp);
                $year = date('Y', $timestamp); 
                ?>

                <div class="swiper-slide timeline-slide d-flex flex-column align-items-center text-center font-fff <?= $isInjury ? 'injury' : 'stats'?>"
                     data-opponent = "<?=$game[0]['Opponent']?>"
                     data-date = "<?=$date?>"
                     data-month = "<?=$month?>"
                     data-year = "<?=$year?>"
                     data-slide = "<?=$i?>"
                >
                    <img class="d-block ball-image margin-bottom-2 margin-bottom-xl-40" src="<?php echo base_url('assets/img/'.$slideImge); ?>"
                         loading="lazy" alt="nba" width="<?= $isInjury ? '150' : '250'?>" height="100" id="<?=$slideImge?>">
                    <div class="match-details margin-top-20 margin-top-xl-0">
                        <div class="d-flex flex-column font-xl-size-09rem">
                            <span class="d-block font-xl-size-09rem">
                                Boston Celtics 
                            </span>
                            <span class="d-block font-xl-size-09rem ">
                               <?php if($isInjury){ ?>
                                    Injuries
                               <?php }else{ ?>
                                VS <?=$game[0]['Opponent']?> 
                                <?php  } ?>
                            </span>
                        </div>
                        <span class="d-block font-xl-size-06rem"><?=$date?></span>
                    </div>
                </div>
            <?php
                $i++;
            } ?>
        </div>
    </div>

    <div class="swiper-pagination"></div>

</div>


<div class="months-pagination w-100 mt-2">
    <div class="w-100 mb-2" style=" height: 0px; opacity: 0.25; border: 1px white solid"></div>
    <div class="d-flex text-white justify-content-between"> 
        <span class="text-left font-size-09rem font-xl-size-12rem d-flex align-items-center cursor-pointer month-controls previous">
            <img class="d-block me-2 pt-0 pl-lg-1" src="<?php echo base_url('assets/img/arrow-left.svg'); ?>" loading="lazy"  alt="Arrow Left" width="13" height="17">
            PREVIOUS MONTH 
        </span>
        <span class="text-right font-size-09rem font-xl-size-12rem  d-flex align-items-center cursor-pointer month-controls next">
            NEXT MONTH 
            <img class="d-block ms-2 pt-0 pl-lg-1" src="<?php echo base_url('assets/img/arrow-right.svg'); ?>" loading="lazy"  alt="Arrow Right" width="13" height="17">
        </span>
    </div>
</div>




