<?php

namespace App\Service;

use App\Entity\System\Settings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SystemSettings extends AbstractController
{
    public function getSystemName()
    {
        $getsettings = $this->getDoctrine()->getRepository(Settings::class)->find(1);
        $systemname = $getsettings->getSystemName();

        return $systemname;
    }

}