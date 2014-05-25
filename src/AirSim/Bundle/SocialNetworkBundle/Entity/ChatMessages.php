<?php

namespace AirSim\Bundle\SocialNetworkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChatMessages
 */
class ChatMessages
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
     * @var string
     */
    private $messageText;

    /**
     * @var \DateTime
     */
    private $dateTimeSent;

    /**
     * @var boolean
     */
    private $isReaded;

    /**
     * @var integer
     */
    private $messageId;


    /**
     * Set chatId
     *
     * @param integer $chatId
     * @return ChatMessages
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
     * @return ChatMessages
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
     * Set messageText
     *
     * @param string $messageText
     * @return ChatMessages
     */
    public function setMessageText($messageText)
    {
        $this->messageText = $messageText;

        return $this;
    }

    /**
     * Get messageText
     *
     * @return string 
     */
    public function getMessageText()
    {
        return $this->messageText;
    }

    /**
     * Set dateTimeSent
     *
     * @param \DateTime $dateTimeSent
     * @return ChatMessages
     */
    public function setDateTimeSent($dateTimeSent)
    {
        $this->dateTimeSent = $dateTimeSent;

        return $this;
    }

    /**
     * Get dateTimeSent
     *
     * @return \DateTime 
     */
    public function getDateTimeSent()
    {
        return $this->dateTimeSent;
    }

    /**
     * Set isReaded
     *
     * @param boolean $isReaded
     * @return ChatMessages
     */
    public function setIsReaded($isReaded)
    {
        $this->isReaded = $isReaded;

        return $this;
    }

    /**
     * Get isReaded
     *
     * @return boolean 
     */
    public function getIsReaded()
    {
        return $this->isReaded;
    }

    /**
     * Get messageId
     *
     * @return integer 
     */
    public function getMessageId()
    {
        return $this->messageId;
    }
}
