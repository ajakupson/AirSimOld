<?php
/**
 * Created by JetBrains PhpStorm.
 * User: HP
 * Date: 1.05.14
 * Time: 21:53
 * To change this template use File | Settings | File Templates.
 */

namespace AirSim\Bundle\SocialNetworkBundle\Modules;


class AlbumModule
{
    public static function getAlbumInfo($entityManager, $albumId)
    {
        $albumsRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:UserPhotoAlbums');
        $albumEntity = $albumsRepo->findOneBy(array('albumId'));

        return $albumEntity;
    }

    public static function getUserAlbums($entityManager, $userId, $maxResult = 7)
    {
        $albumsRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:UserPhotoAlbums');
        $albumEntities = $albumsRepo->findBy(array('userId' => $userId));

        return $albumEntities;
    }

    public static function getWallPicsAlbumId($entityManager, $userId)
    {
        $albumsRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:UserPhotoAlbums');
        $albumEntity = $albumsRepo->findOneBy(array('userId' => $userId, 'albumName' => 'wall_pics'));
        $albumId = $albumEntity->getAlbumId();

        return $albumId;
    }
}