<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpClient\HttpClient;
use App\Entity\System\CronTasks;
use App\Entity\System\Settings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReleaseInfo extends AbstractController
{

    public function SystemVersion()
    {
        $setting = $this->getDoctrine()->getRepository(Settings::class)->findOneBy(['settingkey' => 'system_version']);
        $version = $setting->getSettingValue();
        return $version;
    }

    public function UpdateService()
    {
        $api_key = 'r9zCymkGV3aoqDY3wtwhMoPeVRsgo4h3';
        $url = 'https://infinityweb.solutions/api/software/details/?api_key='.$api_key;
        $json = file_get_contents($url);
        $updates = json_decode($json, TRUE);
        return $updates;
    }

    public function CurrentVersion()
    {
        $setting = $this->getDoctrine()->getRepository(Settings::class)->findOneBy(['settingkey' => 'update_service_currentversion']);
        $currentversion = $setting->getSettingValue();
        return $currentversion;
    }

    public function DevelopmentVersion()
    {
        $setting = $this->getDoctrine()->getRepository(Settings::class)->findOneBy(['settingkey' => 'update_service_developmentversion']);
        $developmentversion = $setting->getSettingValue();
        return $developmentversion;
    }

    public function DevStage()
    {
        $setting = $this->getDoctrine()->getRepository(Settings::class)->findOneBy(['settingkey' => 'update_service_devstage']);
        $devstage = $setting->getSettingValue();
        return $devstage;
    }

    public function ServiceStatus()
    {
        $setting = $this->getDoctrine()->getRepository(Settings::class)->findOneBy(['settingkey' => 'update_service_status']);
        $servicestatus = $setting->getSettingValue();
        return $servicestatus;
    }



    public function UpdateCheck()
    {
        $sysversion = $this->SystemVersion();
        $currentversion = $this->CurrentVersion();

        if ($sysversion < $currentversion){
            $isupdate = 'yes';
        } else {
            $isupdate = 'no';
        }

        return $isupdate;

    }


    public function Announcements()
    {
        try{
            $url = 'https://update.stormdevelopers.com/api/announcements/2';
            $json = file_get_contents($url);
            $announcements = json_decode($json, TRUE);
        }
        catch(\Exception $e){
            $announcements = 'Announcements are offline or no new announcements been sent, They will be some very soon';
        }
        return $announcements;
    }


    // crontasks
    public function UpdateServiceCron()
    {

        //get settings
        $settings = $this->getDoctrine()->getRepository(Settings::class);
        $usconfig = $settings->findOneBy(['settingkey' => 'core_update_service' ]);
        $getsettingtime = $usconfig->getSettingValue();

        // get cron information
        $cronconfig = $this->getDoctrine()->getRepository(CronTasks::class)->findOneBy(['cronkey' => 'update_service_check']);
        $lastrun = $cronconfig->getLastRun();

        $checkdate = $lastrun->add(new \DateInterval('PT'.$getsettingtime.'S'));
        $datenow = new \DateTime('now');

        if ($datenow >= $checkdate)
        {
            $currentverion = $settings->findOneBy(['settingkey' => 'update_service_currentversion']);
            $indev = $settings->findOneBy(['settingkey' => 'update_service_developmentversion']);

            $getinfo = $this->UpdateService();

            $entityManager = $this->getDoctrine()->getManager();

            if($getinfo["currentversion"] > $currentverion->getSettingValue()) {
                $currentverion->setSettingValue($getinfo["currentversion"]);
            }
            if($getinfo["developmentversion"] > $indev->getSettingValue()) {
                $indev->setSettingValue($getinfo["developmentversion"]);
            }
            // update service time refresh rate
            $uservtime = $settings->findOneBy(['settingkey' => 'core_update_service']);
            if($uservtime != $getinfo["uservtime"])
            {
                $uservtime->setSettingValue($getinfo["uservtime"]);
            }
            $devstage = $settings->findOneBy(['settingkey' => 'update_service_devstage']);
            if($devstage != $getinfo["devstage"])
            {
                $devstage->setSettingValue($getinfo["devstage"]);
            }
            /*
            $servicestatus = $settings->findOneBy(['settingkey' => 'update_service_status']);
            if($servicestatus != $getinfo["devstage"])
            {
                $servicestatus->setSettingValue($getinfo["status"]);
            }*/
            $cronconfig->setLastRun(new \DateTime('now'));
            $cronconfig->setNextRun($datenow->add(new \DateInterval('PT'.$getsettingtime.'S')));
            $entityManager->flush();
            $check = 'success';
        } else {
            $check = 'Do not need to run yet';
        }
        // calculate setting and when last run - if longer run code


        //$currentversion = $updates["currentversion"];
        // $nextversion = $updates["indev"];
        // $isupdate = $releaseinfo->UpdateCheck();
        // $servicestatus = $updates["status"];
        // $devstage = $updates["devstage"];


        $status = 'success';
        return $check;
    }

}
