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
        /*$query
            ->select(array('lastPhotos', 'photoAlbums.albumName'))
            ->from('AirSimSocialNetworkBundle:UserPhotos', 'lastPhotos')
            ->innerJoin('AirSimSocialNetworkBundle:UserPhotoAlbums', 'photoAlbums')
            ->where('lastPhotos.albumId = photoAlbums.albumId')
            ->andWhere('photoAlbums.userId = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('lastPhotos.photoId', 'DESC')
            ->setMaxResults($maxResult);*/
        /*$query
            ->select(array('lastPhotos', 'photoAlbums.albumName'))
            ->from('AirSimSocialNetworkBundle:UserPhotos', 'lastPhotos')
            ->innerJoin('lastPhotos.photoId', 'pId')
            ->innerJoin('AirSimSocialNetworkBundle:Users', 'user')
            ->andWhere('user.userId = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('lastPhotos.photoId', 'DESC')
            ->setMaxResults($maxResult);*/
        $query
            ->select(array('lastPhotos', 'photoAlbums.albumName'))
            ->from('AirSimSocialNetworkBundle:UserPhotoAlbums', 'photoAlbums')
            ->innerJoin('AirSimSocialNetworkBundle:Users', 'user', 'WITH', 'user.userId = :userId')
            ->innerJoin('AirSimSocialNetworkBundle:UserPhotos', 'lastPhotos', 'WITH', 'photoAlbums.albumId = 1')
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
            ->select(array('photo', 'photoAlbums.albumName', 'photoAlbums.albumTitle', 'user.login'))
            ->from('AirSimSocialNetworkBundle:UserPhotos', 'photo')
            ->innerJoin('AirSimSocialNetworkBundle:UserPhotoAlbums', 'photoAlbums', 'WITH', 'photo.albumId = photoAlbums.albumId')
            ->innerJoin('AirSimSocialNetworkBundle:Users', 'user', 'WITH', 'photoAlbums.userId = user.userId')
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

    public static function getPhotoComments($entityManager, $photoId)
    {
        $query = $entityManager->createQueryBuilder();
        $query
            ->select('comment.commentId', 'comment.comment', 'comment.dateAdded', 'author.firstName',
                'author.lastName', 'author.login', 'author.webProfilePic')
            ->from('AirSimSocialNetworkBundle:PhotoComments', 'comment')
            ->innerJoin('AirSimSocialNetworkBundle:Users', 'author', 'WITH', 'comment.userId = author.userId')
            ->where('comment.photoId = :photoId')
            ->setParameter('photoId', $photoId);
        $comments = $query->getQuery()->getResult();

        return $comments;
    }

    public static function getCommentsAuthors($entityManager, $authorsIdsArray)
    {
        $query = $entityManager->createQueryBuilder();
        $query
            ->select('author')
            ->from('AirSimSocialNetworkBundle:Users')
            ->where('author.userId IN (:authors)')
            ->setParameter('authors', $authorsIdsArray);
        $authors = $query->getQuery()->getResult();

        return $authors;
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