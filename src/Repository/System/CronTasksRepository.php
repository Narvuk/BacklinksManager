<?php

namespace App\Repository\System;

use Doctrine\ORM\EntityRepository;
use App\Entity\System\CronTasks;

class CronTasksRepository extends EntityRepository
{
    public function loadcron($cronkey): array
    {

    }

}