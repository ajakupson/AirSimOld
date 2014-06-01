<?php

namespace AirSim\Bundle\SocialNetworkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WallRecordPictures
 *
 * @ORM\Table(name="wall_record_pictures", indexes={@ORM\Index(name="FK_wall_record_pictures", columns={"wall_rec_id"}), @ORM\Index(name="FK_photos_wall_record_pictures", columns={"picture_id"})})
 * @ORM\Entity
 */
class WallRecordPictures
{
    /**
     * @var integer
     *
     * @ORM\Column(name="rec_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $recId;

    /**
     * @var \UserPhotos
     *
     * @ORM\ManyToOne(targetEntity="UserPhotos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="picture_id", referencedColumnName="photo_id")
     * })
     */
    private $picture;

    /**
     * @var \UserWallRecords
     *
     * @ORM\ManyToOne(targetEntity="UserWallRecords")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wall_rec_id", referencedColumnName="wall_rec_id")
     * })
     */
    private $wallRec;



    /**
     * Get recId
     *
     * @return integer 
     */
    public function getRecId()
    {
        return $this->recId;
    }

    /**
     * Set picture
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\UserPhotos $picture
     * @return WallRecordPictures
     */
    public function setPicture(\AirSim\Bundle\SocialNetworkBundle\Entity\UserPhotos $picture = null)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return \AirSim\Bundle\SocialNetworkBundle\Entity\UserPhotos 
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set wallRec
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\UserWallRecords $wallRec
     * @return WallRecordPictures
     */
    public function setWallRec(\AirSim\Bundle\SocialNetworkBundle\Entity\UserWallRecords $wallRec = null)
    {
        $this->wallRec = $wallRec;

        return $this;
    }

    /**
     * Get wallRec
     *
     * @return \AirSim\Bundle\SocialNetworkBundle\Entity\UserWallRecords 
     */
    public function getWallRec()
    {
        return $this->wallRec;
    }
}
