<?php

namespace AirSim\Bundle\SocialNetworkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPhotoAlbums
 */
class UserPhotoAlbums
{
    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     */
    private $albumName;

    /**
     * @var string
     */
    private $albumTitle;

    /**
     * @var string
     */
    private $albumDescription;

    /**
     * @var integer
     */
    private $albumId;


    /**
     * Set userId
     *
     * @param integer $userId
     * @return UserPhotoAlbums
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
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
     * Get albumId
     *
     * @return integer 
     */
    public function getAlbumId()
    {
        return $this->albumId;
    }
}
