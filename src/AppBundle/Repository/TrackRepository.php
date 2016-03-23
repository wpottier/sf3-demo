<?php

namespace AppBundle\Repository;

use Pagerfanta\Adapter\DoctrineORMAdapter;

/**
 * TrackRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TrackRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @return DoctrineORMAdapter
     */
    public function findAllWithArtistPagerAware()
    {
        $qb = $this->createQueryBuilder('tracks');
        $qb
            ->leftJoin('tracks.artist', 'artists')
            ->select('tracks', 'artists')
        ;

        return new DoctrineORMAdapter($qb);
    }
}