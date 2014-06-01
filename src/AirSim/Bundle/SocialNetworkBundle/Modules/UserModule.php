<?php

namespace AirSim\Bundle\SocialNetworkBundle\Modules;


class UserModule
{
    public static function getUserData($entityManager, $login)
    {
        $query = $entityManager->createQueryBuilder();
        $query
            ->select('user')
            ->from('AirSimSocialNetworkBundle:Users', 'user')
            ->where('user.login = :login')
            ->setParameter('login', $login);
        $user = $query->getQuery()->getResult();

        return $user[0];
    }

    public static function getUserDataById($entityManager, $userId)
    {
        $query = $entityManager->createQueryBuilder();
        $query
            ->select('user')
            ->from('AirSimSocialNetworkBundle:Users', 'user')
            ->where('user.userId = :userId')
            ->setParameter('userId', $userId);
        $user = $query->getQuery()->getResult();

        return $user;
    }

    public static function getUsernameById($entityManager, $userId)
    {
        $query = $entityManager->createQueryBuilder();
        $query
            ->select('user.login')
            ->from('AirSimSocialNetworkBundle:Users', 'user')
            ->where('user.userId = :userId')
            ->setParameter('userId', $userId);
        $username = $query->getQuery()->getResult()[0]['login'];

        return $username;
    }


    public static function getUserHighEducation($entityManager, $username)
    {
        $query = $entityManager->createQueryBuilder();
        /*$query
            ->select('userHighEducation')
            ->from('AirSimSocialNetworkBundle:UserHighEducation', 'userHighEducation')
            ->innerJoin('AirSimSocialNetworkBundle:Users', 'user')
            ->where('userHighEducation.userId = user.userId')
            ->andWhere('user.login = :login')
            ->setParameter('login', $username)
            ->orderBy('userHighEducation.startDate', 'ASC');*/
        $query
            ->select('userHighEducation')
            ->from('AirSimSocialNetworkBundle:Users', 'user')
            ->innerJoin('AirSimSocialNetworkBundle:UserHighEducation', 'userHighEducation')
            ->andWhere('user.userId = :login')
            ->setParameter('login', $username)
            ->orderBy('userHighEducation.startDate', 'ASC');
        $userHighEducation = $query->getQuery()->getResult();

        return $userHighEducation;
    }

    public static function getUserWorkplaces($entityManager, $username)
    {
        $query = $entityManager->createQueryBuilder();
        /*$query
            ->select('userWorkplaces')
            ->from('AirSimSocialNetworkBundle:UserWorkplaces', 'userWorkplaces')
            ->innerJoin('AirSimSocialNetworkBundle:Users', 'user')
            ->where('userWorkplaces.userId = user.userId')
            ->andWhere('user.login = :login')
            ->setParameter('login', $username)
            ->orderBy('userWorkplaces.startDate', 'ASC');*/
        $query
            ->select('userWorkplaces')
            ->from('AirSimSocialNetworkBundle:UserWorkplaces', 'userWorkplaces')
            ->innerJoin('AirSimSocialNetworkBundle:Users', 'user')
            ->andWhere('user.login = :login')
            ->setParameter('login', $username)
            ->orderBy('userWorkplaces.startDate', 'ASC');
        $userWorkplaces = $query->getQuery()->getResult();

        return $userWorkplaces;
    }

    public static function getUserFriends($entityManager, $username, $limit = 0, $offset = 0, $sortBy = 'userId', $sortType = 'ASC')
    {
        $friendsRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:UserFriends');
        $usersRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:Users');
        $userId = $usersRepo->findOneBy(array('login' => $username))->getUserId();

        /*if($limit == 0)
            $userFriends = $friendsRepo->findBy(array('userId' => $userId, 'isAccepted' => 1), array($sortBy => $sortType));
        else $userFriends = $friendsRepo->findBy(array('userId' => $userId, 'isAccepted' => 1), array($sortBy => $sortType), $limit, $offset);*/
        $query = $entityManager->createQueryBuilder();
        if($limit == 0)
        {
            $query
                ->select('userFriends')
                ->from('AirSimSocialNetworkBundle:UserFriends', 'userFriends')
                ->innerJoin('AirSimSocialNetworkBundle:Users', 'user')
                ->andWhere('user.login = :login')
                ->setParameter('login', $username);
                /*->orderBy('userWorkplaces.startDate', 'ASC');*/
            $userFriends = $query->getQuery()->getResult();
        }
        else
        {
            $query
                ->select('userFriends')
                ->from('AirSimSocialNetworkBundle:UserFriends', 'userFriends')
                ->innerJoin('AirSimSocialNetworkBundle:Users', 'user')
                ->andWhere('user.login = :login')
                ->setParameter('login', $username)
                ->setFirstResult($offset)
                ->setMaxResults($limit);
            /*->orderBy('userWorkplaces.startDate', 'ASC');*/
            $userFriends = $query->getQuery()->getResult();
        }

        $userFriendsEntities = array();
        foreach($userFriends as $friend)
        {
            $friendEntity = UserModule::getUserDataById($entityManager, $friend->getFriendId());
            $userFriendsEntities[] = $friendEntity[0];
        }

        return $userFriendsEntities;
    }

    public static function searchUserFriends($entityManager, $userId, $searchText)
    {
        $query = $entityManager->createQueryBuilder();
        $query
            ->select('contacts')
            ->from('AirSimSocialNetworkBundle:Users', 'contacts')
            ->innerJoin('AirSimSocialNetworkBundle:UserFriends', 'userFriends', 'WITH', 'contacts.userId = userFriends.friendId')
            ->andWhere('userFriends.userId = :userId')
            ->andWhere('userFriends.isAccepted = 1')
            ->andWhere($query->expr()->like('contacts.firstName', $query->expr()->literal('%'.$searchText.'%')))
            ->orWhere($query->expr()->like('contacts.lastName', $query->expr()->literal('%'.$searchText.'%')))
            ->setParameter('userId', $userId)
            ->orderBy('contacts.firstName', 'ASC');
        $userFriends = $query->getQuery()->getResult();

        return $userFriends;
    }

    /*public static function getAllUserContacts($entityManager, $userId)
    {
        $query = $entityManager->createQueryBuilder();
        $query
            ->select('friends')
            ->from('AirSimSocialNetworkBundle:Users', 'friends')
            ->from('AirSimSocialNetworkBundle:UserFriends', 'userFriends')
            ->where('userFriends.userId = :userId AND friends.userId = userFriends.friendId')
            ->orWhere('userFriends.friendId = :userId AND friends.userId = userFriends.userId')
            ->setParameter('userId', $userId)
            ->orderBy('friends.firstName', 'ASC');
        $userFriends = $query->getQuery()->getResult();

        return $userFriends;
    }*/

    public static function searchContactsByCriterias($entityManager, $criterias, $offset)
    {
        $contactsIdArray = array();
        $contactsIdArray[] = $criterias['userId'];

        $query = $entityManager->createQueryBuilder();
        $query
            ->select('contacts')
            ->from('AirSimSocialNetworkBundle:Users', 'contacts')
            ->where('contacts.userId NOT in (:idArray)')
            ->setParameter('idArray', $contactsIdArray);

        if($criterias['nameFamily'] != null)
        {
            $query
                ->andWhere($query->expr()->like('contacts.firstName', $query->expr()->literal('%'.$criterias['nameFamily'].'%')))
                ->orWhere($query->expr()->like('contacts.lastName', $query->expr()->literal('%'.$criterias['nameFamily'].'%')));
        }
        if($criterias['gender'] != 'A' && $criterias['gender'] != null)
        {
            $query
                ->andWhere('contacts.gender = :gender')
                ->setParameter('gender',  $criterias['gender']);
        }
        if($criterias['phoneNumber'] != null)
        {
            $query
                ->andWhere($query->expr()->like('contacts.phoneNumber', $query->expr()->literal('%'.$criterias['phoneNumber'].'%')));
        }
        if($criterias['country'] != "0")
        {
            $query
                ->andWhere('contacts.country = :country')
                ->setParameter('country',  $criterias['country']);
        }
        if($criterias['city'] != "0")
        {
            $query
                ->andWhere('contacts.city = :city')
                ->setParameter('city',  $criterias['city']);
        }
        $query
            ->orderBy('contacts.firstName', 'ASC');
        $q = $query->getQuery();
        $q->setFirstResult($offset)
            ->setMaxResults(10);

        $contacts = $q->getResult();

        return $contacts;
    }
}