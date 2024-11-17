<?php
switch (true) {
    case ($risk == 1):
        $riskText = "Negligible";
        break;
    case ($risk >= 2 && $risk <= 3):
        $riskText = "Low Risk";
        break;
    case ($risk >= 4 && $risk <= 7):
        $riskText = "Medium Risk";
        break;
    case ($risk >= 8 && $risk <= 10):
        $riskText = "High Risk";
        break;
    case ($risk == 11):
        $riskText = "Very High Risk";
        break;
    default:
        $riskText = "unknown"; // Handle unexpected values
}

?>

<div class="d-flex justify-content-between align-items-center col-12 px-2">
    <span class="font-fff font-weight-normal top-0 d-block mt-1 mb-1 text-start font-xl-size-09rem font-size-07rem">
       <?=$riskText?>
    </span>
    <span class="font-fff font-weight-normal d-block mt-1 mb-1 text-end font-xl-size-09rem font-size-07rem">
        NUYMBER OF ANALYSIS: <?= $numberOfAnalysis?>
    </span> 
</div>

<?php $randomId = getToken(8); ?>
<div class="position-relative risks-swiper-container swiper-container margin-top-0 margin-top-xl-20 w-100 px-3" data-breakpoint="type3" data-prefix="risks" id="risks-swipe-<?php echo $randomId;?>">
    <div class="swiper risks-swiper">
        <div class="swiper-wrapper">
           <?php
            if(!empty($percentages)) 
            {
                foreach(array_reverse($percentages) as $key => $data) 
                {  
                    
                    if (!isset($data['players']) || empty($data['risk'])) 
                    {
                        continue;
                    }
                ?>
                    <div class="swiper-slide d-flex align-items-center text-center font-fff">
                        <?php   
                        $percentage = $data['risk'];
                        $class = 'orange';
                
                        echo view('templates/timeline-components/player-progress-bar',
                            [
                                'cacheHandler' => $cacheHandler,
                                'color' => $class,
                                'percentage' => $percentage,  
                                'players' => $data['players'],
                            ]
                        ); 

                        ?>
                            
                        <span class="d-block font-fff font-bold font-size-08rem margin-top-10"> 
                            <?= $percentage?>% Players participation on this risk scale
                         </span>

                    </div>
                <?php
                } 
            }
            ?>
        </div>
    </div>

    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>

    <!-- <div class="swiper-pagination"></div> -->
</div>