<?php

namespace AppBundle\DataFixtures\Orm;

use AppBundle\Entity\Artist;
use AppBundle\Entity\Track;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $artist = new Artist();
        $artist->setName('Queen');
        $manager->persist($artist);

        $track = new Track();
        $track
            ->setName('The Show Must Go On')
            ->setReleasedAt(new \DateTime('1991-10-14'))
            ->setArtist($artist)
        ;
        $manager->persist($track);

        $track = new Track();
        $track
            ->setName('We Will Rock You')
            ->setReleasedAt(new \DateTime('1977-10-07'))
            ->setArtist($artist)
        ;
        $manager->persist($track);

        $track = new Track();
        $track
            ->setName('Bohemian Rhapsody')
            ->setReleasedAt(new \DateTime('1975-10-03'))
            ->setArtist($artist)
        ;
        $manager->persist($track);

        $artist = new Artist();
        $artist->setName('Ray Charles');
        $manager->persist($artist);


        $track = new Track();
        $track
            ->setName('Georgia on My Mind')
            ->setReleasedAt(new \DateTime('1960-01-01'))
            ->setArtist($artist)
        ;
        $manager->persist($track);

        $track = new Track();
        $track
            ->setName('Hit the Road Jack')
            ->setReleasedAt(new \DateTime('1961-01-01'))
            ->setArtist($artist)
        ;
        $manager->persist($track);

        $artist = new Artist();
        $artist->setName('Iron Maiden');
        $manager->persist($artist);

        $track = new Track();
        $track
            ->setName('The Trooper')
            ->setReleasedAt(new \DateTime('1983-06-20'))
            ->setArtist($artist)
        ;
        $manager->persist($track);

        $manager->flush();
    }
}