<?php

namespace AppBundle\Repository;


/**
 * ArtistRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArtistRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllWithTracksPagerAware()
    {
        $qb = $this->createQueryBuilder('artists');
        $qb
            ->leftJoin('artists.tracks', 'tracks')
            ->select('artists', 'tracks')
        ;

        return $qb->getQuery()->execute();
    }
}
