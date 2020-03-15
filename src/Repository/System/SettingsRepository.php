<?php

namespace App\Repository\System;

use Doctrine\ORM\EntityRepository;
use App\Entity\System\Settings;

class SettingsRepository extends EntityRepository
{
    public function loadsettings($systemname): array
    {

    }

}