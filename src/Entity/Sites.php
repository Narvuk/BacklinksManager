<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="sites")
 * @ORM\Entity(repositoryClass="App\Repository\SitesRepository")
 */
class Sites
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $newprosp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activeprosp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $inactiveprosp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $newbl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activebl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lostbl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activesitepages;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activetrackcamps;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activetracklinks;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;


    //Getters

    public function getId()
    {
        return $this->id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getNewProsp()
    {
        return $this->newprosp;
    }

    public function getActiveProsp()
    {
        return $this->activeprosp;
    }

    public function getInactiveProsp()
    {
        return $this->inactiveprosp;
    }

    public function getNewBL()
    {
        return $this->newbl;
    }

    public function getActiveBL()
    {
        return $this->activebl;
    }

    public function getLostBL()
    {
        return $this->lostbl;
    }

    public function getActiveSitePages()
    {
        return $this->activesitepages;
    }

    public function getActiveTrackCamps()
    {
        return $this->activetrackcamps;
    }

    public function getActiveTrackLinks()
    {
        return $this->activetracklinks;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    //Setters

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setNewProsp($newprosp)
    {
        $this->newprosp = $newprosp;
    }

    public function setActiveProsp($activeprosp)
    {
        $this->activeprosp = $activeprosp;
    }

    public function setInactiveProsp($inactiveprosp)
    {
        $this->inactiveprosp = $inactiveprosp;
    }

    public function setNewBL($newbl)
    {
        $this->newbl = $newbl;
    }

    public function setActiveBL($activebl)
    {
        $this->activebl = $activebl;
    }

    public function setLostBL($lostbl)
    {
        $this->lostbl = $lostbl;
    }

    public function setActiveSitePages($activesitepages)
    {
        $this->activesitepages = $activesitepages;
    }

    public function setActiveTrackCamps($activetrackcamps)
    {
        $this->activetrackcamps = $activetrackcamps;
    }

    public function setActiveTrackLinks($activetracklinks)
    {
        $this->activetracklinks = $activetracklinks;
    }

    public function setDescription($description)
    {
        $this->description = $description;
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