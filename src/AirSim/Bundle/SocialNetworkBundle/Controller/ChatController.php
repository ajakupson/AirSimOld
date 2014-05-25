<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use AirSim\Bundle\SocialNetworkBundle\Modules\ChatModule;

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
                return $this->render('AirSimSocialNetworkBundle:Default:chatRoom.html.twig');
                break;
            default:
                return $this->render('AirSimSocialNetworkBundle:Default:notFound.html.twig');
                break;
        }
    }



    /* AJAX functions */
}
