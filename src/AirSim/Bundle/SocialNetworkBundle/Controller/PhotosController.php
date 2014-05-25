<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PhotosController extends Controller
{
    private $pageId;
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

    public function photosAction($albumId)
    {
        $this->init();
        $this->changeLanguage();
        $pages = array();
        $pages[] = $this->layoutId;
        $pages[] = 999;
        $selectedLang = $this->session->get('lang');

        return $this->render('AirSimSocialNetworkBundle:Default:photos.html.twig');
    }

    /* AJAX functions */
}
