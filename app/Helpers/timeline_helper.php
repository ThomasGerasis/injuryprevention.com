<?php

function caclulateMovementData($playerMovementData)
{
    /// risks 0-11 scale
    if(empty($playerMovementData))
    {   
        return [
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0
        ];
    }

    $totalMoves = count($playerMovementData);

    $riskCount = array_fill(0, 12, 0);

    foreach ($playerMovementData as $movement) {
        $risk = $movement["Risk"];
        // $risk = 4
        $riskCount[$risk]++;
        // $riskCount[4] = 2
    }

    $riskPercentage = [];
    for ($i = 0; $i <= 11; $i++) 
    {
        $riskPercentage[$i] = round((($riskCount[$i] / $totalMoves) * 100),2);
    }

    return $riskPercentage;

}

function calculateTeamRisks($playersMovementData) {
    // Initialize the risk count for each level (0-11) for the team
    $teamTotalMoves = 0;
    $teamRiskCount = array_fill(0, 12, 0);

    // Process each player's movement data
    foreach ($playersMovementData as $playerData) 
    {

        $playerName = $playerData['Player'];
        $movements = $playerData['Player Movement'];
        if (empty($movements)) {    
            continue;   
        }

        // Initialize risk count for this player
        $riskCount = array_fill(0, 12, 0);
        // If the player has movements, count occurrences for each risk level
        foreach ($movements as $movement) {
            if (!isset($movement["Risk"])) {
                continue;
            }
            $risk = $movement["Risk"];
            if ($risk >= 0 && $risk <= 11) {
                $riskCount[$risk]++;
                // Count this movement in the team totals
                $teamRiskCount[$risk]++;
                $teamTotalMoves++;
            }
        }
       
        $playersRiskCount[$playerName] = $riskCount;
    }

    // Calculate the team risk percentages
    $teamRiskPercentage = [];
    for ($i = 0; $i <= 11; $i++) {
        $teamRiskPercentage[$i] = 0;
        if ($teamTotalMoves > 0) {
            $teamRiskPercentage[$i] = round((($teamRiskCount[$i] / $teamTotalMoves) * 100), 1);
        }
    }

    // Prepare data for how each player's movements contribute to each risk level
    $playerContributionToRiskLevel = [];
    foreach ($playersRiskCount as $player => $riskCounts) {
          $contribution = [];
          for ($i = 0; $i <= 11; $i++) {
            $contribution[$i] = 0;
              if ($teamRiskCount[$i] > 0) {
                  $contribution[$i] = round((($riskCounts[$i] / $teamRiskCount[$i]) * 100), 1);
              }
          }
          $playerContributionToRiskLevel[$player] = $contribution;
      }


    return [
        'teamRiskPercentage' => $teamRiskPercentage,
        'teamRiskCount' => $teamRiskCount,
        'playersRiskPercentages' => $playerContributionToRiskLevel,
    ];
}


function fetchPlayersVariation($playersMovementData) {
    $playersVariation = [];
    foreach ($playersMovementData as $player) {
        $playerName = $player['Player'];
        // Extract risk values
        $riskValues = array_column($player['Player Movement'], 'Risk');
        // Calculate variance and standard deviation
        $variation = calculateVariation($riskValues);
        
        $playersVariation[$playerName] = $variation;
    }

    return $playersVariation;
}

function calculateVariation($risks) {
    $n = count($risks);

    if ($n <= 1) {
        return [
            'variance' => 0,
            'standard_deviation' => 0
        ];
    }
    
    $mean = array_sum($risks) / $n;
    
    // Calculate variance
    $variance = 0;
    foreach ($risks as $risk) {
        $variance += pow($risk - $mean, 2);
    }
    $variance /= $n;

    // Standard deviation
    $stdDev = sqrt($variance);

    return [
        'variance' => $variance,
        'standard_deviation' => $stdDev 
    ];
}