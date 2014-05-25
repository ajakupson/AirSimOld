<?php

namespace AirSim\Bundle\SocialNetworkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserHighEducation
 */
class UserHighEducation
{
    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     */
    private $university;

    /**
     * @var string
     */
    private $faculty;

    /**
     * @var string
     */
    private $speciality;

    /**
     * @var string
     */
    private $degree;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var integer
     */
    private $recId;


    /**
     * Set userId
     *
     * @param integer $userId
     * @return UserHighEducation
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
     * Get recId
     *
     * @return integer 
     */
    public function getRecId()
    {
        return $this->recId;
    }
}
