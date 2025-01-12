<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class AjaxContent extends BaseController
{
    use ResponseTrait;

    public function fetchPlayers()
    {
//        if (!$this->request->isAJAX()) {
//            return $this->fail('not ajax request');
//        }
        $gameOpponent = $this->request->getJSON()->gameOpponent;
        $gameDate = $this->request->getJSON()->gameDate;
        $players = $this->cacheHandler->getFixturePlayers($gameDate);

        ob_start();

        echo view('templates/players-row',
            [
                'cacheHandler' => $this->cacheHandler,
                'date' => $gameDate,
                'gameOpponent' => $gameOpponent,
                'players' => $players ?? [],
            ]
        );


        $html = ob_get_clean();
        return $this->respond([
            'html' => json_encode($html)
        ]);

        die();
    }

    public function fetchInjuries(){
        $gameOpponent = $this->request->getJSON()->gameOpponent;
        $gameDate = $this->request->getJSON()->gameDate;
        $injuries = $this->cacheHandler->getFixtureInjuries($gameDate);

        ob_start();

        echo view('templates/injury-slider',
            [
                'cacheHandler' => $this->cacheHandler,
                'date' => $gameDate,
                'gameOpponent' => $gameOpponent,
                'players' => $injuries ?? [],
            ]
        );


        $html = ob_get_clean();
        return $this->respond([
            'html' => json_encode($html)
        ]);

        die();
    }

    public function fetchRisk()
    {
//        if (!$this->request->isAJAX()) {
//            return $this->fail('not ajax request');
//        }
        $gameOpponent = $this->request->getJSON()->gameOpponent;
        $gameDate = $this->request->getJSON()->gameDate;
        $players = $this->cacheHandler->getFixturePlayers($gameDate);

        $teamRisk = calculateTeamRisks($players);
        
        ob_start();

        echo view('templates/risk-chart',
            [
                'cacheHandler' => $this->cacheHandler,
                'date' => $gameDate,
                'gameOpponent' => $gameOpponent,
                'players' => !empty($players) ? $players : [],
            ]
        );
        $html = ob_get_clean();
        return $this->respond([
            'html' => json_encode($html),
            'teamRisk' => json_encode($teamRisk),
        ]);

        die();
    }

    
    public function buildPlayerPercentage()
    {
//        if (!$this->request->isAJAX()) {
//            return $this->fail('not ajax request');
//        }
        $numberOfAnalysis = $this->request->getJSON()->numberOfAnalysis;
        $risk = $this->request->getJSON()->risk;
        $playerPercentages = $this->request->getJSON()->playersRiskPercentages;
        $dataArray = (array) $playerPercentages;
        $riskValues = array_column($dataArray, (int)$risk);
        array_multisort($riskValues, SORT_DESC, $dataArray);

        $topPlayers = [];
        $limit = 4;
        $countGroups = 0;

        foreach ($dataArray as $player => $stats) 
        {
            $stats = (array) $stats;
            $playerRiskValue = $stats[$risk];

            if ($countGroups < $limit) {
                if (!isset($topPlayers[$playerRiskValue])) {
                    // New group for this risk value
                    $countGroups++;
                }
                // Add the player to the group
                $topPlayers[$playerRiskValue]['players'][] = $player;
                $topPlayers[$playerRiskValue]['risk'] = $playerRiskValue;
            } elseif (isset($topPlayers[$playerRiskValue])) {
                // If the current risk value group already exists in topPlayers, add the player to it
                $topPlayers[$playerRiskValue]['players'][] = $player;
                $topPlayers[$playerRiskValue]['risk'] = $playerRiskValue;
            } else {
                // Stop if the topPlayers array has reached the desired number of groups
                break;
            }
        }

        ob_start();

        echo view('templates/timeline-components/risk-players-percentages',
            [
                'cacheHandler' => $this->cacheHandler,
                'numberOfAnalysis' => $numberOfAnalysis,
                'percentages' => $topPlayers,
                'risk' => $risk
            ]
        );
        
        $html = ob_get_clean();

        return $this->respond([
            'html' => json_encode($html),
        ]);

        die();
    }
    
    public function buildPlayerChart()
    {
//        if (!$this->request->isAJAX()) {
//            return $this->fail('not ajax request');
//        }
        $gameOpponent = $this->request->getJSON()->gameOpponent;
        $gameDate = $this->request->getJSON()->gameDate;
        $playerName = $this->request->getJSON()->playerName;
        $playerLogo = $this->request->getJSON()->playerLogo;
        $playerKey = $this->request->getJSON()->playerKey;

        $players = $this->cacheHandler->getFixturePlayers($gameDate);

        $foundPlayer = [];

        foreach ($players as $key => $player) {
            if($player['Player'] === $playerKey){
                $foundPlayer = $player;        
                break;  // Exit the loop once the value is found
            }
        }

        $playerMovement = $foundPlayer['Player Movement'] ?? [];
        $caclulatedMovementData = caclulateMovementData($playerMovement);

        ob_start();

        echo view('templates/player-movement',
            [
                'cacheHandler' => $this->cacheHandler,
                'date' => $gameDate,
                'gameOpponent' => $gameOpponent,
                'playerName' => $playerName,
                'playerLogo' => $playerLogo,
            ]
        );

        $html = ob_get_clean();

        return $this->respond([
            'html' => json_encode($html),
            'playerMovementData' => json_encode($caclulatedMovementData)
        ]);

        die();
    }

    public function fetchMatches()
    {
//        if (!$this->request->isAJAX()) {
//            return $this->fail('not ajax request');
//        }
        $matches = $this->cacheHandler->getFixtures();

        ob_start();

        echo view('templates/timeline-slider',
                [
                    'cacheHandler' => $this->cacheHandler,
                    'matches' => !empty($matches) ? $matches : [],
                ]
        );

        $html = ob_get_clean();

        return $this->respond([
            'html' => json_encode($html),
        ]);

        die();
    }

    public function fetchVariance()
    {
//        if (!$this->request->isAJAX()) {
//            return $this->fail('not ajax request');
//        }
        ob_start();

        $gameOpponent = $this->request->getJSON()->gameOpponent;
        $gameDate = $this->request->getJSON()->gameDate;
        $players = $this->cacheHandler->getFixturePlayers($gameDate);

        $playerStats = fetchPlayersVariation($players);

        $playersVariation = []; 
        foreach ($playerStats as $playerName => $stats) { 
            $playersVariation[$playerName] = $stats['variance'];
        }
        arsort($playersVariation);


        $playerLogos = [];
        foreach ($players as $player){
            $playerName = $player['Player'];
            $playerDetails = $this->cacheHandler->getFixturePlayerDetails($playerName);
            $playerLogos[$playerName] = $playerDetails['logo'] ?? '';
        }

        echo view('templates/variance',
            [
                'cacheHandler' => $this->cacheHandler,
                'gameOpponent' => $gameOpponent,
                'date' => $gameDate,
                'players' => !empty($players) ? $players : [],
                'playersVariation' => $playersVariation,
            ]
        );

        $html = ob_get_clean();

        return $this->respond([
            'html' => json_encode($html),
            'playersVariation' => json_encode($playersVariation),
            'playerLogos' => json_encode($playerLogos),
        ]);

        die();
    }


    public function getLockedArticle(){

        $userData = $this->cacheHandler->getUser($this->request->getJSON()->email);
		$profile = [
			'userName' => $userData['username'] ?? '',
			'firstName' => $userData['firstname'] ?? '',
			'email' => $userData['email'] ?? '',
		];

        return $this->respond([
			'userAuthenticated' => isset($userData),
            'profile' => $profile
		]);

    }

    public function fetchInfo()
    {
        $timelineTexts = $this->cacheHandler->getOption('timelineSetup');
        $key = $this->request->getJSON()->infoKey;

        switch ($key) {
            case 'players':
                $databaseKey = 'players_info';
                $buttonKey = 'button_players_text';
                break;
            case 'variance':    
                $databaseKey = 'variance_info';
                $buttonKey = 'variance_button_text';
                break;
            case 'teams':    
                $databaseKey = 'team_stats_info';
                $buttonKey = 'team_stats_button_text';
                break;
            case 'player-movement':    
                $databaseKey = 'player_movement_info';
                $buttonKey = '';    
                break;
            default:
                $databaseKey = '';
                break;
        }

        $information = $timelineTexts[$databaseKey] ?? ''; 
        $title = $timelineTexts[$buttonKey] ?? 'Player Movement';

        return $this->respond([
			'title' => $title,
            'information' => strip_tags($information)
		]);

    }



    
}