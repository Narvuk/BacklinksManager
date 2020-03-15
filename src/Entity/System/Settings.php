<?php

namespace App\Entity\System;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="sys_settings")
 * @ORM\Entity(repositoryClass="App\Repository\System\SettingsRepository")
 */
class Settings
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
    private $systemname;


    // Getters

    public function getId()
    {
        return $this->id;
    }

    public function getSystemName()
    {
        return $this->systemname;
    }


    // Setters

    public function setSystemName($systemname)
    {
        $this->systemname = $systemname;
    }

}