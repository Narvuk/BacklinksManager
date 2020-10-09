<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReleaseInfo extends AbstractController
{

    public function SystemVersion()
    {
        $version = '0.5.7';

        return $version;
    }

    public function CurrentVersion()
    {
        try{
            $currentversion = file_get_contents('https://stormdevelopers.com/software/details/1-Backlinks-Manager/currentversion');
            }
            catch(\Exception $e){
                $currentversion = 'Unavailable';
            }
        
        return $currentversion;
    }


    public function DevelopmentVersion()
    {
        try{
            $currentversion = file_get_contents('https://stormdevelopers.com/software/details/1-Backlinks-Manager/indev');
            }
            catch(\Exception $e){
                $currentversion = 'Unavailable';
            }
        
        return $currentversion;
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
            $currentversion = file_get_contents('https://stormdevelopers.com/software/details/1-Backlinks-Manager/announcements');
            }
            catch(\Exception $e){
                $currentversion = 'Unavailable';
            }
        
        return $currentversion;
    }

}