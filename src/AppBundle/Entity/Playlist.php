<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Playlist
 *
 * @ORM\Table(name="playlists")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlaylistRepository")
 */
class Playlist
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var Collection|Track[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Track")
     * @ORM\JoinTable(name="playlists_tracks")
     */
    private $tracks;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Playlist
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Track[]|Collection
     */
    public function getTracks()
    {
        return $this->tracks;
    }

    /**
     * @param Track $track
     *
     * @return $this
     */
    public function addTrack(Track $track)
    {
        $this->tracks->add($track);

        if ($track->get)

        return $this;
    }

    public function removeTrack(Track $track)
    {
        $this->tracks->removeElement($track);
    }

    public function __construct()
    {
        $this->tracks = new ArrayCollection();
    }
}

