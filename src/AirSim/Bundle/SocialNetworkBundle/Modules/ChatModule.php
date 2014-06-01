<?php

namespace AirSim\Bundle\SocialNetworkBundle\Modules;


class ChatModule
{
    public static function getUserChatsIds($entityManager, $userId)
    {
        $chatMemsRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:ChatMembers');
        $chats = $chatMemsRepo->findBy(array('userId' => $userId));
        $chatWith = array();
        foreach($chats as $chat)
        {
            $query = $entityManager->createQueryBuilder();
            $query
                   ->select('chatMembers.userId')
                   ->from('AirSimSocialNetworkBundle:ChatMembers', 'chatMembers')
                   ->innerJoin('AirSimSocialNetworkBundle:ChatMessages', 'chatMessages', 'WITH', 'chatMembers.chatId = chatMessages.chatId')
                   ->where('chatMembers.chatId = :chatId')
                   ->andWhere('chatMembers.userId != :userId')
                   ->setParameter('chatId', $chat->getChatId())
                   ->setParameter('userId', $userId);
            $result = $query->getQuery()->getResult();
            if(sizeof($result) > 0)
                $chatWith[] = $result[0]['userId'];
        }

        $chatsData = array();
        $i = 0;
        foreach($chats as $chat)
        {
            if(isset($chatWith[$i]))
            {
                $chatsData[] = array
                (
                    'chatId' => $chat->getChatId(),
                    'chatWith' => $chatWith[$i]
                );
            }
            $i++;
        }

        return $chatsData;
    }

    public static function getAvailableChats($entityManager, $userId)
    {
        $chatsWith = ChatModule::getUserChatsIds($entityManager, $userId);
        $chats = array();
        foreach($chatsWith as $chat)
        {
            $query = $entityManager->createQueryBuilder();
            $query
                ->select(array('chat.chatId', 'chat.userId', 'chat.messageText', 'chat.dateTimeSent', 'chat.isReaded'))
                ->from('AirSimSocialNetworkBundle:ChatMessages', 'chat')
                ->where('chat.chatId = :chatId')
                ->setParameter('chatId', $chat['chatId'])
                ->orderBy('chat.dateTimeSent', 'DESC');
            $chats[] = $query->getQuery()->setMaxResults(1)->getResult()[0];
        }

        $availableChatsInfo = array();
        $i = 0;

        foreach($chats as $chat)
        {
            $chatWithData = UserModule::getUserDataById($entityManager, $chatsWith[$i]['chatWith'])[0];
            if(strlen($chat['messageText']) > 95)
                $chat['messageText'] = substr($chat['messageText'], 0, 95).'  ...';
            $availableChatsInfo[] = array
            (
                'chatId' => $chat['chatId'],
                'chatMessage' => $chat['messageText'],
                'messageSentTime' => $chat['dateTimeSent']->format('d.m.Y h:i:s'),
                'messageIsRead' => $chat['isReaded'],
                'contactUsername' => $chatWithData->getLogin(),
                'contactName' => $chatWithData->getFirstName(),
                'contactFamily' => $chatWithData->getLastName(),
                'contactWebProfilePic' => $chatWithData->getWebProfilePic()
            );
            $i++;

        }
        uasort($availableChatsInfo, array('AirSim\Bundle\SocialNetworkBundle\Modules\ChatModule', 'sortChatsByDateTime'));

        return $availableChatsInfo;
    }

    public static function getChatMessages($entityManager, $chatId)
    {
        $chatMessagesRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:ChatMessages');
        $chatMessagesEntities = $chatMessagesRepo->findBy(array('chatId' => $chatId), array(), 100, 0);

        return $chatMessagesEntities;
    }

    public static function getChatParticipantId($entityManager, $chatId, $userId)
    {
        $query = $entityManager->createQueryBuilder();
        $query
            ->select('chat.userId')
            ->from('AirSimSocialNetworkBundle:ChatMembers', 'chat')
            ->where('chat.chatId = :chatId')
            ->andWhere('chat.userId != :userId')
            ->setParameter('chatId', $chatId)
            ->setParameter('userId', $userId);
        $participantId = $query->getQuery()->setMaxResults(1)->getResult()[0];

        return $participantId['userId'];
    }

    /* ***** Inner Functions ***** */

    public static function sortChatsByDateTime($a, $b)
    {
        if($a['messageSentTime'] == $b['messageSentTime'])
            return 0;

        return ($a['messageSentTime'] > $b['messageSentTime']) ? -1 : 1;
    }
}