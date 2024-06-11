<div class="player-row align-items-center text-center w-50 font-fff">
    <img class="d-block player-image" src="<?php echo base_url('assets/img/player.png'); ?>" loading="lazy" alt="nba" width="120" height="240" id="player">
    <div class="player-box w-100">
            <span class="d-block font-fff font-bold font-size-08rem font-xl-size-18rem">
                <?=$players['name']?>
            </span>
        <span class="d-block font-fff font-size-07rem font-xl-size-16rem">
                <?=$players['surname']?>
            </span>
    </div>
</div>


<div class="player-injury-container w-50">
    <div class="injury-stats">
        <span class="font-fff font-xl-size-16rem font-size-1rem">INJURY RISK :</span>
        <span class="injury-risk-type high-risk font-fff text-center padding-5">HIGH</span>
    </div>

    <span class="divider margin-top-10 margin-bottom-10"></span>

    <div class="player-stats">
        <div class="injury-stats margin-top-10">
            <span class="stats-title font-size-08rem font-fff">NUMBER OF JUMPS</span>
            <span class="stat font-fff text-center font-bold">264</span>
        </div>
        <div class="injury-stats margin-top-10">
            <span class="stats-title font-size-08rem font-fff">RISK PERCENTAGE</span>
            <span class="stat font-red text-center font-bold">20%</span>
        </div>
        <div class="injury-stats margin-top-10">
            <span class="stats-title font-size-08rem font-fff">MEAN D.POINTS</span>
            <span class="stat font-fff text-center font-bold">4,69</span>
        </div>
    </div>
</div>