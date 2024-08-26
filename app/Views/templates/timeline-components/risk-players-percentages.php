<span class="font-fff font-weight-normal analysis-text top-0 d-block w-100 mt-1 mb-1 text-end font-xl-size-09rem font-size-07rem">
    NUYMBER OF ANALYSIS: <?= $numberOfAnalysis?>
</span> 

<?php
if(!empty($percentages)) 
{
    foreach(array_reverse($percentages) as $key => $data) 
    { 
        if (!isset($data['players']) || empty($data['risk'])) 
        {
            continue;
        }

        $percentage = $data['risk'];

        switch ($percentage) {
            case $percentage < 15:
                $class = 'light-green';
                break;
            case $percentage < 30:
                $class = 'yellow';
                break;
            case $percentage < 35:
                $class = 'orange';
                break;
            default:
                $class = 'danger';
        }

        echo view('templates/timeline-components/player-progress-bar',
            [
                'cacheHandler' => $cacheHandler,
                'color' => $class,
                'percentage' => $percentage,  
                'players' => $data['players'],
            ]
        ); 
    }
}
?>

      