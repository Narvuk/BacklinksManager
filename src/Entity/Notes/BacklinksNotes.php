<?php

namespace App\Entity\Notes;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="backlinks_notes")
 * @ORM\Entity(repositoryClass="App\Repository\Notes\BacklinksNotesRepository")
 */
class BacklinksNotes
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
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siteid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $backlinkid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
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

    public function getType()
    {
        return $this->type;
    }

    public function getSiteId()
    {
        return $this->siteid;
    }

    public function getBacklinkId()
    {
        return $this->backlinkid;
    }

    public function getTitle()
    {
        return $this->title;
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

    // Setters

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setSiteId($siteid)
    {
        $this->siteid = $siteid;
    }

    public function setBacklinkId($backlinkid)
    {
        $this->backlinkid = $backlinkid;
    }

    public function setTitle($title)
    {
        $this->title = $title;
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