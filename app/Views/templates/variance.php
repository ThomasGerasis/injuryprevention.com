
<?php echo view('templates/timeline-components/step-title',
        [
            'date' => $date,
            'gameOpponent' => $gameOpponent,  
            'chart' => 'variance',
        ]
); ?>

<div class="position-relative margin-top-20 d-flex justify-content-center col-12 px-xl-3 px-1">
    <canvas class="d-none d-xl-block" id="barChart" width="900" height="400"></canvas>
    <canvas class="d-block d-xl-none" id="barChartMobile"></canvas>
</div>


<span class="font-fff font-weight-normal d-flex d-lg-none align-items-center w-100 font-fff-opacity-75 text-decoration-none justify-content-center font-size-09rem">
    <?=$timelineTexts['copyright_text'] ?? '*No copyright infringement is intended.'?>  <img class="d-block me-2 ms-2" src="<?php echo base_url('assets/img/info.svg'); ?>" 
    loading="lazy" alt="Info" width="18" height="18"> 
</span>

<?php echo view('templates/timeline-components/see-all-matches-mobile', []); ?>