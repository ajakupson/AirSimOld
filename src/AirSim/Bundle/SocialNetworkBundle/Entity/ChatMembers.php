<?php

namespace AirSim\Bundle\SocialNetworkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChatMembers
 */
class ChatMembers
{
    /**
     * @var integer
     */
    private $chatId;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
    private $recId;


    /**
     * Set chatId
     *
     * @param integer $chatId
     * @return ChatMembers
     */
    public function setChatId($chatId)
    {
        $this->chatId = $chatId;

        return $this;
    }

    /**
     * Get chatId
     *
     * @return integer 
     */
    public function getChatId()
    {
        return $this->chatId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return ChatMembers
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
     * Get recId
     *
     * @return integer 
     */
    public function getRecId()
    {
        return $this->recId;
    }
}
