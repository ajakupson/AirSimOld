<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use AirSim\Bundle\SocialNetworkBundle\Entity\PhotoComments;
use AirSim\Bundle\SocialNetworkBundle\Entity\UserWallRecords;
use AirSim\Bundle\SocialNetworkBundle\Entity\WallRecordLikes;
use AirSim\Bundle\SocialNetworkBundle\Modules\AlbumModule;
use AirSim\Bundle\SocialNetworkBundle\Modules\UserWallModule;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use AirSim\Bundle\SocialNetworkBundle\Modules\UserModule;
use AirSim\Bundle\SocialNetworkBundle\Modules\PhotosModule;
use AirSim\Bundle\SocialNetworkBundle\Entity\UserPhotos;
use AirSim\Bundle\SocialNetworkBundle\Entity\WallRecordPictures;

use AirSim\Bundle\SocialNetworkBundle\WebSocketServices;

class UserController extends Controller
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
        $this->userModule = new UserModule();
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

    public function userAction($username)
    {
        $this->init();
        $this->changeLanguage();
        $pages = array();
        $pages[] = $this->layoutId;
        $pages[] = 0;
        $selectedLang = $this->session->get('lang');

        $pages[] = 1;
        $translations = $this->get('TranslationModule')->getPageTranslations($this->entityManager, $pages, $selectedLang);

        //$userId = $this->session->get('userSessionData')['userInfo']['userId'];
        /* User Info Block */
        $userData =  UserModule::getUserData($this->entityManager, $username);
        //$userHighEducation = UserModule::getUserHighEducation($this->entityManager, $username);
        //$userWorkplaces = UserModule::getUserWorkplaces($this->entityManager, $username);
        $userHighEducation = $userData->getHighEducation();
        $userWorkplaces = $userData->getWorkplaces();

        /* Contacts Blocks */
        //$userLastContacts = UserModule::getUserFriends($this->entityManager, $username, 7, 0, 'dateAdded', 'DESC');
        //$contacts = UserModule::getUserFriends($this->entityManager, $username, 9);
       // $userLastContacts = $userData->getFriends();
        //$contacts = $userData->getFriends();

        /* Last Photos Block */
        //$userLastPhotos = $userData->getPhotoAlbums()->getAlbumPhotos();


        /* Wall Block */
        $whoId = $this->session->get('userSessionData')['userInfo']['userId'];
        //$userWallRecords = UserWallModule::getUserWallRecords($this->entityManager, $userData[0], $whoId);

        return $this->render('AirSimSocialNetworkBundle:Default:user.html.twig',
            array('translations' => $translations, 'userData' => $userData, 'userHighEducation' => $userHighEducation, 'userWorkplaces' => $userWorkplaces,
                'userLastContacts' => '', 'userLastPhotos' => '', 'contacts' => '',
                'userWallRecords' => ''/*$userWallRecords*/));

        /*return $this->render('AirSimSocialNetworkBundle:Default:user.html.twig',
            array('translations' => $translations, 'userData' => $userData, 'userHighEducation' => $userHighEducation, 'userWorkplaces' => $userWorkplaces,
                'userLastContacts' => $userLastContacts, 'userLastPhotos' => $userLastPhotos, 'contacts' => $contacts,
                'userWallRecords' => ''$userWallRecords));*/

    }

    /* AJAX Calls */
    public function getPhotoDataAction()
    {
        $success = false;
        $error = null;

        $entityManager = $this->getDoctrine()->getManager();
        $request = $this->get('request_stack')->getCurrentRequest();
        $photoId = $request->get('photoId');

        $photoObject = PhotosModule::getPhotoData($entityManager, $photoId);
        $albumId = $photoObject[0]->getAlbumId();

        $previousPhotoId = PhotosModule::albumHasPreviousPhoto($entityManager, $albumId, $photoId);
        $nextPhotoId = PhotosModule::albumHasNextPhoto($entityManager, $albumId, $photoId);

        $photoComments = PhotosModule::getPhotoComments($entityManager, $photoId);

        $photoData = array
        (
            'username' => $photoObject['login'],
            'albumId' => $albumId,
            'albumName' => $photoObject['albumName'],
            'photoId' => $photoId,
            'photoName' => $photoObject[0]->getPhotoName(),
            'photoTitle' => $photoObject[0]->getPhotoTitle(),
            'photoDescription' => $photoObject[0]->getPhotoDescription(),
            'photoDateAdded' => $photoObject[0]->getDateAdded()->format('d.m.Y'),
            'photoIsCover' => $photoObject[0]->getIsCover(),
            'latitude' => $photoObject[0]->getLatitude(),
            'longitude' => $photoObject[0]->getLongitude(),
            'photoAddress' => $photoObject[0]->getAddress(),
            'previousPhotoId' => $previousPhotoId,
            'nextPhotoId' => $nextPhotoId,
            'photoComments' => $photoComments
        );

        $success = true;
        $response = array('success' => $success, 'error' => $error, 'data' => array("photoData" => $photoData));
        return new Response(json_encode($response));
    }

    public function commentPhotoAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $photoId = $request->get('photoId');
        $receiverId = $request->get('receiverId');
        $commentText = $request->get('commentText');
        $senderId = $session->get('userSessionData')['userInfo']['userId'];
        $dateTime = new \DateTime();

        $commentEntity = new PhotoComments();
        $commentEntity->setPhotoId($photoId);
        $commentEntity->setUserId($senderId);
        $commentEntity->setComment($commentText);
        $commentEntity->setDateAdded($dateTime);

        $entityManager->persist($commentEntity);
        $entityManager->flush();

        $commentId = $commentEntity->getCommentId();
        $senderEntity = UserModule::getUserDataById($entityManager, $senderId);

        $senderData = array
        (
            'senderUsername' => $senderEntity[0]->getLogin(),
            'senderName' => $senderEntity[0]->getFirstName(),
            'senderFamily' => $senderEntity[0]->getLastName(),
            'senderWebPic' => $senderEntity[0]->getWebProfilePic()
        );
        $receiverData = array
        (
            'receiverId' => $receiverId
        );
        $eventData = array
        (
            'success' => true,
            'event' => 'photoComment',
            'page' => 'user'.$receiverId,
            'photoId' => $photoId,
            'commentId' => $commentId,
            'messageText' => $commentText,
            'dateTime' => $dateTime->format('d.m.Y h:i:s')
        );
        $response = array
        (
            'senderData' => $senderData,
            'receiverData' => $receiverData,
            'eventData' => $eventData
        );
        $jsonResponse = json_encode($response);

        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://80.66.252.45:5555");
        $socket->send(json_encode($jsonResponse));

        return new Response($jsonResponse);
    }

    public function addWallRecordAction()
    {
        $success = false;
        $error = '';
        $validFormats = array("jpg", "png", "gif", "bmp","jpeg");

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $receiverId = $request->get('receiverId');
        $page = $request->get('page');
        $text = $request->get('text');
        $userData= $session->get('userSessionData');
        $authorPic = $userData['userInfo']['userWebPic'];
        $authorUsername = $userData['userInfo']['username'];
        $authorName = $userData['userInfo']['userFirstName'];
        $authorFamily = $userData['userInfo']['userLastName'];
        $recordDate = new \DateTime();
        $recordDateFormatted = $recordDate->format('d.m.Y');

        $entityManager = $this->getDoctrine()->getManager();

        $wallRecordEntity = new UserWallRecords();
        $wallRecordEntity->setToId($receiverId);
        $wallRecordEntity->setAuthorId($userData['userInfo']['userId']);
        $wallRecordEntity->setRecordText($text);
        $wallRecordEntity->setDateAdded(new \DateTime());

        $entityManager->persist($wallRecordEntity);
        $entityManager->flush();
        $newWallRecordId = $wallRecordEntity->getWallRecId();

        /* ***** Wall Pictures ***** */
        $username = UserModule::getUsernameById($entityManager, $receiverId);
        $uploadDirectory = './../web/public_files/'.$username.'/albums/wall_pics';
        $albumDirectory = '/AirSim/web/public_files/'.$username.'/albums/wall_pics/';

        $pictures = $request->files->get('wallAttachedPictures');
        $addedPictures = array();
        if(sizeof($pictures) > 0)
        {
            foreach($pictures as $picture)
            {
                $ext = $picture->getClientOriginalExtension();
                $fileName = $picture->getClientOriginalName();
                if(in_array($ext, $validFormats))
                {
                    $picture->move($uploadDirectory, $fileName);
                    $filePath = $albumDirectory.$fileName;

                    $albumId = AlbumModule::getWallPicsAlbumId($entityManager, $receiverId);
                    $photoEntity = new UserPhotos();
                    $photoEntity->setAlbumId($albumId);
                    $photoEntity->setPhotoName($fileName);
                    $photoEntity->setIsCover(0);
                    $photoEntity->setDateAdded(new \DateTime());
                    $entityManager->persist($photoEntity);
                    $entityManager->flush();

                    $pictureId = $photoEntity->getPhotoId();

                    $wallRecordPicEntity = new WallRecordPictures();
                    $wallRecordPicEntity->setWallRecId($newWallRecordId);
                    $wallRecordPicEntity->setPictureId($pictureId);
                    $entityManager->persist($wallRecordPicEntity);
                    $entityManager->flush();

                    $addedPictures[] = array
                    (
                        'fileId' => $pictureId,
                        'fileName' => $fileName
                    );
                }
            }
        }

        $senderData = array
        (
            'senderUsername' => $authorUsername,
            'senderName' => $authorName,
            'senderFamily' => $authorFamily,
            'senderWebPic' => $authorPic
        );
        $receiverData = array
        (
            'receiverId' => $receiverId,
            'receiverUsername' => $username,
        );
        $eventData = array
        (
            'success' => true,
            'page' => $page,
            'event' => 'addWallRecord',
            'messageText' => $text,
            'addedPics' => $addedPictures,
            'recordDate' => $recordDateFormatted,
            'newWallRecordId' => $newWallRecordId
        );
        $response = array
        (
            'senderData' => $senderData,
            'receiverData' => $receiverData,
            'eventData' => $eventData
        );
        $jsonResponse = json_encode($response);

        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://80.66.252.45:5555");
        $socket->send(json_encode($jsonResponse));

        return new Response($jsonResponse);
    }

    public function likeWallRecordAction()
    {
        $success = false;
        $error = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();
        $senderId = $session->get('userSessionData')['userInfo']['userId'];
        $page = $request->get('page');
        $wallRecordId = $request->get('wallRecordId');

        $entityManager = $this->getDoctrine()->getManager();
        $wallRecordLikesRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:WallRecordLikes');
        $likeEntity = $wallRecordLikesRepo->findOneBy(array('wallRecId' => $wallRecordId, 'userId' => $senderId));
        $flag = '';
        if(sizeof($likeEntity) == 0)
        {
            $likeEntity = new WallRecordLikes();
            $likeEntity->setUserId($senderId);
            $likeEntity->setWallRecId($wallRecordId);
            $likeEntity->setLikeDislike(1);
            $likeEntity->setDateRated(new \DateTime());

            $flag = 'new';
        }
        else
        {
            $likeEntity->setLikeDislike(1);

            $flag = 'change';
        }
        $entityManager->persist($likeEntity);
        $entityManager->flush();

        $senderData = array
        (
            'senderUsername' => $session->get('userSessionData')['userInfo']['username'],
            'senderName' => $session->get('userSessionData')['userInfo']['userFirstName'],
            'senderFamily' => $session->get('userSessionData')['userInfo']['userLastName'],
            'senderWebPic' => $session->get('userSessionData')['userInfo']['userWebPic']
        );
        $receiverData = array
        (
            'receiverId' => $request->get('receiverId')
        );
        $eventData = array
        (
            'success' => true,
            'page' => $page,
            'event' => 'likeWallRecord',
            'messageText' => '',
            'wallRecordId' => $wallRecordId,
            'flag' => $flag
        );
        $response = array
        (
            'senderData' => $senderData,
            'receiverData' => $receiverData,
            'eventData' => $eventData
        );
        $jsonResponse = json_encode($response);

        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://80.66.252.45:5555");
        $socket->send(json_encode($jsonResponse));

        return new Response($jsonResponse);
    }

    public function dislikeWallRecordAction()
    {
        $success = false;
        $error = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();
        $senderId = $session->get('userSessionData')['userInfo']['userId'];
        $page = $request->get('page');
        $wallRecordId = $request->get('wallRecordId');

        $entityManager = $this->getDoctrine()->getManager();
        $wallRecordLikesRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:WallRecordLikes');
        $likeEntity = $wallRecordLikesRepo->findOneBy(array('wallRecId' => $wallRecordId, 'userId' => $senderId));
        $flag = '';
        if(sizeof($likeEntity) == 0)
        {
            $likeEntity = new WallRecordLikes();
            $likeEntity->setUserId($senderId);
            $likeEntity->setWallRecId($wallRecordId);
            $likeEntity->setLikeDislike(0);
            $likeEntity->setDateRated(new \DateTime());

            $flag = 'new';
        }
        else
        {
            $likeEntity->setLikeDislike(0);

            $flag = 'change';
        }
        $entityManager->persist($likeEntity);
        $entityManager->flush();

        $senderData = array
        (
            'senderUsername' => $session->get('userSessionData')['userInfo']['username'],
            'senderName' => $session->get('userSessionData')['userInfo']['userFirstName'],
            'senderFamily' => $session->get('userSessionData')['userInfo']['userLastName'],
            'senderWebPic' => $session->get('userSessionData')['userInfo']['userWebPic']
        );
        $receiverData = array
        (
            'receiverId' => $request->get('receiverId')
        );
        $eventData = array
        (
            'success' => true,
            'page' => $page,
            'event' => 'dislikeWallRecord',
            'messageText' => '',
            'wallRecordId' => $wallRecordId,
            'flag' => $flag
        );
        $response = array
        (
            'senderData' => $senderData,
            'receiverData' => $receiverData,
            'eventData' => $eventData
        );
        $jsonResponse = json_encode($response);

        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://80.66.252.45:5555");
        $socket->send(json_encode($jsonResponse));

        return new Response($jsonResponse);
    }

    public function deleteWallRecordAction()
    {
        $success = false;
        $error = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();
        $page = $request->get('page');
        $wallRecordId = $request->get('wallRecordId');

        $entityManager = $this->getDoctrine()->getManager();
        $wallRecordsRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:UserWallRecords');
        $wallRecordEntity = $wallRecordsRepo->findOneBy(array('wallRecId' => $wallRecordId));
        $entityManager->remove($wallRecordEntity);
        $entityManager->flush();

        $senderData = array
        (
            'senderUsername' => $session->get('userSessionData')['userInfo']['username'],
            'senderName' => $session->get('userSessionData')['userInfo']['userFirstName'],
            'senderFamily' => $session->get('userSessionData')['userInfo']['userLastName'],
            'senderWebPic' => $session->get('userSessionData')['userInfo']['userWebPic']
        );
        $receiverData = array
        (
            'receiverId' => ''
        );
        $eventData = array
        (
            'success' => true,
            'page' => $page,
            'event' => 'deleteWallRecord',
            'messageText' => '',
            'wallRecordId' => $wallRecordId
        );
        $response = array
        (
            'senderData' => $senderData,
            'receiverData' => $receiverData,
            'eventData' => $eventData
        );
        $jsonResponse = json_encode($response);

        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://80.66.252.45:5555");
        $socket->send(json_encode($jsonResponse));

        return new Response($jsonResponse);
    }

}
