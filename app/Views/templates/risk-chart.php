<?php echo view('templates/timeline-components/step-title',
        [
            'date' => $date,
            'gameOpponent' => $gameOpponent,  
            'chart' => 'teams',
        ]
); ?>

<div class="position-relative padding-top-20 w-100 d-flex flex-wrap mx-auto">
    <div class="col-12 col-lg-7">
        <svg id="riskChart" width="700" height="280"></svg>
    </div>

    <div class="col-12 col-lg-5 position-relative d-flex flex-column justify-content-center" id="numberOfAnalysis">
       
    </div>
</div>

<?php echo view('templates/timeline-components/see-all-matches-mobile', []); ?>