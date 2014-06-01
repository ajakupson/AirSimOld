<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use AirSim\Bundle\SocialNetworkBundle\Modules\UserModule;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use AirSim\Bundle\SocialNetworkBundle\Modules\ChatModule;
use AirSim\Bundle\SocialNetworkBundle\Entity\ChatMessages;

class ChatController extends Controller
{
    private $layoutId;
    private $session;
    private $entityManager;

    private function init()
    {
        $this->layoutId = -1;
        $this->session = $this->getRequest()->getSession();
        if($this->session->get('lang') == null)
        {
            $this->session->set('lang', 'eng');
        }

        $this->entityManager = $this->getDoctrine()->getManager();
    }

    private function changeLanguage()
    {
        $request = $this->getRequest();
        if($request->getMethod() == 'POST')
        {
            if($request->get('lang') != null)
            {
                $lang = $request->get('lang');
                $this->session->set('lang', $lang);
            }
        }
    }

    public function chatAction($location)
    {
        $this->init();
        $this->changeLanguage();
        $pages = array();
        $pages[] = $this->layoutId;
        $pages[] = 999;
        $selectedLang = $this->session->get('lang');

        $userId = $this->session->get('userSessionData')['userInfo']['userId'];
        $availableChats = ChatModule::getAvailableChats($this->entityManager, $userId);

        switch($location)
        {
            case 'available_chats':
            {
                $pages[] = 1;
                $translations = $this->get('TranslationModule')->getPageTranslations($this->entityManager, $pages, $selectedLang);

                return $this->render('AirSimSocialNetworkBundle:Default:availableChats.html.twig',
                    array('translations' => $translations, 'availableChats' => $availableChats));
            }break;
            case 'chat_room':
            {
                $translations = $this->get('TranslationModule')->getPageTranslations($this->entityManager, $pages, $selectedLang);

                $request = $this->get('request_stack')->getCurrentRequest();
                $roomId = $request->query->get('id');
                $chatMembersRoomsRepo = $this->entityManager->getRepository('AirSimSocialNetworkBundle:ChatMembers');
                $chatIsYour = $chatMembersRoomsRepo->findOneBy(array('chatId' => $roomId, 'userId' => $userId));
                if(sizeof($chatIsYour) > 0)
                {
                    $chatMessages = ChatModule::getChatMessages($this->entityManager, $roomId);
                    $participantId = ChatModule::getChatParticipantId($this->entityManager, $roomId, $userId);
                    $chatUsers = array();
                    $chatUsers[$participantId] = UserModule::getUserDataById($this->entityManager, $participantId)[0];

                    return $this->render('AirSimSocialNetworkBundle:Default:chatRoom.html.twig',
                        array('translations' => $translations, 'chatMessages' => $chatMessages, 'chatUsers' => $chatUsers,
                            'participantId' => $participantId, 'availableChats' => $availableChats));

                }
                else break;

            }break;
            default:
                return $this->render('AirSimSocialNetworkBundle:Default:notFound.html.twig');
                break;
        }
    }



    /* AJAX functions */
    public function sendMessageAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $chatId = $request->get('chatId');
        $toId = $request->get('toId');
        $messageText = $request->get('messageText');
        $userId = $session->get('userSessionData')['userInfo']['userId'];
        $dateTime = new \DateTime();

        $messageEntity = new ChatMessages();
        $messageEntity->setChatId($chatId);
        $messageEntity->setUserId($userId);
        $messageEntity->setMessageText($messageText);
        $messageEntity->setDateTimeSent($dateTime);
        $messageEntity->setIsReaded(0);

        $entityManager->persist($messageEntity);
        $entityManager->flush();

        $messageId = $messageEntity->getMessageId();

        $eventData = array
        (
            'event' => 'sendMessage',
            'page' => 'chat'.$chatId,
            'chatId' => $chatId,
            'messageId' => $messageId,
            'messageText' => $messageText,
            'dateTime' => $dateTime->format('d.m.Y h:i:s'),
            'userId' => $userId,
            'toId' => $toId
        );
        $senderEntity = UserModule::getUserDataById($entityManager, $userId);
        $senderData = array
        (
            'senderUsername' => $senderEntity[0]->getLogin(),
            'senderName' => $senderEntity[0]->getFirstName(),
            'senderFamily' => $senderEntity[0]->getLastName(),
            'senderWebPic' => $senderEntity[0]->getWebProfilePic()
        );
        $response = array
        (
            'eventData' => $eventData,
            'senderData' => $senderData
        );

        $jsonResponse = json_encode($response);

        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://80.66.252.45:5555");

        $socket->send(json_encode($jsonResponse));

        return new Response($jsonResponse);

    }

    public function readMessageAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $messagesRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:ChatMessages');
        $request = $this->get('request_stack')->getCurrentRequest();
        $chatId = $request->get('chatId');
        $messageId = $request->get('messageId');

        $messageEntity = $messagesRepo->findOneBy(array('messageId' => $messageId));
        $messageEntity->setIsReaded(true);
        $entityManager->persist($messageEntity);
        $entityManager->flush();

        $eventData = array
        (
            'event' => 'readMessage',
            'page' => 'chat'.$chatId,
            'messageId' => $messageId,
            'toId' => null
        );
        $senderData = array();
        $response = array
        (
            'eventData' => $eventData,
            'senderData' => $senderData
        );

        $jsonResponse = json_encode($response);

        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://80.66.252.45:5555");

        $socket->send(json_encode($jsonResponse));

        return new Response($jsonResponse);
    }

    public function deleteMessageAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $messagesRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:ChatMessages');
        $request = $this->get('request_stack')->getCurrentRequest();
        $chatId = $request->get('chatId');
        $messageId = $request->get('messageId');

        $messageEntity = $messagesRepo->findOneBy(array('messageId' => $messageId));
        $entityManager->remove($messageEntity);
        $entityManager->flush();

        $eventData = array
        (
            'event' => 'deleteMessage',
            'page' => 'chat'.$chatId,
            'messageId' => $messageId,
            'toId' => null
        );
        $senderData = array();
        $response = array
        (
            'eventData' => $eventData,
            'senderData' => $senderData
        );

        $jsonResponse = json_encode($response);

        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://80.66.252.45:5555");

        $socket->send(json_encode($jsonResponse));

        return new Response($jsonResponse);
    }
}
