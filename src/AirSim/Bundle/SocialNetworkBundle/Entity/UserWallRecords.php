<?php

namespace AirSim\Bundle\SocialNetworkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserWallRecords
 *
 * @ORM\Table(name="user_wall_records", indexes={@ORM\Index(name="FK_user_wall_records", columns={"to_id"})})
 * @ORM\Entity
 */
class UserWallRecords
{
    /**
     * @var integer
     *
     * @ORM\Column(name="wall_rec_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $wallRecId;

    /**
     * @var integer
     *
     * @ORM\Column(name="author_id", type="integer", nullable=false)
     */
    private $authorId;

    /**
     * @var string
     *
     * @ORM\Column(name="record_text", type="text", nullable=false)
     */
    private $recordText;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime", nullable=false)
     */
    private $dateAdded;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="to_id", referencedColumnName="user_id")
     * })
     */
    private $to;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="WallRecordLikes", mappedBy="wallRec")
     */
    private $wallRecordLikes;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="WallRecordPictures", mappedBy="wallRec")
     */
    private $wallRecordPictures;

    public function __construct()
    {
        $this->wallRecordLikes = new ArrayCollection();
        $this->wallRecordPictures = new ArrayCollection();
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
     * Set authorId
     *
     * @param integer $authorId
     * @return UserWallRecords
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;

        return $this;
    }

    /**
     * Get authorId
     *
     * @return integer 
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * Set recordText
     *
     * @param string $recordText
     * @return UserWallRecords
     */
    public function setRecordText($recordText)
    {
        $this->recordText = $recordText;

        return $this;
    }

    /**
     * Get recordText
     *
     * @return string 
     */
    public function getRecordText()
    {
        return $this->recordText;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return UserWallRecords
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime 
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set to
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\Users $to
     * @return UserWallRecords
     */
    public function setTo(\AirSim\Bundle\SocialNetworkBundle\Entity\Users $to = null)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get to
     *
     * @return \AirSim\Bundle\SocialNetworkBundle\Entity\Users 
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Add wallRecordLikes
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\WallRecordLikes $wallRecordLikes
     * @return UserWallRecords
     */
    public function addWallRecordLike(\AirSim\Bundle\SocialNetworkBundle\Entity\WallRecordLikes $wallRecordLikes)
    {
        $this->wallRecordLikes[] = $wallRecordLikes;

        return $this;
    }

    /**
     * Remove wallRecordLikes
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\WallRecordLikes $wallRecordLikes
     */
    public function removeWallRecordLike(\AirSim\Bundle\SocialNetworkBundle\Entity\WallRecordLikes $wallRecordLikes)
    {
        $this->wallRecordLikes->removeElement($wallRecordLikes);
    }

    /**
     * Get wallRecordLikes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWallRecordLikes()
    {
        return $this->wallRecordLikes;
    }

    /**
     * Add wallRecordPictures
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\WallRecordPictures $wallRecordPictures
     * @return UserWallRecords
     */
    public function addWallRecordPicture(\AirSim\Bundle\SocialNetworkBundle\Entity\WallRecordPictures $wallRecordPictures)
    {
        $this->wallRecordPictures[] = $wallRecordPictures;

        return $this;
    }

    /**
     * Remove wallRecordPictures
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\WallRecordPictures $wallRecordPictures
     */
    public function removeWallRecordPicture(\AirSim\Bundle\SocialNetworkBundle\Entity\WallRecordPictures $wallRecordPictures)
    {
        $this->wallRecordPictures->removeElement($wallRecordPictures);
    }

    /**
     * Get wallRecordPictures
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWallRecordPictures()
    {
        return $this->wallRecordPictures;
    }
}
