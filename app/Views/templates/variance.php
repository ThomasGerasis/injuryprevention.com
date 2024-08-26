
<?php echo view('templates/timeline-components/step-title',
        [
            'date' => $date,
            'gameOpponent' => $gameOpponent,  
            'chart' => 'variance',
        ]
); ?>

<div class="position-relative margin-top-20 w-sm-100 w-80 d-block mx-auto px-xl-3 px-1">
    <canvas class="d-none d-xl-block" id="barChart" width="900" height="300"></canvas>
    <canvas class="d-block d-xl-none" id="barChartMobile" width="600" height="700"></canvas>
</div>


<?php echo view('templates/timeline-components/see-all-matches-mobile', []); ?>