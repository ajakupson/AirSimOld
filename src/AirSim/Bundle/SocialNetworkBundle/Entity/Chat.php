<?php

namespace AirSim\Bundle\SocialNetworkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chat
 *
 * @ORM\Table(name="chat")
 * @ORM\Entity
 */
class Chat
{
    /**
     * @var integer
     *
     * @ORM\Column(name="chat_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $chatId;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=750, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_time_created", type="datetime", nullable=false)
     */
    private $dateTimeCreated;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ChatMembers", mappedBy="user")
     */
    private $chatMembers;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ChatMessages", mappedBy="user")
     */
    private $chatMessages;

    public function __construct()
    {
        $this->chatMembers = new ArrayCollection();
        $this->chatMessages = new ArrayCollection();
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
     * Set description
     *
     * @param string $description
     * @return Chat
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateTimeCreated
     *
     * @param \DateTime $dateTimeCreated
     * @return Chat
     */
    public function setDateTimeCreated($dateTimeCreated)
    {
        $this->dateTimeCreated = $dateTimeCreated;

        return $this;
    }

    /**
     * Get dateTimeCreated
     *
     * @return \DateTime 
     */
    public function getDateTimeCreated()
    {
        return $this->dateTimeCreated;
    }

    /**
     * Add chatMembers
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\ChatMembers $chatMembers
     * @return Chat
     */
    public function addChatMember(\AirSim\Bundle\SocialNetworkBundle\Entity\ChatMembers $chatMembers)
    {
        $this->chatMembers[] = $chatMembers;

        return $this;
    }

    /**
     * Remove chatMembers
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\ChatMembers $chatMembers
     */
    public function removeChatMember(\AirSim\Bundle\SocialNetworkBundle\Entity\ChatMembers $chatMembers)
    {
        $this->chatMembers->removeElement($chatMembers);
    }

    /**
     * Get chatMembers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChatMembers()
    {
        return $this->chatMembers;
    }

    /**
     * Add chatMessages
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\ChatMessages $chatMessages
     * @return Chat
     */
    public function addChatMessage(\AirSim\Bundle\SocialNetworkBundle\Entity\ChatMessages $chatMessages)
    {
        $this->chatMessages[] = $chatMessages;

        return $this;
    }

    /**
     * Remove chatMessages
     *
     * @param \AirSim\Bundle\SocialNetworkBundle\Entity\ChatMessages $chatMessages
     */
    public function removeChatMessage(\AirSim\Bundle\SocialNetworkBundle\Entity\ChatMessages $chatMessages)
    {
        $this->chatMessages->removeElement($chatMessages);
    }

    /**
     * Get chatMessages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChatMessages()
    {
        return $this->chatMessages;
    }
}
