<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Sites;
use App\Entity\Backlinks;
use App\Entity\Prospects;
use App\Form\Sites\AddBacklinkType;
use App\Form\Sites\AddPageType;
use App\Form\Sites\AddKeywordType;
use App\Form\Sites\AddProspectType;
use Symfony\Component\HttpFoundation\JsonResponse;


class ProspectsController extends AbstractController
{

    /**
     * @Route("/prospect", name="prospect_noinfo")
     */
    public function ProspectNoInfo(Request $request)
    {
        return $this->redirectToRoute('core');
    }

    /**
     * @Route("/prospect/{id}", name="prospect_view")
     */
    public function ProspectView($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        $prospect = $this->getDoctrine()->getRepository(Prospects::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($prospect->getSiteId());

        //count stats
        $bcount = count($backlinks = $this->getDoctrine()->getRepository(Backlinks::class)->findBy(['prospectid' => $prospect->getId()], ['id' => 'DESC']));

        return $this->render('prospects/view.html.twig',
            [
                'site' => $site,
                'prospect' => $prospect,
                'bcount' => $bcount,
            ]
        );
    }


    /**
     * @Route("/prospect/{id}/backlinks", name="prospect_backlinks_view")
     */
    public function ProspectBacklinksView($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        $prospect = $this->getDoctrine()->getRepository(Prospects::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($prospect->getSiteId());
        $backlinks = $this->getDoctrine()->getRepository(Backlinks::class)->findBy(['prospectid' => $prospect->getId()], ['id' => 'DESC']);

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

            $addbacklink->setSiteId($prospect->getSiteId());
            $addbacklink->setProspectId($prospect->getId());
            $addbacklink->setDomain($formatdomain);
            $addbacklink->setStatus('New');
            $addbacklink->setCreated(new \DateTime());

            $entityManager->persist($addbacklink);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('prospect_backlinks_view', ['id' => $id]);
        }

        return $this->render('prospects/backlinks.html.twig',
            [
                'site' => $site,
                'prospect' => $prospect,
                'backlinks' => $backlinks,
                'form' => $form->createView(),
            ]
        );
    }


    /**
     * @Route("/prospect/{id}/edit", name="prospect_edit")
     */
    public function ProspectEdit($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }
        $prospect = $this->getDoctrine()->getRepository(Prospects::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($prospect->getSiteId());

        return $this->render('backlinks/edit.html.twig',
            [
                'site' => $site,
                'prospect' => $prospect,
            ]
        );
    } 

    /**
     * @Route("/prospect/{id}/updatestatus/{status}", name="prospect_updatestatus")
     */
    public function ProspectUpdateStatus($id, $status, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

            $prospect = $this->getDoctrine()->getRepository(Prospects::class)->find($id);
            $site = $this->getDoctrine()->getRepository(Sites::class)->find($prospect->getSiteId());


            $entityManager = $this->getDoctrine()->getManager();

            $prospect->setStatus($status);

            $prospect->setUpdated(new \DateTime());
            $entityManager->persist($prospect);
            $entityManager->flush();

            $getstatus = $prospect->getStatus();
            
                $temp = array(
                'statuschange' => $getstatus, 
                );   
                $jsonData = $temp;  
         
            return new JsonResponse($jsonData);
        }
    }

}