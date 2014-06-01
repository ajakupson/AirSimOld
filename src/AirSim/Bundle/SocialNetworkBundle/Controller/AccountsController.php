<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AccountsController extends Controller
{
    private $pageId;
    private $session;
    private $entityManager;

    private function init()
    {
        $this->pageId = -1;
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

    public function accountsAction($action)
    {
        $this->init();
        $this->changeLanguage();
        $pages = array();
        $pages[] = $this->pageId;
        $pages[] = 0;
        $selectedLang = $this->session->get('lang');

        switch($action)
        {
            case 'login':
            {
                $pages[] = 1;
                $translations = $this->get('TranslationModule')->getPageTranslations($this->entityManager, $pages, $selectedLang);
                $news = $this->getLatestNews();

                return $this->render('AirSimSocialNetworkBundle:Default:login.html.twig',
                    array('translations' => $translations, 'news' => $news));
            }break;
            case 'register':
                return $this->render('AirSimSocialNetworkBundle:Default:register.html.twig');
                break;
            case 'restore':
                return $this->render('AirSimSocialNetworkBundle:Default:restore.html.twig');
                break;
            default:
                return $this->render('AirSimSocialNetworkBundle:Default:notFound.html.twig');
                break;
        }
    }

    private function getLatestNews()
    {
        $lang = $this->session->get('lang');

        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select('news')
            ->from('AirSimSocialNetworkBundle:SiteNews', 'news')
            ->where('news.langId = :lang')
            ->setParameter('lang', $lang)
            ->orderBy('news.dateAdded', 'DESC')
            ->setMaxResults(3);
        $news = $query->getQuery()->getResult();

        return $news;
    }

    /* AJAX functions */
    public function userLoginAction()
    {
        $success = false;
        $error = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $username = $request->get('username');
        $password = $request->get('password');
        $entityManager = $this->getDoctrine()->getManager();
        $usersRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:Users');

        $userEntity = $usersRepo->findOneBy(array('login' => $username, 'password' => md5($password)));
        if(sizeof($userEntity) == 0)
        {
            $success = false;
            $error = 'noInDatabase';
            $response = array('success' => $success, 'error' => $error);
            return new Response(json_encode($response));
        }

        $success = true;
        $session = $this->getRequest()->getSession();
        $userId = $userEntity->getUserId();
        $userInfo = array
        (
            'userId' => $userId,
            'username' => $username,
            'userFirstName' => $userEntity->getFirstName(),
            'userLastName' => $userEntity->getLastName(),
            'userWebPic' => $userEntity->getWebProfilePic()
        );
        $configEntity = $userEntity->getConfig();
        $userConfig = array
        (
            'phoneVisibility' => $configEntity->getPrivPhoneVisibility(),
            'searchByPhone' => $configEntity->getPrivSearchByPhone(),
            'addInfoVisibility' => $configEntity->getPrivAddInfoVisibility(),
            'friendsVisibility' => $configEntity->getPrivFriendsVisibility(),
            'whoAllowedWrite' => $configEntity->getPrivWhoAllowedWrite(),
            'autoSync' => $configEntity->getSyncAutoSync()
        );
        $session->set('userSessionData', array('userInfo' => $userInfo, 'userConfig' => $userConfig));

        $baseUrl = $this->container->get('router')->getContext()->getBaseUrl();
        $userUrl = $baseUrl.'/user/'.$username;

        $response = array('success' => $success, 'error' => $error, 'data' => array('userURL' => $userUrl));
        return new Response(json_encode($response));
    }
}
