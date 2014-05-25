<?php

namespace AirSim\Bundle\SocialNetworkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WallRecordPictures
 */
class WallRecordPictures
{
    /**
     * @var integer
     */
    private $wallRecId;

    /**
     * @var integer
     */
    private $pictureId;

    /**
     * @var integer
     */
    private $recId;


    /**
     * Set wallRecId
     *
     * @param integer $wallRecId
     * @return WallRecordPictures
     */
    public function setWallRecId($wallRecId)
    {
        $this->wallRecId = $wallRecId;

        return $this;
    }

    /**
     * Get wallRecId
     *
     * @return integer 
     */
    public function getWallRecId()
    {
        return $this->wallRecId;
    }

    /**
     * Set pictureId
     *
     * @param integer $pictureId
     * @return WallRecordPictures
     */
    public function setPictureId($pictureId)
    {
        $this->pictureId = $pictureId;

        return $this;
    }

    /**
     * Get pictureId
     *
     * @return integer 
     */
    public function getPictureId()
    {
        return $this->pictureId;
    }

    /**
     * Get recId
     *
     * @return integer 
     */
    public function getRecId()
    {
        return $this->recId;
    }
}
