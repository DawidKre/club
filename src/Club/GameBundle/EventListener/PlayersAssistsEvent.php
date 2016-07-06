<?php
/**
 * Created by PhpStorm.
 * User: dawid
 * Date: 09.05.16
 * Time: 13:38
 */

namespace Club\GameBundle\EventListener;

use Club\GameBundle\Entity\Assists;
use Club\GameBundle\Entity\PlayerStats;
use Doctrine\ORM\Event\LifecycleEventArgs;

class PlayersAssistsEvent
{

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Assists) {
            $this->savePlayerAssists($entityManager);
        }

    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Assists) {
            $this->savePlayerAssists($entityManager);
        }

    }

    /**
     * @param $entityManager
     */
    private function savePlayerAssists($entityManager)
    {
        $assistsRepo = $entityManager->getRepository(Assists::class);
        $assists = $assistsRepo->findAll();

        $playerStatsRepo = $entityManager->getRepository(PlayerStats::class);
        $playerStats = $playerStatsRepo->findAll();

        foreach ($playerStats as $playerStat) {
            $playerStat->setAssists(0);
        }

        foreach ($assists as $assist) {
            $player = $playerStatsRepo->findOneByPlayer($assist->getPlayer());
            $playerAssists = $player->getAssists();
            $player->setAssists($playerAssists + $assist->getNumAssists());
            $entityManager->persist($player);

        }
        $entityManager->flush();
    }

}