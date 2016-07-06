<?php
/**
 * Created by PhpStorm.
 * User: dawid
 * Date: 09.05.16
 * Time: 13:38
 */

namespace Club\GameBundle\EventListener;


use Club\GameBundle\Entity\PlayerStats;
use Club\GameBundle\Entity\Scores;
use Doctrine\ORM\Event\LifecycleEventArgs;

class PlayersGoalsEvent
{

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Scores) {
            $this->savePlayerGoals($entityManager);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Scores) {
            $this->savePlayerGoals($entityManager);
        }
    }

    /**
     * @param $entityManager
     */
    private function savePlayerGoals($entityManager)
    {
        $scoresRepo = $entityManager->getRepository(Scores::class);
        $scores = $scoresRepo->findAll();

        $playerStatsRepo = $entityManager->getRepository(PlayerStats::class);
        $playerStats = $playerStatsRepo->findAll();

        foreach ($playerStats as $playerStat) {
            $playerStat->setGoals(0);
        }

        foreach ($scores as $score) {
            $player = $playerStatsRepo->findOneByPlayer($score->getPlayer());
            $playerGoals = $player->getGoals();
            $player->setGoals($playerGoals + $score->getNumGoals());
            $entityManager->persist($player);

        }
        $entityManager->flush();
    }
}