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
    private $settingtype;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $settingname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $settingkey;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $settingvalue;


    // Getters

    public function getId()
    {
        return $this->id;
    }

    public function getSettingType()
    {
        return $this->settingtype;
    }

    public function getSettingName()
    {
        return $this->settingname;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getSettingKey()
    {
        return $this->settingkey;
    }

    public function getSettingValue()
    {
        return $this->settingvalue;
    }


    // Setters

    public function setSettingType($settingtype)
    {
        $this->settingtype = $settingtype;
    }

    public function setSettingName($settingname)
    {
        $this->settingname = $settingname;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setSettingKey($settingkey)
    {
        $this->settingkey = $settingkey;
    }

    public function setSettingValue($settingvalue)
    {
        $this->settingvalue = $settingvalue;
    }
}