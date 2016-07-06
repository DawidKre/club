<?php
/**
 * Created by PhpStorm.
 * User: dawid
 * Date: 09.05.16
 * Time: 13:38
 */

namespace Club\GameBundle\EventListener;

use Club\GameBundle\Entity\Matches;

use Club\GameBundle\Entity\PlayerStats;
use Doctrine\ORM\Event\LifecycleEventArgs;

class PlayersStatsEvent
{

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Matches) {

            $this->saveMatchesPlayed($entityManager);
            $this->saveCards($entityManager);
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

            $this->saveMatchesPlayed($entityManager);
            $this->saveCards($entityManager);
        }
    }

    /**
     * @param $entityManager
     */
    private function saveMatchesPlayed($entityManager)
    {
        $matchesRepo = $entityManager->getRepository(Matches::class);
        $matches = $matchesRepo->findAll();

        $playerStatsRepo = $entityManager->getRepository(PlayerStats::class);
        $playerStats = $playerStatsRepo->findAll();

        foreach ($playerStats as $playerStat) {
            $playerStat->setMatches(0);
        }

        foreach ($matches as $match) {
            foreach ($match->getSquad() as $player) {
                $playerMatches = $playerStatsRepo->findOneByPlayer($player);
                $matchesPlayed = $playerMatches->getMatches();
                $playerMatches->setMatches($matchesPlayed + 1);
                $entityManager->persist($playerMatches);
            }
        }
        $entityManager->flush();
    }

    /**
     * @param $entityManager
     */
    private function saveCards($entityManager)
    {
        $matchesRepo = $entityManager->getRepository(Matches::class);
        $matches = $matchesRepo->findAll();

        $playerStatsRepo = $entityManager->getRepository(PlayerStats::class);
        $playerStats = $playerStatsRepo->findAll();

        foreach ($playerStats as $playerStat) {
            $playerStat->setYellowCards(0);
            $playerStat->setRedCards(0);
        }

        foreach ($matches as $match) {
            foreach ($match->getYellowCards() as $player) {
                $playerCards = $playerStatsRepo->findOneByPlayer($player);
                $yellowCards = $playerCards->getYellowCards();
                $playerCards->setYellowCards($yellowCards + 1);
                $entityManager->persist($playerCards);
            }
            foreach ($match->getRedCards() as $player) {
                $playerCards = $playerStatsRepo->findOneByPlayer($player);
                $redCards = $playerCards->getRedCards();
                $playerCards->setRedCards($redCards + 1);
                $entityManager->persist($playerCards);
            }
        }
        $entityManager->flush();
    }
}