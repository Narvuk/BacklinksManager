<?php

namespace App\Controller\System;

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
use App\Entity\System\DataSettings;
use App\Form\System\EditSettingsMainType;
use App\Form\System\EditDataSettingsType;


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
     * @Route("/system/mainsettings", name="system_settings_main")
     */
    public function SystemMainSettings(Request $request)
    {

        $settings = $this->getDoctrine()->getRepository(Settings::class)->find(1);


        // 1) build the form
        $form = $this->createForm(EditSettingsMainType::class, $settings);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 4) save the site!
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($settings);
            $entityManager->flush();

            $this->addFlash('success', 'Successfully Saved Settings');

            return $this->redirectToRoute('system_settings_main');
        }

        return $this->render('system/settings/main.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }


    /**
     * @Route("/system/datasettings", name="system_settings_data")
     */
    public function SystemDataSettings(Request $request)
    {

        $settings = $this->getDoctrine()->getRepository(DataSettings::class)->find(1);


        // 1) build the form
        $form = $this->createForm(EditDataSettingsType::class, $settings);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 4) save the site!
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($settings);
            $entityManager->flush();

            $this->addFlash('success', 'Successfully Saved Data Settings');

            return $this->redirectToRoute('system_settings_data');
        }

        return $this->render('system/settings/data.html.twig',
            [
                'form' => $form->createView(),
            ]
        );

    }
}