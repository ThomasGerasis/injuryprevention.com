<?php $timelineTexts = $cacheHandler->getOption('timelineSetup');?>
<div class="d-flex flex-row justify-content-center margin-top-30 controls margin-top-xl-0 flex-md-row col-12 col-lg-6">
    <div class="d-flex chartButton font-size-07rem font-xl-size-12rem align-items-center justify-content-center position-relative <?=($chart == 'players' ? 'active' : '')?>" data-chart="players">
        <?=$timelineTexts['button_players_text'] ?? 'PLAYERS';?>
    </div>
    <div class="position-relative chartButton font-size-07rem font-xl-size-12rem d-flex align-items-center justify-content-center text-white <?=($chart == 'variance' ? 'active' : '')?>" data-chart="variance">
        <?=$timelineTexts['variance_button_text'] ?? 'VARIANCE';?>
    </div>
    <div class="position-relative chartButton font-size-07rem font-xl-size-12rem d-flex align-items-center justify-content-center text-white <?=($chart == 'teams' ? 'active' : '')?>" data-chart="teams">
        <?=$timelineTexts['team_stats_button_text'] ?? 'VARIANCE';?>
    </div>
</div>