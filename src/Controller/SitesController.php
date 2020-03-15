<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Sites;
use App\Entity\Backlinks;
use App\Entity\Sitepages;
use App\Entity\Keywords;
use App\Entity\Prospects;
use App\Form\Sites\AddBacklinkType;
use App\Form\Sites\AddPageType;
use App\Form\Sites\AddKeywordType;
use App\Form\Sites\AddProspectType;
use Symfony\Component\HttpFoundation\JsonResponse;

class SitesController extends AbstractController
{

    /**
     * @Route("/site", name="site_noinfo")
     */
    public function SiteNoInfo(Request $request)
    {
        return $this->redirectToRoute('core');
    }

    /**
     * @Route("/site/{id}", name="site_dashboard")
     */
    public function Index($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        $site = $this->getDoctrine()->getRepository(Sites::class)->find($id);
        $newbls = $this->getDoctrine()->getRepository(Backlinks::class)->findBy(['siteid' => $id, 'status' => 'New'], ['id' => 'DESC']);

        return $this->render('sites/index.html.twig',
            [
                'site' => $site,
                'newbls' => $newbls,
            ]
        );
    }


    /**
     * @Route("/site/{id}/updatecount", name="site_dashboard_updatecount")
     */
    public function DashUpdateCount($id, Request $request)
    {
        if ($request->isXmlHttpRequest()) { 

        // Database Repos
        $backlinkrepo = $this->getDoctrine()->getRepository(Backlinks::class);
        $prospectrepo = $this->getDoctrine()->getRepository(Prospects::class);

        $site = $this->getDoctrine()->getRepository(Sites::class)->find($id);

        // backlinks
        $newbl = $backlinkrepo->findBy(['siteid' => $id, 'status' => 'New'] );
        $activebl = $backlinkrepo->findBy(['siteid' => $id, 'status' => 'Active'] );
        // prospects
        $newprosp = $prospectrepo->findBy(['siteid' => $id, 'status' => 'New'] );
        $activeprosp = $prospectrepo->findBy(['siteid' => $id, 'status' => 'Active'] );

        // Count Totals to update site info
        // backlinks
        $countnew = count($newbl);
        $countactive = count($activebl);
        // Prospects
        $cnprosp = count($newprosp);
        $caprosp = count($activeprosp);


        $entityManager = $this->getDoctrine()->getManager();

        $site->setNewBL($countnew);
        $site->setActiveBL($countactive);
        $site->setNewProsp($cnprosp);
        $site->setActiveProsp($caprosp);
        $site->setUpdated(new \DateTime());
        $entityManager->persist($site);
        $entityManager->flush();
        
            $temp = array(
               'newbl' => $countnew,  
               'activebl' => $countactive,
               'cnprosp' => $cnprosp,
               'caprosp' => $caprosp,  
            );   
            $jsonData = $temp;  
         
        return new JsonResponse($jsonData);
        
        }

    }


    /**
     * @Route("/site/{id}/prospects", name="site_prospects")
     */
    public function Prospects($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        $site = $this->getDoctrine()->getRepository(Sites::class)->find($id);
        $prospects = $this->getDoctrine()->getRepository(Prospects::class)->findBy(['siteid' => $id], ['id' => 'DESC']);

        // 1) build the form
        $addprospect = new Prospects();
        $form = $this->createForm(AddProspectType::class, $addprospect);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 4) save the site!
            $entityManager = $this->getDoctrine()->getManager();

            //$getdomain = parse_url($form->get('Url')->getData());
           // $formatdomain = $getdomain['host'];

            $addprospect->setSiteId($id);
            //$addbacklink->setDomain($formatdomain);
            $addprospect->setStatus('New');
            $addprospect->setCreated(new \DateTime());

            $entityManager->persist($addprospect);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('site_prospects', ['id' => $id]);
        }

        return $this->render('sites/prospects.html.twig',
            [
                'site' => $site,
                'prospects' => $prospects,
                'form' => $form->createView(),
            ]
        );
    }


    /**
     * @Route("/site/{id}/backlinks", name="site_backlinks")
     */
    public function Backlinks($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        $site = $this->getDoctrine()->getRepository(Sites::class)->find($id);
        $backlinks = $this->getDoctrine()->getRepository(Backlinks::class)->findBy(['siteid' => $id], ['id' => 'DESC']);

        // 1) build the form
        $addbacklink = new Backlinks();
        $form = $this->createForm(AddBacklinkType::class, $addbacklink);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 4) save the site!
            $entityManager = $this->getDoctrine()->getManager();

            $getdomain = parse_url($form->get('Backlink')->getData());
            $formatdomain = $getdomain['host'];

            $addbacklink->setSiteId($id);
            $addbacklink->setDomain($formatdomain);
            $addbacklink->setStatus('New');
            $addbacklink->setCreated(new \DateTime());

            $entityManager->persist($addbacklink);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('site_backlinks', ['id' => $id]);
        }

        return $this->render('sites/backlinks.html.twig',
            [
                'site' => $site,
                'backlinks' => $backlinks,
                'form' => $form->createView(),
            ]
        );
    }


    /**
     * @Route("/site/{id}/linkingdomains", name="site_linking_domains")
     */
    public function LinkingDomains($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        $site = $this->getDoctrine()->getRepository(Sites::class)->find($id);

        return $this->render('sites/index.html.twig',
            [
                'site' => $site,
            ]
        );
    }


    /**
     * @Route("/site/{id}/keywords", name="site_keywords")
     */
    public function SiteKeywords($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        $site = $this->getDoctrine()->getRepository(Sites::class)->find($id);
        $sitekeywords = $this->getDoctrine()->getRepository(Keywords::class)->findBy(['siteid' => $id], ['id' => 'DESC']);

        // 1) build the form
        $addkeyword = new Keywords();
        $form = $this->createForm(AddKeywordType::class, $addkeyword);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 4) save the site!
            $entityManager = $this->getDoctrine()->getManager();

            //$getdomain = parse_url($form->get('Url')->getData());
           // $formatdomain = $getdomain['host'];

            $addkeyword->setSiteId($id);
            //$addbacklink->setDomain($formatdomain);
            $addkeyword->setStatus('New');
            $addkeyword->setCreated(new \DateTime());

            $entityManager->persist($addkeyword);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('site_keywords', ['id' => $id]);
        }

        return $this->render('sites/keywords.html.twig',
            [
                'site' => $site,
                'sitekeywords' => $sitekeywords,
                'form' => $form->createView(),
            ]
        );
    }


    /**
     * @Route("/site/{id}/pages", name="site_pages")
     */
    public function SitePages($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        $site = $this->getDoctrine()->getRepository(Sites::class)->find($id);
        $sitepages = $this->getDoctrine()->getRepository(SitePages::class)->findBy(['siteid' => $id], ['id' => 'DESC']);

        // 1) build the form
        $addpage = new SitePages();
        $form = $this->createForm(AddPageType::class, $addpage);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 4) save the site!
            $entityManager = $this->getDoctrine()->getManager();

            //$getdomain = parse_url($form->get('Url')->getData());
           // $formatdomain = $getdomain['host'];

            $addpage->setSiteId($id);
            //$addbacklink->setDomain($formatdomain);
            $addpage->setStatus('New');
            $addpage->setCreated(new \DateTime());

            $entityManager->persist($addpage);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('site_pages', ['id' => $id]);
        }

        return $this->render('sites/pages.html.twig',
            [
                'site' => $site,
                'sitepages' => $sitepages,
                'form' => $form->createView(),
            ]
        );
    }

}