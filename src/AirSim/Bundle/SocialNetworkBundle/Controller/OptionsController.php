<?php

namespace AirSim\Bundle\SocialNetworkBundle\Controller;

use AirSim\Bundle\SocialNetworkBundle\Entity\UserHighEducation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AirSim\Bundle\SocialNetworkBundle\Modules\UserModule;
use Symfony\Component\HttpFoundation\Response;

class OptionsController extends Controller
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

    public function optionsAction($type)
    {
        $this->init();
        $this->changeLanguage();
        $pages = array();
        $pages[] = $this->layoutId;
        $pages[] = 0;
        $selectedLang = $this->session->get('lang');

        switch($type)
        {
            case 'private':
            {
                $pages[] = 999;
                $translations = $this->get('TranslationModule')->getPageTranslations($this->entityManager, $pages, $selectedLang);

                $config = $this->getUserConfig();

                return $this->render('AirSimSocialNetworkBundle:Default:private.html.twig',
                    array('translations' => $translations, 'config' => $config));
            }break;
            case 'synchronization':
            {
                $config = $this->getUserConfig();

                return $this->render('AirSimSocialNetworkBundle:Default:synchronization.html.twig',
                    array('config' => $config));
            }break;
            case 'safety':
                return $this->render('AirSimSocialNetworkBundle:Default:safety.html.twig');
                break;
            case 'personal':
            {
                $username = $this->session->get('userSessionData')['userInfo']['username'];
                $userData = UserModule::getUserData($this->entityManager, $username);
                $userHighEducation = UserModule::getUserHighEducation($this->entityManager, $username);
                $userWorkplaces = UserModule::getUserWorkplaces($this->entityManager, $username);

                return $this->render('AirSimSocialNetworkBundle:Default:personal.html.twig',
                    array('userData' => $userData, 'userHighEducation' => $userHighEducation, 'userWorkplaces' => $userWorkplaces));
            }break;
            default:
                return $this->render('AirSimSocialNetworkBundle:Default:notFound.html.twig');
                break;
        }
    }

    private function getUserConfig()
    {
        $configsRepo = $this->entityManager->getRepository('AirSimSocialNetworkBundle:UserConfig');
        $userId = $this->session->get('userSessionData')['userInfo']['userId'];
        $configEntity = $configsRepo->findOneBy(array('userId' => $userId));

        return $configEntity;
    }

    /* AJAX */
    public function configChangeAction()
    {
        $success = false;
        $error = '';

        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();
        $connection = $this->getDoctrine()->getConnection();

        $userId = $session->get('userSessionData')['userInfo']['userId'];
        $fieldName = $request->get('name');
        $valueToBeSet = $request->get('value');

        $sql = 'UPDATE user_config SET '.$fieldName.' = '.$valueToBeSet.' WHERE user_id = '.$userId;
        $statement = $connection->prepare($sql);
        try
        {
            $statement->execute();
            $success = true;
        }
        catch(DBALException $e)
        {
            $success = false;
            $error = $e->getMessage();
        }

        $response = array('success' => $success, 'error' => $error);
        return new Response(json_encode($response));
    }

    public function updateMainInfoAction()
    {
        $success = false;
        $error = '';

        $entityManager = $this->getDoctrine()->getManager();
        $usersRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:Users');
        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $userId = $session->get('userSessionData')['userInfo']['userId'];
        $userName = $request->get('userName');
        $userFamily = $request->get('userFamily');
        $userBirthdate = $request->get('userBirthdate');
        $userGender = $request->get('userGender');
        $phoneOperator = $request->get('phoneOperator');

        $userEntity = $usersRepo->findOneBy(array('userId' => $userId));
        $userEntity->setFirstName($userName);
        $userEntity->setLastName($userFamily);
        if(strlen($userBirthdate) > 0)
            $userEntity->setBirthdate(new \DateTime(str_replace('.', '', $userBirthdate)));
        $userEntity->setGender($userGender);
        $userEntity->setOperator($phoneOperator);

        $entityManager->persist($userEntity);
        $entityManager->flush();

        $success = true;
        $response = array('success' => $success, 'error' => $error);
        return new Response(json_encode($response));
    }

    public function updateAdditionalInfoAction()
    {
        $success = false;
        $error = '';

        $entityManager = $this->getDoctrine()->getManager();
        $usersRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:Users');
        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $userId = $session->get('userSessionData')['userInfo']['userId'];
        $email = $request->get('email');
        $country = $request->get('country');
        $city = $request->get('city');
        $address = $request->get('address');
        $website = $request->get('website');

        $userEntity = $usersRepo->findOneBy(array('userId' => $userId));
        $userEntity->setEmail($email);
        $userEntity->setCountry($country);
        $userEntity->setCity($city);
        $userEntity->setAddress($address);
        $userEntity->setWebsite($website);

        $entityManager->persist($userEntity);
        $entityManager->flush();

        $success = true;
        $response = array('success' => $success, 'error' => $error);
        return new Response(json_encode($response));
    }

    public function addHighEducationAction()
    {
        $success = false;
        $error = '';

        $entityManager = $this->getDoctrine()->getManager();
        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $userId = $session->get('userSessionData')['userInfo']['userId'];
        $university = $request->get('university');
        $faculty = $request->get('faculty');
        $speciality = $request->get('speciality');
        $degree = $request->get('degree');
        $startYear = $request->get('startYear');
        $endYear = $request->get('endYear');

        $highEducationEntity = new UserHighEducation();
        $highEducationEntity->setUserId($userId);
        $highEducationEntity->setUniversity($university);
        $highEducationEntity->setFaculty($faculty);
        $highEducationEntity->setSpeciality($speciality);
        $highEducationEntity->setDegree($degree);
        $highEducationEntity->setStartDate(new \DateTime(str_replace('.', '', $startYear)));
        if(strlen($endYear) > 0)
            $highEducationEntity->setEndDate(new \DateTime(str_replace('.', '', $endYear)));

        $entityManager->persist($highEducationEntity);
        $entityManager->flush();

        $highEducationId = $highEducationEntity->getRecId();

        $success = true;
        $response = array('success' => $success, 'error' => $error, 'data' => array('highEducationId' => $highEducationId));
        return new Response(json_encode($response));

    }

    public function editHighEducationAction()
    {
        $success = false;
        $error = '';

        $entityManager = $this->getDoctrine()->getManager();
        $highEducationsRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:UserHighEducation');
        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $highEducationRecordId = $request->get('highEducationRecordId');
        $userId = $session->get('userSessionData')['userInfo']['userId'];
        $university = $request->get('university');
        $faculty = $request->get('faculty');
        $speciality = $request->get('speciality');
        $degree = $request->get('degree');
        $startYear = $request->get('startYear');
        $endYear = $request->get('endYear');

        $highEducationEntity = $highEducationsRepo->findOneBy(array('recId' => $highEducationRecordId));
        $highEducationEntity->setUserId($userId);
        $highEducationEntity->setUniversity($university);
        $highEducationEntity->setFaculty($faculty);
        $highEducationEntity->setSpeciality($speciality);
        $highEducationEntity->setDegree($degree);
        $highEducationEntity->setStartDate(new \DateTime(str_replace('.', '', $startYear)));
        if(strlen($endYear) > 0)
            $highEducationEntity->setEndDate(new \DateTime(str_replace('.', '', $endYear)));

        $entityManager->persist($highEducationEntity);
        $entityManager->flush();

        $success = true;
        $response = array('success' => $success, 'error' => $error);
        return new Response(json_encode($response));

    }

    public function deleteHighEducationAction()
    {
        $success = false;
        $error = '';

        $entityManager = $this->getDoctrine()->getManager();
        $highEducationsRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:UserHighEducation');
        $request = $this->get('request_stack')->getCurrentRequest();
        $highEducationRecordId = $request->get('highEducationRecordId');
        $highEducationEntity = $highEducationsRepo->findOneBy(array('recId' => $highEducationRecordId));
        $entityManager->remove($highEducationEntity);
        $entityManager->flush();
        $success = true;

        $success = true;
        $response = array('success' => $success, 'error' => $error);
        return new Response(json_encode($response));
    }

    public function addWorkplaceAction()
    {
        $success = false;
        $error = '';

        $entityManager = $this->getDoctrine()->getManager();
        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $userId = $session->get('userSessionData')['userInfo']['userId'];
        $workplace = $request->get('workplace');
        $position = $request->get('position');
        $startYear = $request->get('startYear');
        $endYear = $request->get('endYear');

        $workplaceEntity = new UserWorkplaces();
        $workplaceEntity->setUserId($userId);
        $workplaceEntity->setCompany($workplace);
        $workplaceEntity->setPosition($position);
        $workplaceEntity->setStartDate(new \DateTime(str_replace('.', '', $startYear)));
        if(strlen($endYear) > 0)
            $workplaceEntity->setEndDate(new \DateTime(str_replace('.', '', $endYear)));

        $entityManager->persist($workplaceEntity);
        $entityManager->flush();

        $workplaceId = $workplaceEntity->getRecId();

        $success = true;
        $response = array('success' => $success, 'error' => $error, 'data' => array('workplaceId' => $workplaceId));
        return new Response(json_encode($response));

    }

    public function editWorkplaceAction()
    {
        $success = false;
        $error = '';

        $entityManager = $this->getDoctrine()->getManager();
        $workplacesRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:UserWorkplaces');
        $request = $this->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $workplaceRecordId = $request->get('workplaceRecordId');
        $userId = $session->get('userSessionData')['userInfo']['userId'];
        $workplace = $request->get('workplace');
        $position = $request->get('position');
        $startYear = $request->get('startYear');
        $endYear = $request->get('endYear');

        $workplaceEntity = $workplacesRepo->findOneBy(array('recId' => $workplaceRecordId));
        $workplaceEntity->setUserId($userId);
        $workplaceEntity->setCompany($workplace);
        $workplaceEntity->setPosition($position);
        $workplaceEntity->setStartDate(new \DateTime(str_replace('.', '', $startYear)));
        if(strlen($endYear) > 0)
            $workplaceEntity->setEndDate(new \DateTime(str_replace('.', '', $endYear)));

        $entityManager->persist($workplaceEntity);
        $entityManager->flush();

        $success = true;
        $response = array('success' => $success, 'error' => $error);
        return new Response(json_encode($response));

    }

    public function deleteWorkplaceAction()
    {
        $success = false;
        $error = '';

        $entityManager = $this->getDoctrine()->getManager();
        $workplacesRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:UserWorkplaces');
        $request = $this->get('request_stack')->getCurrentRequest();
        $workplaceRecordId = $request->get('workplaceRecordId');
        $workplaceEntity = $workplacesRepo->findOneBy(array('recId' => $workplaceRecordId));
        $entityManager->remove($workplaceEntity);
        $entityManager->flush();
        $success = true;

        $success = true;
        $response = array('success' => $success, 'error' => $error);
        return new Response(json_encode($response));
    }
}
