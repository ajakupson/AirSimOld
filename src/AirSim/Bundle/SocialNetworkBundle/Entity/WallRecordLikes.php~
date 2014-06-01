<?php

namespace AirSim\Bundle\SocialNetworkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WallRecordLikes
 *
 * @ORM\Table(name="wall_record_likes", indexes={@ORM\Index(name="FK_wall_record_likes", columns={"wall_rec_id"})})
 * @ORM\Entity
 */
class WallRecordLikes
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
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="like_dislike", type="boolean", nullable=false)
     */
    private $likeDislike;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_rated", type="datetime", nullable=false)
     */
    private $dateRated;

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
     * Set userId
     *
     * @param integer $userId
     * @return WallRecordLikes
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
     * Set likeDislike
     *
     * @param boolean $likeDislike
     * @return WallRecordLikes
     */
    public function setLikeDislike($likeDislike)
    {
        $this->likeDislike = $likeDislike;

        return $this;
    }

    /**
     * Get likeDislike
     *
     * @return boolean 
     */
    public function getLikeDislike()
    {
        return $this->likeDislike;
    }

    /**
     * Set dateRated
     *
     * @param \DateTime $dateRated
     * @return WallRecordLikes
     */
    public function setDateRated($dateRated)
    {
        $this->dateRated = $dateRated;

        return $this;
    }

    /**
     * Get dateRated
     *
     * @return \DateTime 
     */
    public function getDateRated()
    {
        return $this->dateRated;
    }

    /**
     * Set wallRec
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\UserWallRecords $wallRec
     * @return WallRecordLikes
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
