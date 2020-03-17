<?php

namespace App\Entity\Linktracking;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="lt_trackingurl")
 * @ORM\Entity(repositoryClass="App\Repository\Linktracking\TrackingUrlRepository")
 */
class TrackingUrls
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
    private $tcampaignid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siteid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urldestination;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlhits;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lasthit;

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

    public function getTcampaignId()
    {
        return $this->tcampaignid;
    }

    public function getSiteId()
    {
        return $this->siteid;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getUrlDestination()
    {
        return $this->urldestination;
    }

    public function getUrlHits()
    {
        return $this->urlhits;
    }

    public function getLastHit()
    {
        return $this->lasthit;
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

    public function setTcampaignId($tcampaignid)
    {
        $this->tcampaignid = $tcampaignid;
    }

    public function setSiteId($siteid)
    {
        $this->siteid = $siteid;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setUrlDestination($urldestination)
    {
        $this->urldestination = $urldestination;
    }

    public function setUrlHits($urlhits)
    {
        $this->urlhits = $urlhits;
    }

    public function setLastHit($lasthit)
    {
        $this->lasthit = $lasthit;
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
