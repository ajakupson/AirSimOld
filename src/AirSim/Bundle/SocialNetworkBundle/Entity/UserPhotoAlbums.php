<?php

namespace AirSim\Bundle\SocialNetworkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPhotoAlbums
 *
 * @ORM\Table(name="user_photo_albums", indexes={@ORM\Index(name="FK_user_photo_albums", columns={"user_id"})})
 * @ORM\Entity
 */
class UserPhotoAlbums
{
    /**
     * @var integer
     *
     * @ORM\Column(name="album_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $albumId;

    /**
     * @var string
     *
     * @ORM\Column(name="album_name", type="string", length=255, nullable=true)
     */
    private $albumName;

    /**
     * @var string
     *
     * @ORM\Column(name="album_title", type="string", length=200, nullable=false)
     */
    private $albumTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="album_description", type="string", length=750, nullable=true)
     */
    private $albumDescription;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="UserPhotos", mappedBy="album")
     */
    private $albumPhotos;

    public function __construct()
    {
        $this->albumPhotos = new ArrayCollection();
    }



    /**
     * Get albumId
     *
     * @return integer 
     */
    public function getAlbumId()
    {
        return $this->albumId;
    }

    /**
     * Set albumName
     *
     * @param string $albumName
     * @return UserPhotoAlbums
     */
    public function setAlbumName($albumName)
    {
        $this->albumName = $albumName;

        return $this;
    }

    /**
     * Get albumName
     *
     * @return string 
     */
    public function getAlbumName()
    {
        return $this->albumName;
    }

    /**
     * Set albumTitle
     *
     * @param string $albumTitle
     * @return UserPhotoAlbums
     */
    public function setAlbumTitle($albumTitle)
    {
        $this->albumTitle = $albumTitle;

        return $this;
    }

    /**
     * Get albumTitle
     *
     * @return string 
     */
    public function getAlbumTitle()
    {
        return $this->albumTitle;
    }

    /**
     * Set albumDescription
     *
     * @param string $albumDescription
     * @return UserPhotoAlbums
     */
    public function setAlbumDescription($albumDescription)
    {
        $this->albumDescription = $albumDescription;

        return $this;
    }

    /**
     * Get albumDescription
     *
     * @return string 
     */
    public function getAlbumDescription()
    {
        return $this->albumDescription;
    }

    /**
     * Set user
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\Users $user
     * @return UserPhotoAlbums
     */
    public function setUser(\AirSim\Bundle\SocialNetworkBundle\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AirSim\Bundle\SocialNetworkBundle\Entity\Users 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add albumPhotos
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\UserPhotos $albumPhotos
     * @return UserPhotoAlbums
     */
    public function addAlbumPhoto(\AirSim\Bundle\SocialNetworkBundle\Entity\UserPhotos $albumPhotos)
    {
        $this->albumPhotos[] = $albumPhotos;

        return $this;
    }

    /**
     * Remove albumPhotos
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\UserPhotos $albumPhotos
     */
    public function removeAlbumPhoto(\AirSim\Bundle\SocialNetworkBundle\Entity\UserPhotos $albumPhotos)
    {
        $this->albumPhotos->removeElement($albumPhotos);
    }

    /**
     * Get albumPhotos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAlbumPhotos()
    {
        return $this->albumPhotos;
    }
}
