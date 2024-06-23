<h1 class="font-fff font-bold d-block w-100 text-center margin-top-0 margin-bottom-5 margin-bottom-xl-10">IN-GAME</h1>
<span class="font-fff d-block w-100 text-center font-xl-size-14rem">
    BOSTON CELTICS 2017-2018 NO WEARABLES HUMAN MOVEMENT ANALYSIS INTO INJURY RISK
    AND INJURIES OCCURED AFTER OUR DANGER OF INJURY REPORT
</span>
<?php $randomId = getToken(8); ?>
<div class="position-relative timeline-swiper-container swiper-container  margin-top-xl-20 w-100 px-3" data-breakpoint="type1" data-prefix="timeline" id="timeline-swipe-<?php echo $randomId;?>">
    <div class="swiper timeline-swiper">
        <div class="swiper-wrapper">
            <?php
            $i =0;
            foreach ($matches as $date => $game) { ?>
                <div class="swiper-slide timeline-slide d-flex flex-column align-items-center text-center font-fff"
                     data-opponent="<?=$game[0]['Opponent']?>"
                     data-date ="<?=$date?>"
                     data-slide="<?=$i?>"
                >
                    <img class="d-block ball-image margin-bottom-2 margin-bottom-xl-40" src="<?php echo base_url('assets/img/ball.svg'); ?>"
                         loading="lazy" alt="nba" width="250" height="100" id="ball">
                    <div class="match-details margin-top-20 margin-top-xl-0">
                        <div class="d-flex flex-column font-xl-size-09rem">
                            <span class="d-block font-xl-size-09rem ">Boston Celtics </span>
                            <span class="d-block font-xl-size-09rem "> VS <?=$game[0]['Opponent']?> </span>
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





