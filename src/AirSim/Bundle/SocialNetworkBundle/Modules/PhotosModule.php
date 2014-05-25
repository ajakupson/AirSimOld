<?php
/**
 * Created by JetBrains PhpStorm.
 * User: HP
 * Date: 1.05.14
 * Time: 21:53
 * To change this template use File | Settings | File Templates.
 */

namespace AirSim\Bundle\SocialNetworkBundle\Modules;
use AirSim\Bundle\SocialNetworkBundle\Modules\AlbumModule;


class PhotosModule
{
    public static function getLastPhotos($entityManager, $userId, $maxResult = 12)
    {
        $query = $entityManager->createQueryBuilder();
        $query
            ->select(array('lastPhotos', 'photoAlbums.albumName'))
            ->from('AirSimSocialNetworkBundle:UserPhotos', 'lastPhotos')
            ->innerJoin('AirSimSocialNetworkBundle:UserPhotoAlbums', 'photoAlbums')
            ->where('lastPhotos.albumId = photoAlbums.albumId')
            ->andWhere('photoAlbums.userId = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('lastPhotos.photoId', 'DESC')
            ->setMaxResults($maxResult);
        $lastPhotos = $query->getQuery()->getResult();

        return $lastPhotos;
    }

    public static function getPhotoData($entityManager, $photoId)
    {
        $query = $entityManager->createQueryBuilder();
        $query
            ->select(array('photo', 'photoAlbums.albumName', 'photoAlbums.albumTitle'))
            ->from('AirSimSocialNetworkBundle:UserPhotos', 'photo')
            ->innerJoin('AirSimSocialNetworkBundle:UserPhotoAlbums', 'photoAlbums')
            ->where('photo.albumId = photoAlbums.albumId')
            ->andWhere('photo.photoId = :photoId')
            ->setParameter('photoId', $photoId);
        $photoData = $query->getQuery()->getResult();

        return $photoData[0];
    }

    public static function albumHasPreviousPhoto($entityManager, $albumId, $photoId)
    {
        $query = $entityManager->createQueryBuilder();
        $query
            ->select('photo.photoId')
            ->from('AirSimSocialNetworkBundle:UserPhotos', 'photo')
            ->where('photo.albumId = :albumId')
            ->andWhere('photo.photoId < :photoId')
            ->setParameter('albumId', $albumId)
            ->setParameter('photoId', $photoId)
            ->orderBy('photo.photoId', 'DESC')
            ->setMaxResults(1);
        $previousPhotoId = $query->getQuery()->getResult();
        if($previousPhotoId != null)
            $previousPhotoId = $previousPhotoId[0]['photoId'];

        return $previousPhotoId;
    }

    public static function albumHasNextPhoto($entityManager, $albumId, $photoId)
    {
        $query = $entityManager->createQueryBuilder();
        $query
            ->select('photo.photoId')
            ->from('AirSimSocialNetworkBundle:UserPhotos', 'photo')
            ->where('photo.albumId = :albumId')
            ->andWhere('photo.photoId > :photoId')
            ->setParameter('albumId', $albumId)
            ->setParameter('photoId', $photoId)
            ->orderBy('photo.photoId', 'ASC')
            ->setMaxResults(1);
        $nextPhotoId = $query->getQuery()->getResult();
        if($nextPhotoId != null)
            $nextPhotoId = $nextPhotoId[0]['photoId'];

        return $nextPhotoId;
    }

    public static function getAlbumPhotos($entityManager, $albumId)
    {
        $photosRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:UserPhotos');
        $photoEntities = $photosRepo->findBy(array('albumId' => $albumId), array('dateAdded' => 'DESC'), 7, 0);

        return $photoEntities;
    }

    public static function collectAlbumsAndPhotosData($entityManager, $userId)
    {
        $userAlbumsAndPhotos = array();

        $albums = AlbumModule::getUserAlbums($entityManager, $userId);
        foreach($albums as $album)
        {
            $albumId = $album->getAlbumId();
            $photos = PhotosModule::getAlbumPhotos($entityManager, $albumId);

            $userAlbumsAndPhotos[] = array
            (
                'album' => $album,
                'photos' => $photos
            );
        }

        return $userAlbumsAndPhotos;

    }
}