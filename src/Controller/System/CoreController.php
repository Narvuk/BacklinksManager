<?php

namespace App\Controller\System;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use App\Entity\Sites;
use App\Entity\System\Users;
use App\Repository\SitesRepository;
use App\Form\Sites\AddSiteType;

class CoreController extends AbstractController
{
    private $version = '0.0.2';

     /**
     * @Route("/", name="core")
     */
    public function Index(Request $request)
    {
        $sitename = "";
        $sites = $this->getDoctrine()
            ->getRepository(Sites::class)
            ->loadSitesByName($sitename);
        

        // 1) build the form
        $addsite = new Sites();
        $form = $this->createForm(AddSiteType::class, $addsite);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 4) save the site!
            $entityManager = $this->getDoctrine()->getManager();

            $addsite->setCreated(new \DateTime());
            $addsite->setNewProsp('0');
            $addsite->setActiveProsp('0');
            $addsite->setInactiveProsp('0');
            $addsite->setNewBL('0');
            $addsite->setActiveBL('0');
            $addsite->setLostBL('0');

            $entityManager->persist($addsite);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('core');
        }

        return $this->render('index.html.twig',
            [
                'sites' => $sites,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // Check To See if connection exists - If error go to troubleshoot
        //try{
        //    $em = $this->getDoctrine()->getManager()->getConnection()->connect();
        // }
        // catch(\Exception $e){
        //     return $this->redirectToRoute('setup');
        //}

        // Check To See if tables exists - If not run install
        try{
            $repository = $this->getDoctrine()->getRepository(Users::class);
            $user = $repository->findAll();
            if ( count($user) < 1 ){
                return $this->redirectToRoute('setup');
            }
        }
        catch(\Exception $e){
            return $this->redirectToRoute('setup');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('system/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            /*'user'          => $user,*/
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(Request $request, AuthenticationUtils $authenticationUtils)
    {

    }

    /**
     * @Route("/system/info", name="system_information")
     */
    public function SystemAboutInformation(Request $request)
    {
        $fileSystem = new Filesystem();
        $islivemode = $fileSystem->exists('../.env.local');
        if ($islivemode === True){
            $sysmode = 'Developer Mode';
        }else{
            $sysmode = 'Live Mode';
        }

        
        $changelog = file_get_contents('https://stormdevelopers.com/projects/php/backlinksmanager/rsysinfo/changelog');
        $inplanning = file_get_contents('https://stormdevelopers.com/projects/php/backlinksmanager/rsysinfo/inplanning');         
        $nextversion = file_get_contents('https://stormdevelopers.com/projects/php/backlinksmanager/rsysinfo/nextversion');
        $currentversion = file_get_contents('https://stormdevelopers.com/projects/php/backlinksmanager/rsysinfo/currentversion');
        
        $sysversion = $this->version;

        if ($sysversion < $currentversion){
            $isupdate = 'yes';
        } else {
            $isupdate = 'no';
        }


        return $this->render('system/sysinfo.html.twig',
            [
                'changelog' => $changelog,
                'inplanning' => $inplanning,
                'sysmode' => $sysmode,
                'nextversion' => $nextversion,
                'currentversion' => $currentversion,
                'sysversion' => $sysversion,
                'isupdate' => $isupdate,
            ]
        );
    }


    /**
     * @Route("/system/version", name="system_version")
     */
    public function SystemAboutVersion(Request $request)
    {       

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        return $this->render('system/version.html.twig',
            [
                'version' => $this->version,
            ]
        );
    }


    /**
     * @Route("/system/license", name="system_license")
     */
    public function SystemAboutLicense(Request $request)
    {       

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');  

        return $this->render('system/license.html.twig',
            [

            ]
        );
    }

    /**
     * @Route("/system/credits", name="system_credits")
     */
    public function SystemAboutCredits(Request $request)
    {       

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    

        return $this->render('system/credits.html.twig',
            [

            ]
        );
    }

    /**
     * @Route("/system/sponsers", name="system_sponsers")
     */
    public function SystemAboutSponsers(Request $request)
    {       

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
  

        return $this->render('system/sponsers.html.twig',
            [
 
            ]
        );
    }

    /**
     * @Route("/system/contributors", name="system_contributors")
     */
    public function SystemAboutContributors(Request $request)
    {       

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
 

        return $this->render('system/contributors.html.twig',
            [

            ]
        );
    }


     /**
     * @Route("/system/support", name="system_support")
     */
    public function SystemAboutSupport(Request $request)
    {       

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
 

        return $this->render('system/support.html.twig',
            [

            ]
        );
    }


}