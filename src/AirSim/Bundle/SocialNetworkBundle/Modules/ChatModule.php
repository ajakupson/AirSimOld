<?php

namespace AirSim\Bundle\SocialNetworkBundle\Modules;


class ChatModule
{
    public static function getUserChatsIds($entityManager, $userId)
    {
        $chatMemsRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:ChatMembers');
        $chats = $chatMemsRepo->findBy(array('userId' => $userId));

        $chatsIds = array();
        foreach($chats as $chat)
        {
            $chatsIds[] = $chat->getChatId();
        }

        return $chatsIds;
    }

    public static function getAvailableChats($entityManager, $userId)
    {
        /*$query
            ->select('DISTINCT chat.chatId, chat.userId, chat.messageText, chat.dateTimeSent, chat.isReaded')
            ->from('AirSimSocialNetworkBundle:ChatMessages', 'chat')
            ->innerJoin('AirSimSocialNetworkBundle:ChatMembers', 'chatMembers', 'WITH', 'chat.userId = chatMembers.userId')
            ->where('chat.chatId = chatMembers.chatId')
            ->andWhere('chatMembers.userId = :userId')
            ->setParameter('userId', $userId);
        $chats = $query->getQuery()->getResult();*/
        $chatsIds = ChatModule::getUserChatsIds($entityManager, $userId);
        $chats = array();
        foreach($chatsIds as $chatId)
        {
            $query = $entityManager->createQueryBuilder();
            $query
                ->select(array('chat.chatId', 'chat.userId', 'chat.messageText', 'chat.dateTimeSent', 'chat.isReaded'))
                ->from('AirSimSocialNetworkBundle:ChatMessages', 'chat')
                ->where('chat.chatId = :chatId')
                ->setParameter('chatId', $chatId)
                ->orderBy('chat.dateTimeSent', 'DESC');
            $chats[] = $query->getQuery()->getResult()[0];
        }

        $availableChatsInfo = array();
        foreach($chats as $chat)
        {
            $lastMessageAuthor = UserModule::getUserDataById($entityManager, $chat['userId'])[0];
            $availableChatsInfo[] = array
            (
                'chatId' => $chat['chatId'],
                'chatMessage' => $chat['messageText'],
                'messageSentTime' => $chat['dateTimeSent']->format('d.m.Y h:i:s'),
                'messageIsReaded' => $chat['isReaded'],
                'messageAuthorUsername' => $lastMessageAuthor->getLogin(),
                'messageAuthorName' => $lastMessageAuthor->getFirstName(),
                'messageAuthorFamily' => $lastMessageAuthor->getLastName(),
                'messageAuthorWebProfilePic' => $lastMessageAuthor->getWebProfilePic()
            );

        }

        return $availableChatsInfo;
    }
}