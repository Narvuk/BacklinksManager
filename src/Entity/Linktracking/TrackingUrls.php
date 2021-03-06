<?php

namespace App\Entity\Linktracking;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="lt_trackingurls")
 * @ORM\Entity(repositoryClass="App\Repository\Linktracking\TrackingUrlsRepository")
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
    private $prospectid;

     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $keywordid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $spageid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tlink;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dlink;

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

    public function getProspectId()
    {
        return $this->prospectid;
    }

    public function getKeywordId()
    {
        return $this->keywordid;
    }

    public function getSpageId()
    {
        return $this->spageid;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getTlink()
    {
        return $this->tlink;
    }

    public function getDlink()
    {
        return $this->dlink;
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

    public function setProspectId($prospectid)
    {
        $this->prospectid = $prospectid;
    }

    public function setKeywordId($keywordid)
    {
        $this->keywordid = $keywordid;
    }

    public function setSpageId($spageid)
    {
        $this->spageid = $spageid;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setTlink($tlink)
    {
        $this->tlink = $tlink;
    }

    public function setDlink($dlink)
    {
        $this->dlink = $dlink;
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
