<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use AirSim\Bundle\SocialNetworkBundle\Modules\UserModule;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class ContactsController extends Controller
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

    public function contactsAction($action)
    {
        $this->init();
        $this->changeLanguage();
        $pages = array();
        $pages[] = $this->layoutId;
        $pages[] = 0;
        $selectedLang = $this->session->get('lang');

        switch($action)
        {
            case 'all_friends':
            {
                $pages[] = 1;
                $translations = $this->get('TranslationModule')->getPageTranslations($this->entityManager, $pages, $selectedLang);

                $username = $this->session->get('userSessionData')['userInfo']['username'];
                $userFriends = UserModule::getUserFriends($this->entityManager, $username, 10, 0);

                return $this->render('AirSimSocialNetworkBundle:Default:friends.html.twig',
                    array('translations' => $translations, 'userFriends' => $userFriends));
            }break;
            case 'add_friend':
                return $this->render('AirSimSocialNetworkBundle:Default:addFriend.html.twig');
                break;
            case 'contact_updates':
                return $this->render('AirSimSocialNetworkBundle:Default:contactsUpdates.html.twig');
                break;
            default:
                return $this->render('AirSimSocialNetworkBundle:Default:notFound.html.twig');
                break;
        }
    }

    /* AJAX functions */
    public function searchFriendAction()
    {
        $success = false;
        $error = '';

        $entityManager = $this->getDoctrine()->getManager();
        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();
        $userId = $session->get('userSessionData')['userInfo']['userId'];
        $searchText = $request->get('searchText');
        $userFriends = UserModule::searchUserFriends($entityManager, $userId, $searchText);

        /*$encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $data = $serializer->serialize($userFriends, 'json');*/

        $data = array();
        foreach($userFriends as $friend)
        {
            $data[] = array
            (
                'firstName' => $friend->getFirstName(),
                'lastName' => $friend->getLastName(),
                'username' => $friend->getLogin(),
                'webProfilePic' => $friend->getWebProfilePic()
            );
        }

        $success = true;
        $response = array('success' => $success, 'error' => $error, 'data' => array('contacts' => $data));
        return new Response(json_encode($response));
    }

    public function searchContactsByCriteriasAction()
    {
        $success = false;
        $error = '';

        $entityManager = $this->getDoctrine()->getManager();
        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();
        $userId = $session->get('userSessionData')['userInfo']['userId'];

        $nameFamily = $request->get('nameFamily');
        $gender = $request->get('gender');
        $phoneNumber = $request->get('phoneNumber');
        $country = $request->get('country');
        $city = $request->get('city');
        $ageFrom = $request->get('ageFrom');
        $ageTo = $request->get('ageTo');
        $offset = $request->get('offset');
        $criterias = array
        (
            'nameFamily' => $nameFamily,
            'gender' => $gender,
            'phoneNumber' => $phoneNumber,
            'country' => $country,
            'city' => $city,
            'ageFrom' => $ageFrom,
            'ageTo' => $ageTo,
            'userId' => $userId
        );

        $foundContacts = UserModule::searchContactsByCriterias($entityManager, $criterias, $offset);

        /*$encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $data = $serializer->serialize($userFriends, 'json');*/

        $data = array();
        foreach($foundContacts as $contact)
        {
            $data[] = array
            (
                'firstName' => $contact->getFirstName(),
                'lastName' => $contact->getLastName(),
                'username' => $contact->getLogin(),
                'webProfilePic' => $contact->getWebProfilePic()
            );
        }

        $success = true;
        $response = array('success' => $success, 'error' => $error, 'data' => array('contacts' => $data));
        return new Response(json_encode($response));
    }
}
