<?php

namespace AirSim\Bundle\SocialNetworkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserHighEducation
 *
 * @ORM\Table(name="user_high_education", indexes={@ORM\Index(name="FK_user_high_education", columns={"user_id"})})
 * @ORM\Entity
 */
class UserHighEducation
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
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="highEducation")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="university", type="string", length=200, nullable=false)
     */

    private $university;

    /**
     * @var string
     *
     * @ORM\Column(name="faculty", type="string", length=200, nullable=true)
     */
    private $faculty;

    /**
     * @var string
     *
     * @ORM\Column(name="speciality", type="string", length=100, nullable=false)
     */
    private $speciality;

    /**
     * @var string
     *
     * @ORM\Column(name="degree", type="string", length=50, nullable=false)
     */
    private $degree;

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
     * Set university
     *
     * @param string $university
     * @return UserHighEducation
     */
    public function setUniversity($university)
    {
        $this->university = $university;

        return $this;
    }

    /**
     * Get university
     *
     * @return string 
     */
    public function getUniversity()
    {
        return $this->university;
    }

    /**
     * Set faculty
     *
     * @param string $faculty
     * @return UserHighEducation
     */
    public function setFaculty($faculty)
    {
        $this->faculty = $faculty;

        return $this;
    }

    /**
     * Get faculty
     *
     * @return string 
     */
    public function getFaculty()
    {
        return $this->faculty;
    }

    /**
     * Set speciality
     *
     * @param string $speciality
     * @return UserHighEducation
     */
    public function setSpeciality($speciality)
    {
        $this->speciality = $speciality;

        return $this;
    }

    /**
     * Get speciality
     *
     * @return string 
     */
    public function getSpeciality()
    {
        return $this->speciality;
    }

    /**
     * Set degree
     *
     * @param string $degree
     * @return UserHighEducation
     */
    public function setDegree($degree)
    {
        $this->degree = $degree;

        return $this;
    }

    /**
     * Get degree
     *
     * @return string 
     */
    public function getDegree()
    {
        return $this->degree;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return UserHighEducation
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
     * @return UserHighEducation
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
     * Set userId
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\Users $userId
     * @return UserHighEducation
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

    /**
     * Set user
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\Users $user
     * @return UserHighEducation
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
