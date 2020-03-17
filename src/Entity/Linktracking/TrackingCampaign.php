<?php

namespace App\Entity\Linktracking;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="lt_trackingcampaign")
 * @ORM\Entity(repositoryClass="App\Repository\Linktracking\TrackingCampaignRepository")
 */
class TrackingCampaign
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
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siteid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prospectid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $totalhits;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    // Getters

    public function getId()
    {
        return $this->id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getSiteId()
    {
        return $this->siteid;
    }

    public function getProspectId()
    {
        return $this->prospectid;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getTotalHits()
    {
        return $this->totalhits;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    // Setters

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setSiteId($siteid)
    {
        $this->siteid = $siteid;
    }

    public function setProspectId($prospectid)
    {
        $this->prospectid = $prospectid;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setTotalHits($totalhits)
    {
        $this->totalhits = $totalhits;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

}
