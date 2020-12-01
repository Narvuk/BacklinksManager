<?php

namespace App\Service;

use App\Entity\System\Settings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SystemSettings extends AbstractController
{
    public function getSystemName()
    {
        $setting = $this->getDoctrine()->getRepository(Settings::class)->findOneBy(['settingkey' => 'system_name']);
        $systemname = $setting->getSettingValue();

        return $systemname;
    }

    public function getMaxPerPage()
    {
        $setting = $this->getDoctrine()->getRepository(Settings::class)->findOneBy(['settingkey' => 'system_items_perpage']);
        $itemsperpage = $setting->getSettingValue();

        return $itemsperpage;
    }

}