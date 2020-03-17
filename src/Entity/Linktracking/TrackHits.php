<?php

namespace App\Entity\Linktracking;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="lt_trackhits")
 * @ORM\Entity(repositoryClass="App\Repository\Linktracking\TrackHitsRepository")
 */
class TrackingHits
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siteid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trackingurlid;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $hitdatetime;


    // Getters

    public function getId()
    {
        return $this->id;
    }

    public function getSiteId()
    {
        return $this->siteid;
    }

    public function getTurlId()
    {
        return $this->trackingurlid;
    }

    public function getHitDateTime()
    {
        return $this->hitdatetime;
    }

    // Setters

    public function setSiteId($siteid)
    {
        $this->siteid = $siteid;
    }

    public function setTurlId($trackingurlid)
    {
        $this->trackingurlid = $trackingurlid;
    }

    public function setHitDateTime($hitdatetime)
    {
        $this->hitdatetime = $hitdatetime;
    }


}
