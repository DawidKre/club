<?php

namespace Club\GameBundle\Controller;

use Club\GameBundle\Entity\PlayerStats;
use Club\GameBundle\Entity\Round;
use Club\GameBundle\Entity\Season;
use Club\GameBundle\Entity\TeamStats;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;

class GamesController extends BaseController
{
    /**
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/tabela", name="game_games_table")
     */
    public function tableAction(Request $request)
    {
        $orders = [
            't.matches' => 'Mecze',
            't.points' => 'Pkt.',
            't.wins' => 'Wygrane',
            't.draws' => 'Remisy',
            't.loses' => 'Poraż.',
            't.goalsScored' => 'Br +',
            't.goalsLost' => 'Br -',
            't.bilans' => '+/-'
        ];
        $orderBy = $request->query->get('orderBy');
        $orderDir = $request->query->get('orderDir');
        $tableRepository = $this->getTeamStatsRepository();
        $table = $tableRepository->getAllTable($orderBy, $orderDir);


        return $this->render('ClubGameBundle:Games:table.html.twig', array(
            'siteTitle' => 'Tabela',
            'table' => $table,
            'orders' => $orders
        ));
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/terminarz", name="game_games_games")
     */
    public function gamesAction()
    {
        $seasonRepo = $this->getSeasonRepository();
        $teamsSeason = $seasonRepo->getLastSeasonTeamsNumber();
        $roundRepo = $this->getRoundRepository();
        $rounds = $roundRepo->getAllRounds($teamsSeason);
        
        if (null === $rounds) {
            throw $this->createRoundsNotFoundHttpException();
        }
        
        return $this->render('ClubGameBundle:Games:games.html.twig', array(
            'rounds' => $rounds,
            'siteTitle' => 'Terminarz',
        ));
    }

    /**
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/statystyki", name="game_games_statistics")
     */
    public function statisticsAction(Request $request)
    {
        $orderBy = $request->query->get('orderBy');
        $orderDir = $request->query->get('orderDir');

        $orders = [
            'p.matches' => 'Mecze',
            'p.goals' => 'Gole',
            'p.assists' => 'Asysty',
            'p.yellowCards' => 'Zółte',
            'p.redCards' => 'Czerwone',
        ];
        $playerStatsRepo = $this->getPlayerStatsRepository();
        $playersStats = $playerStatsRepo->getPlayerStatistics($orderBy, $orderDir);
        
        if (null === $playersStats) {
            throw $this->createPlayerStatsNotFoundHttpException();
        }

        return $this->render('ClubGameBundle:Games:statistics.html.twig', array(
            'siteTitle' => 'Statystyki zawodników',
            'playersStats' => $playersStats,
            'orders' => $orders

        ));
    }
}
