<?php

namespace AirSim\Bundle\SocialNetworkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Translations
 *
 * @ORM\Table(name="translations", uniqueConstraints={@ORM\UniqueConstraint(name="alias", columns={"alias"})})
 * @ORM\Entity
 */
class Translations
{
    /**
     * @var integer
     *
     * @ORM\Column(name="word_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $wordId;

    /**
     * @var integer
     *
     * @ORM\Column(name="page_id", type="integer", nullable=false)
     */
    private $pageId;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=50, nullable=false)
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="eng", type="text", nullable=false)
     */
    private $eng;

    /**
     * @var string
     *
     * @ORM\Column(name="rus", type="text", nullable=false)
     */
    private $rus;

    /**
     * @var string
     *
     * @ORM\Column(name="est", type="text", nullable=false)
     */
    private $est;



    /**
     * Get wordId
     *
     * @return integer 
     */
    public function getWordId()
    {
        return $this->wordId;
    }

    /**
     * Set pageId
     *
     * @param integer $pageId
     * @return Translations
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;

        return $this;
    }

    /**
     * Get pageId
     *
     * @return integer 
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return Translations
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set eng
     *
     * @param string $eng
     * @return Translations
     */
    public function setEng($eng)
    {
        $this->eng = $eng;

        return $this;
    }

    /**
     * Get eng
     *
     * @return string 
     */
    public function getEng()
    {
        return $this->eng;
    }

    /**
     * Set rus
     *
     * @param string $rus
     * @return Translations
     */
    public function setRus($rus)
    {
        $this->rus = $rus;

        return $this;
    }

    /**
     * Get rus
     *
     * @return string 
     */
    public function getRus()
    {
        return $this->rus;
    }

    /**
     * Set est
     *
     * @param string $est
     * @return Translations
     */
    public function setEst($est)
    {
        $this->est = $est;

        return $this;
    }

    /**
     * Get est
     *
     * @return string 
     */
    public function getEst()
    {
        return $this->est;
    }
}
