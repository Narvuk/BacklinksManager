<?php

namespace App\Entity\System;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="sys_crontasks")
 * @ORM\Entity(repositoryClass="App\Repository\System\CronTasksRepository")
 */
class CronTasks
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cronkey;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastrun;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $nextrun;



    // Getters

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getCronKey()
    {
        return $this->cronkey;
    }

    public function getLastRun()
    {
        return $this->lastrun;
    }

    public function getNextRun()
    {
        return $this->nextrun;
    }


    // Setters

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setCronKey($cronkey)
    {
        $this->cronkey = $cronkey;
    }

    public function setLastRun($lastrun)
    {
        $this->lastrun = $lastrun;
    }

    public function setNextRun($nextrun)
    {
        $this->nextrun = $nextrun;
    }

}
