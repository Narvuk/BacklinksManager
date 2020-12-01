<?php

namespace App\Controller\System;

use App\Entity\System\CronTasks;
use App\Form\System\SystemSettingsType;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Entity\System\Settings;
use App\Service\SystemSettings;


class SettingsController extends AbstractController
{
    /**
     * @Route("/system", name="system_settings_dash")
     */
    public function Index(Request $request)
    {

        return $this->render('system/settingsdash.html.twig',
            [

            ]
        );
    }


    /**
     * @Route("/system/settings", name="system_settings_index")
     */
    public function SystemSettingsIndex(Request $request, SystemSettings $systemsettings)
    {
        //repo
        $getsettings = $this->getDoctrine()->getRepository(Settings::class);

        // Paginition
        $page = isset($_GET['page']) ? $_GET['page'] : "1";
        $limit = $systemsettings->getMaxPerPage();
        $countmax = count($getsettings->findBy([], ['id' => 'ASC']));
        $getmaxpages = ceil($countmax / $limit);
        if ($getmaxpages < 1){
            $maxpages = 1;
        } else {
            $maxpages = $getmaxpages;
        }
        if (isset($_GET['page']) && $_GET['page']!="")
        {
            $currentpage = $_GET['page'];
        } else {
            $currentpage = 1;
        }
        $previouspage = $currentpage - 1;
        $nextpage = $currentpage + 1;
        if ($page){
            $offset = ($page - 1) * $limit;
            $settings = $getsettings->findBy([], ['id' => 'ASC'], $limit, $offset);
        }

        return $this->render('system/settings/index.html.twig',
            [
                'settings' => $settings,
                'currentpage' => $currentpage,
                'previouspage' => $previouspage,
                'nextpage' => $nextpage,
                'maxpages' => $maxpages,
            ]
        );
    }

    /**
     * @Route("/system/settings/{id}", name="system_settings_edit")
     */
    public function SystemSettingsEdit($id, Request $request)
    {

        $setting = $this->getDoctrine()->getRepository(Settings::class)->find($id);

        if ($setting->getSettingType() == 'private'){
            return $this->redirectToRoute('system_settings_index');
        }

        // if core setting do not edit
        if ($setting->getSettingType() == 'core') {
            return $this->redirectToRoute('system_settings_index');
        } else {

            // 1) build the form
            $form = $this->createForm(SystemSettingsType::class, $setting);

            // 2) handle the submit (will only happen on POST)
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();

                // 4) save the site!
                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->persist($setting);
                $entityManager->flush();

                $this->addFlash('success', 'Setting Saved');

                return $this->redirectToRoute('system_settings_index');
            }
        }

        return $this->render('system/settings/edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }


    /**
     * @Route("/system/crontasks", name="system_crontasks_index")
     */
    public function SystemCronTasksIndex(Request $request, SystemSettings $systemsettings)
    {
        //repo
        $getcrons = $this->getDoctrine()->getRepository(CronTasks::class);

        // Paginition
        $page = isset($_GET['page']) ? $_GET['page'] : "1";
        $limit = $systemsettings->getMaxPerPage();
        $countmax = count($getcrons->findBy([], ['id' => 'ASC']));
        $getmaxpages = ceil($countmax / $limit);
        if ($getmaxpages < 1){
            $maxpages = 1;
        } else {
            $maxpages = $getmaxpages;
        }
        if (isset($_GET['page']) && $_GET['page']!="")
        {
            $currentpage = $_GET['page'];
        } else {
            $currentpage = 1;
        }
        $previouspage = $currentpage - 1;
        $nextpage = $currentpage + 1;
        if ($page){
            $offset = ($page - 1) * $limit;
            $crontasks = $getcrons->findBy([], ['id' => 'ASC'], $limit, $offset);
        }

        return $this->render('system/crontasks.html.twig',
            [
                'crontasks' => $crontasks,
                'currentpage' => $currentpage,
                'previouspage' => $previouspage,
                'nextpage' => $nextpage,
                'maxpages' => $maxpages,
            ]
        );
    }
}