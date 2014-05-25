<?php

namespace AirSim\Bundle\SocialNetworkBundle\Modules;

class TranslationModule
{
    public function getPageTranslations($entityManager, $pages, $selectedLang)
    {
        $fields = array('trans.alias', 'trans.'.$selectedLang);
        $query = $entityManager->createQueryBuilder();
        $query
            ->select($fields)
            ->from('AirSimSocialNetworkBundle:Translations', 'trans')
            ->where('trans.pageId IN (:pages)')
            ->setParameter('pages', $pages);
        $records = $query->getQuery()->getResult();

        $translations = array();
        foreach($records as $record)
        {
            $translations[$record['alias']] = $record[$selectedLang];
        }

        return $translations;
    }

}