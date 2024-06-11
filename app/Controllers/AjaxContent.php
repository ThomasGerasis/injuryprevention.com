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


    public function fetchRisk()
    {
//        if (!$this->request->isAJAX()) {
//            return $this->fail('not ajax request');
//        }
        $gameOpponent = $this->request->getJSON()->gameOpponent;
        $gameDate = $this->request->getJSON()->gameDate;
        $players = $this->cacheHandler->getFixturePlayers($gameDate);

        ob_start();
        echo view('templates/risk-chart',
            [
                'cacheHandler' => $this->cacheHandler,
                'date' => $gameDate,
                'gameOpponent' => $gameOpponent,
                'players' => !empty($players) ? json_decode($players,true) : [],
            ]
        );
        $html = ob_get_clean();
        return $this->respond([
            'html' => json_encode($html)
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


        $data= [];

        return $this->respond([
            'html' => json_encode($html),
//            'chartData' => json_encode($data)
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
        echo view('templates/variance',
            [
                'cacheHandler' => $this->cacheHandler,
                'gameOpponent' => $gameOpponent,
                'date' => $gameDate,
                'players' => !empty($players) ? $players : []
            ]
        );

        $html = ob_get_clean();

        return $this->respond([
            'html' => json_encode($html),
        ]);

        die();
    }


}