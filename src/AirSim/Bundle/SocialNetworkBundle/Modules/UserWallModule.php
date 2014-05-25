<?php
/**
 * Created by JetBrains PhpStorm.
 * User: HP
 * Date: 1.05.14
 * Time: 21:53
 * To change this template use File | Settings | File Templates.
 */

namespace AirSim\Bundle\SocialNetworkBundle\Modules;


class UserWallModule
{
    public static function getUserWallRecords($entityManager, $userId, $whoId)
    {
        $wallRecordsRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:UserWallRecords');
        $wallRecordLikesRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:WallRecordLikes');
        $wallRecordPicsRepo = $entityManager->getRepository('AirSimSocialNetworkBundle:WallRecordPictures');

        $wallRecords = $wallRecordsRepo->findBy(array('toId' => $userId), array('dateAdded' => 'DESC'));
        $wallRecordsData = array();
        foreach($wallRecords as $record)
        {
            $authorId = $record->getAuthorId();
            $authorData = UserModule::getUserDataById($entityManager, $authorId)[0];
            $likes = sizeof($wallRecordLikesRepo->findBy(array('wallRecId' => $record->getWallRecId(), 'likeDislike' => 1)));
            $dislikes = sizeof($wallRecordLikesRepo->findBy(array('wallRecId' => $record->getWallRecId(), 'likeDislike' => 0)));
            $authorLikeEntity = $wallRecordLikesRepo->findOneBy(array('userId' => $whoId, 'wallRecId' => $record->getWallRecId()));
            $likeStatus = 'active';
            $dislikeStatus = 'active';
            if(sizeof($authorLikeEntity) > 0)
            {
                if($authorLikeEntity->getLikeDislike() == 1)
                    $likeStatus = 'inactive';
                else $dislikeStatus = 'inactive';
            }

            $wallRecordPics = $wallRecordPicsRepo->findBy(array('wallRecId' => $record->getWallRecId()));
            $wallPicsData = array();
            foreach($wallRecordPics as $wallPic)
            {
                $wallPicsData[] = PhotosModule::getPhotoData($entityManager, $wallPic->getPictureId());
            }

            $wallRecordsData[] = array
            (
                'wallRecord' => $record,
                'wallPics' => $wallPicsData,
                'authorId' => $authorData->getUserId(),
                'authorName' => $authorData->getFirstName(),
                'authorFamily' => $authorData->getLastName(),
                'authorUsername' => $authorData->getLogin(),
                'authorWebPic' => $authorData->getWebProfilePic(),
                'likes' => $likes,
                'dislikes' => $dislikes,
                'likeStatus' => $likeStatus,
                'dislikeStatus' => $dislikeStatus
            );
        }

        return $wallRecordsData;
    }
}