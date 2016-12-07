<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Playlist
 *
 * @ORM\Table(name="playlists")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlaylistRepository")
 *
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
     * @Assert\NotBlank()
     * @Assert\Length(min="2")
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="public", type="boolean")
     */
    private $public;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $owner;

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
     * @return boolean
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * @param boolean $public
     *
     * @return $this
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     *
     * @return $this
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
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

        return $this;
    }

    public function removeTrack(Track $track)
    {
        $this->tracks->removeElement($track);
    }

    public function __construct(User $owner)
    {
        $this->owner = $owner;
        $this->tracks = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }
}

