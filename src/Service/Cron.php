<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// Connect Entity
use App\Entity\System\Settings;
use App\Entity\System\CronTasks;
// Connect Services
use App\Service\ReleaseInfo;

class Cron extends AbstractController
{

    public function AllRun()
    {

        $finish = 'success';
        return $finish;
    }



}