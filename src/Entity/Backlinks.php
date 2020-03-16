<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="backlinks")
 * @ORM\Entity(repositoryClass="App\Repository\BacklinksRepository")
 */
class Backlinks
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
    private $prospectid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $spageid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $keywordid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $domain;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $backlink;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastchecked;


    //Getters

    public function getId()
    {
        return $this->id;
    }

    public function getSiteId()
    {
        return $this->siteid;
    }

    public function getProspectId()
    {
        return $this->prospectid;
    }

    public function getSpageId()
    {
        return $this->spageid;
    }

    public function getKeywordId()
    {
        return $this->keywordid;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getBacklink()
    {
        return $this->backlink;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function getLastChecked()
    {
        return $this->lastchecked;
    }

    //Setters

    public function setSiteId($siteid)
    {
        $this->siteid = $siteid;
    }

    public function setProspectId($prospectid)
    {
        $this->prospectid = $prospectid;
    }

    public function setSpageId($spageid)
    {
        $this->spageid = $spageid;
    }

    public function setKeywordId($keywordid)
    {
        $this->keywordid = $keywordid;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public function setBacklink($backlink)
    {
        $this->backlink = $backlink;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    public function setLastChecked($lastchecked)
    {
        $this->lastchecked = $lastchecked;
    }

}