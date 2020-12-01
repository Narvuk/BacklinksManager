<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// Connect Entity
use App\Entity\System\Settings;
use App\Entity\System\CronTasks;

class DatabaseInstallData extends AbstractController
{

    public function SettingsData()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $setting = new Settings();
        $setting->setSettingType('core');
        $setting->setSettingName('Update Service');
        $setting->setSettingKey('core_update_service');
        $setting->setSettingValue('0');
        $setting->setDescription('Timer Interval for checking Updates');
        $entityManager->persist($setting);

        $setting = new Settings();
        $setting->setSettingType('system');
        $setting->setSettingName('Items Per Page');
        $setting->setSettingKey('system_items_perpage');
        $setting->setSettingValue('20');
        $setting->setDescription('Items Per Page on listing');
        $entityManager->persist($setting);

        $setting = new Settings();
        $setting->setSettingType('system');
        $setting->setSettingName('System Name');
        $setting->setSettingKey('system_name');
        $setting->setSettingValue('Backlinks Manager');
        $setting->setDescription('Name Of System - Company Name');
        $entityManager->persist($setting);

        $setting = new Settings();
        $setting->setSettingType('private');
        $setting->setSettingName('System Version');
        $setting->setSettingKey('system_version');
        $setting->setSettingValue('0.6.0');
        $setting->setDescription('Installed System Version');
        $entityManager->persist($setting);

        $setting = new Settings();
        $setting->setSettingType('private');
        $setting->setSettingName('Current Version');
        $setting->setSettingKey('update_service_currentversion');
        $setting->setSettingValue('0.0.0');
        $setting->setDescription('Latest Released Version');
        $entityManager->persist($setting);

        $setting = new Settings();
        $setting->setSettingType('private');
        $setting->setSettingName('Development Version');
        $setting->setSettingKey('update_service_developmentversion');
        $setting->setSettingValue('0.0.0');
        $setting->setDescription('Latest Development Version');
        $entityManager->persist($setting);

        $setting = new Settings();
        $setting->setSettingType('private');
        $setting->setSettingName('Development Stage');
        $setting->setSettingKey('update_service_devstage');
        $setting->setSettingValue('Beta');
        $setting->setDescription('Current Development Stage');
        $entityManager->persist($setting);

        $setting = new Settings();
        $setting->setSettingType('private');
        $setting->setSettingName('Update Service Status');
        $setting->setSettingKey('update_service_status');
        $setting->setSettingValue('Live');
        $setting->setDescription('Status of Updates Service');
        $entityManager->persist($setting);

        // Crons
        $datenow = new \DateTime('now');
        $cron = new CronTasks();
        $cron->setTitle('Update Service Check');
        $cron->setCronKey('update_service_check');
        $cron->setLastRun($datenow->modify("-30 day"));
        $cron->setNextRun($datenow);
        $cron->setDescription('Checks Update Services to see if new updates');
        $entityManager->persist($cron);
        $entityManager->flush();

        $finish = 'success';
        return $finish;
    }



}
