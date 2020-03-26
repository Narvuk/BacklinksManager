<?php

namespace App\Entity\System;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="sys_datasettings")
 * @ORM\Entity(repositoryClass="App\Repository\System\DataSettingsRepository")
 */
class DataSettings
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
    private $maxpagerows;


    // Getters

    public function getId()
    {
        return $this->id;
    }

    public function getMaxPageRows()
    {
        return $this->maxpagerows;
    }


    // Setters

    public function setMaxPageRows($maxpagerows)
    {
        $this->maxpagerows = $maxpagerows;
    }

}