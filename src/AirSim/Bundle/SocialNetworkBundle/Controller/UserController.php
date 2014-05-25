<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

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

        //$userId = $this->session->get('userSessionData')['userInfo']['userId'];
        /* User Info Block */
        $userData =  UserModule::getUserData($this->entityManager, $username);
        $userHighEducation = UserModule::getUserHighEducation($this->entityManager, $username);
        $userWorkplaces = UserModule::getUserWorkplaces($this->entityManager, $username);

        /* Contacts Blocks */
        $userLastContacts = UserModule::getUserFriends($this->entityManager, $username, 7, 0, 'dateAdded', 'DESC');
        $contacts = UserModule::getUserFriends($this->entityManager, $username, 9);

        /* Last Photos Block */
        $userLastPhotos = PhotosModule::getLastPhotos($this->entityManager, $userData[0]);

        /* Wall Block */
        $whoId = $this->session->get('userSessionData')['userInfo']['userId'];
        $userWallRecords = UserWallModule::getUserWallRecords($this->entityManager, $userData[0], $whoId);

        return $this->render('AirSimSocialNetworkBundle:Default:user.html.twig',
            array('userData' => $userData, 'userHighEducation' => $userHighEducation, 'userWorkplaces' => $userWorkplaces,
                'userLastContacts' => $userLastContacts, 'userLastPhotos' => $userLastPhotos, 'contacts' => $contacts,
                'userWallRecords' => $userWallRecords));

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

        $photoData = array
        (
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
            'nextPhotoId' => $nextPhotoId
        );

        $success = true;
        $response = array('success' => $success, 'error' => $error, 'data' => array("photoData" => $photoData));
        return new Response(json_encode($response));
    }

    public function addWallRecordAction()
    {
        $success = false;
        $error = '';
        $validFormats = array("jpg", "png", "gif", "bmp","jpeg");

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $userId = $request->get('userId');
        $page = $request->get('page');
        $event = $request->get('event');
        $text = $request->get('text');
        $userData= $session->get('userSessionData');
        $authorPic = $userData['userInfo']['userWebPic'];
        $username = $userData['userInfo']['username'];
        $authorPicPath = './../../public_files/'.$username.'/albums/profile_pics/'.$authorPic;
        $authorName = $userData['userInfo']['userFirstName'];
        $authorFamily = $userData['userInfo']['userLastName'];
        $recordDate = new \DateTime();
        $recordDateFormatted = $recordDate->format('d.m.Y');

        $entityManager = $this->getDoctrine()->getManager();

        $wallRecordEntity = new UserWallRecords();
        $wallRecordEntity->setToId($userId);
        $wallRecordEntity->setAuthorId($userData['userInfo']['userId']);
        $wallRecordEntity->setRecordText($text);
        $wallRecordEntity->setDateAdded(new \DateTime());

        $entityManager->persist($wallRecordEntity);
        $entityManager->flush();
        $newWallRecordId = $wallRecordEntity->getWallRecId();

        /* ***** Wall Pictures ***** */
        $username = UserModule::getUsernameById($entityManager, $userId);
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

                    $albumId = AlbumModule::getWallPicsAlbumId($entityManager, $userId);
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
                        'fileName' => $fileName,
                        'fileId' => $pictureId
                    );
                }
            }
        }
        /* ***** */

        $data = array
        (
            'text' => $text,
            'addedPics' => $addedPictures,
            'toUsername' => $username,
            'authorPic' => $authorPicPath,
            'authorName' => $authorName,
            'authorFamily' => $authorFamily,
            'recordDate' => $recordDateFormatted,
            'newWallRecordId' => $newWallRecordId
        );

        /*if($userId != $userData['userInfo']['userId'])
            $data['toId'] = $userId;*/

        $response = array
        (
            'success' => true,
            'page' => $page,
            'event' => $event,
            'data' => $data
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
        $userId = $session->get('userSessionData')['userInfo']['userId'];
        $page = $request->get('page');
        $event = $request->get('event');
        $wallRecordId = $request->get('wallRecordId');

        $entityManager = $this->getDoctrine()->getManager();
        $wallRecordLikesRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:WallRecordLikes');
        $likeEntity = $wallRecordLikesRepo->findOneBy(array('wallRecId' => $wallRecordId, 'userId' => $userId));
        $flag = '';
        if(sizeof($likeEntity) == 0)
        {
            $likeEntity = new WallRecordLikes();
            $likeEntity->setUserId($userId);
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

        $data = array
        (
            'wallRecordId' => $wallRecordId,
            'flag' => $flag
        );
        $response = array
        (
            'success' => true,
            'page' => $page,
            'event' => $event,
            'data' => $data
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
        $userId = $session->get('userSessionData')['userInfo']['userId'];
        $page = $request->get('page');
        $event = $request->get('event');
        $wallRecordId = $request->get('wallRecordId');

        $entityManager = $this->getDoctrine()->getManager();
        $wallRecordLikesRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:WallRecordLikes');
        $likeEntity = $wallRecordLikesRepo->findOneBy(array('wallRecId' => $wallRecordId, 'userId' => $userId));
        $flag = '';
        if(sizeof($likeEntity) == 0)
        {
            $likeEntity = new WallRecordLikes();
            $likeEntity->setUserId($userId);
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

        $data = array
        (
            'wallRecordId' => $wallRecordId,
            'flag' => $flag
        );
        $response = array
        (
            'success' => true,
            'page' => $page,
            'event' => $event,
            'data' => $data
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
        $event = $request->get('event');
        $wallRecordId = $request->get('wallRecordId');

        $entityManager = $this->getDoctrine()->getManager();
        $wallRecordsRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:UserWallRecords');
        $wallRecordEntity = $wallRecordsRepo->findOneBy(array('wallRecId' => $wallRecordId));
        $entityManager->remove($wallRecordEntity);
        $entityManager->flush();

        $data = array
        (
            'wallRecordId' => $wallRecordId
        );
        $response = array
        (
            'success' => true,
            'page' => $page,
            'event' => $event,
            'data' => $data
        );
        $jsonResponse = json_encode($response);

        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://80.66.252.45:5555");

        $socket->send(json_encode($jsonResponse));

        return new Response($jsonResponse);
    }

}
