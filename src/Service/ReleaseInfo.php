<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReleaseInfo extends AbstractController
{
    public function CurrentVersion()
    {
        $version = '0.5.5';

        return $version;
    }

}