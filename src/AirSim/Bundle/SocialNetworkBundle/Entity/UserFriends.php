<?php

namespace AirSim\Bundle\SocialNetworkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserFriends
 *
 * @ORM\Table(name="user_friends", indexes={@ORM\Index(name="FK_user_friends", columns={"user_id"})})
 * @ORM\Entity
 */
class UserFriends
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
     * @ORM\Column(name="friend_id", type="integer", nullable=false)
     */
    private $friendId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="date", nullable=false)
     */
    private $dateAdded;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_accepted", type="boolean", nullable=false)
     */
    private $isAccepted;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_accepted", type="date", nullable=true)
     */
    private $dateAccepted;

    /**
     * @var integer
     *
     * @ORM\Column(name="group_id", type="integer", nullable=false)
     */
    private $groupId;

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
     * Get recId
     *
     * @return integer 
     */
    public function getRecId()
    {
        return $this->recId;
    }

    /**
     * Set friendId
     *
     * @param integer $friendId
     * @return UserFriends
     */
    public function setFriendId($friendId)
    {
        $this->friendId = $friendId;

        return $this;
    }

    /**
     * Get friendId
     *
     * @return integer 
     */
    public function getFriendId()
    {
        return $this->friendId;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return UserFriends
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
     * Set isAccepted
     *
     * @param boolean $isAccepted
     * @return UserFriends
     */
    public function setIsAccepted($isAccepted)
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }

    /**
     * Get isAccepted
     *
     * @return boolean 
     */
    public function getIsAccepted()
    {
        return $this->isAccepted;
    }

    /**
     * Set dateAccepted
     *
     * @param \DateTime $dateAccepted
     * @return UserFriends
     */
    public function setDateAccepted($dateAccepted)
    {
        $this->dateAccepted = $dateAccepted;

        return $this;
    }

    /**
     * Get dateAccepted
     *
     * @return \DateTime 
     */
    public function getDateAccepted()
    {
        return $this->dateAccepted;
    }

    /**
     * Set groupId
     *
     * @param integer $groupId
     * @return UserFriends
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * Get groupId
     *
     * @return integer 
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * Set user
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\Users $user
     * @return UserFriends
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
}
