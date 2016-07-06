<?php
/**
 * Created by PhpStorm.
 * User: dawid
 * Date: 09.05.16
 * Time: 13:38
 */

namespace Club\GameBundle\EventListener;

use Club\GameBundle\Entity\Matches;
use Club\GameBundle\Entity\TeamStats;
use Doctrine\ORM\Event\LifecycleEventArgs;

class TeamStatsEvent
{

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Matches) {
            $this->saveTeamStats($entityManager);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Matches) {
            $this->saveTeamStats($entityManager);
        }

    }

    /**
     * @param $entityManager
     */
    private function saveTeamStats($entityManager)
    {
        $matchesRepo = $entityManager->getRepository(Matches::class);
        $matches = $matchesRepo->findAll();

        $teamStatsRepo = $entityManager->getRepository(TeamStats::class);
        $teamsStats = $teamStatsRepo->findAll();

        foreach ($teamsStats as $teamsStat) {
            $teamsStat->setMatches(0);
            $teamsStat->setWins(0);
            $teamsStat->setDraws(0);
            $teamsStat->setLoses(0);
            $teamsStat->setPoints(0);
            $teamsStat->setGoalsScored(0);
            $teamsStat->setGoalsLost(0);
            $teamsStat->setBilans(0);
        }

        foreach ($matches as $match) {
            $teamHome = $teamStatsRepo->findOneByTeam($match->getTeamHome());
            $teamAway = $teamStatsRepo->findOneByTeam($match->getTeamAway());

            $teamHomeMatches = $teamHome->getMatches();
            $teamHomeWins = $teamHome->getWins();
            $teamHomeDraws = $teamHome->getDraws();
            $teamHomeLoses = $teamHome->getLoses();
            $teamHomePoints = $teamHome->getPoints();
            $teamHomeGoalsScored = $teamHome->getGoalsScored();
            $teamHomeGoalsLost = $teamHome->getGoalsLost();

            $teamAwayMatches = $teamAway->getMatches();
            $teamAwayWins = $teamAway->getWins();
            $teamAwayDraws = $teamAway->getDraws();
            $teamAwayLoses = $teamAway->getLoses();
            $teamAwayPoints = $teamAway->getPoints();
            $teamAwayGoalsScored = $teamAway->getGoalsScored();
            $teamAwayGoalsLost = $teamAway->getGoalsLost();

            $teamHome->setMatches($teamHomeMatches + 1);
            $teamHome->setGoalsScored($teamHomeGoalsScored + $match->getScoreForHome());
            $teamHome->setGoalsLost($teamHomeGoalsLost + $match->getScoreForAway());

            $teamAway->setMatches($teamAwayMatches + 1);
            $teamAway->setGoalsScored($teamAwayGoalsScored + $match->getScoreForAway());
            $teamAway->setGoalsLost($teamAwayGoalsLost + $match->getScoreForHome());

            if (($match->getScoreForHome()) > ($match->getScoreForAway())) {
                $teamHome->setWins($teamHomeWins + 1);
                $teamHome->setPoints($teamHomePoints + 3);
                $teamAway->setLoses($teamAwayLoses + 1);
            } elseif (($match->getScoreForHome()) == ($match->getScoreForAway())) {
                $teamHome->setDraws($teamHomeDraws + 1);
                $teamHome->setPoints($teamHomePoints + 1);
                $teamAway->setDraws($teamAwayDraws + 1);
                $teamAway->setPoints($teamAwayPoints + 1);
            } else {
                $teamAway->setWins($teamAwayWins + 1);
                $teamAway->setPoints($teamAwayPoints + 3);
                $teamHome->setLoses($teamHomeLoses + 1);
            }

            $teamHome->setBilans(($teamHome->getGoalsScored()) - ($teamHome->getGoalsLost()));
            $teamAway->setBilans(($teamAway->getGoalsScored()) - ($teamAway->getGoalsLost()));

            $entityManager->persist($teamHome);
            $entityManager->persist($teamAway);
        }
        $entityManager->flush();
    }
}