<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="site_pages")
 * @ORM\Entity(repositoryClass="App\Repository\SitepagesRepository")
 */
class Sitepages
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
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

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

    public function getSiteId()
    {
        return $this->siteid;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getUrl()
    {
        return $this->url;
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

    public function setSiteId($siteid)
    {
        $this->siteid = $siteid;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function seturl($url)
    {
        $this->url = $url;
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