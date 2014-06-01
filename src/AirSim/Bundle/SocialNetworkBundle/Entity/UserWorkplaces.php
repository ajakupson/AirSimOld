<?php

namespace AirSim\Bundle\SocialNetworkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserWorkplaces
 *
 * @ORM\Table(name="user_workplaces", indexes={@ORM\Index(name="FK_user_workplaces", columns={"user_id"})})
 * @ORM\Entity
 */
class UserWorkplaces
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
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="workpalces")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="company", type="string", length=150, nullable=false)
     */
    private $company;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=150, nullable=false)
     */
    private $position;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=false)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="date", nullable=true)
     */
    private $endDate;

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
     * Set company
     *
     * @param string $company
     * @return UserWorkplaces
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return UserWorkplaces
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return UserWorkplaces
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return UserWorkplaces
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set user
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\Users $user
     * @return UserWorkplaces
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
     * Set userId
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\Users $userId
     * @return UserWorkplaces
     */
    public function setUserId(\AirSim\Bundle\SocialNetworkBundle\Entity\Users $userId = null)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return \AirSim\Bundle\SocialNetworkBundle\Entity\Users 
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
