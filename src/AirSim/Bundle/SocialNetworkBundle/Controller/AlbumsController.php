<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AirSim\Bundle\SocialNetworkBundle\Modules\PhotosModule;

class AlbumsController extends Controller
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

    public function albumsAction($username)
    {
        $this->init();
        $this->changeLanguage();
        $pages = array();
        $pages[] = $this->layoutId;
        $pages[] = 999;
        $selectedLang = $this->session->get('lang');

        $userId = $this->session->get('userSessionData')['userInfo']['userId'];
        $albumsAndPhotos = PhotosModule::collectAlbumsAndPhotosData($this->entityManager, $userId);

        return $this->render('AirSimSocialNetworkBundle:Default:albums.html.twig', array('albumsAndPhotos' => $albumsAndPhotos));
    }



    /* AJAX functions */
}
