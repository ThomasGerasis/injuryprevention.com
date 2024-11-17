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

    <div class="col-12 col-lg-5 position-relative d-flex flex-column" id="numberOfAnalysis">
       
    </div>
</div>

<span class="font-fff font-weight-normal d-flex d-lg-none align-items-center w-100 font-fff-opacity-75 text-decoration-none justify-content-center font-size-09rem">
    <?=$timelineTexts['copyright_text'] ?? '*No copyright infringement is intended.'?>  <img class="d-block me-2 ms-2" src="<?php echo base_url('assets/img/info.svg'); ?>" 
    loading="lazy"  alt="Info" width="18" height="18"> 
</span>

<?php echo view('templates/timeline-components/see-all-matches-mobile', []); ?>