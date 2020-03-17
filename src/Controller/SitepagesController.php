<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Sites;
use App\Entity\Backlinks;
use App\Entity\Keywords;
use App\Entity\Sitepages;
use App\Entity\Notes\SitepagesNotes;
use App\Form\Sitepages\AddNoteType;

use Symfony\Component\HttpFoundation\JsonResponse;


class SitepagesController extends AbstractController
{

    /**
     * @Route("/sitepage", name="sitepage_noinfo")
     */
    public function SitepageNoInfo(Request $request)
    {
        return $this->redirectToRoute('core');
    }

    /**
     * @Route("/sitepage/{id}", name="sitepage_view")
     */
    public function SitepageView($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        $spage = $this->getDoctrine()->getRepository(Sitepages::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($spage->getSiteId());

        // Get Notes
        $spnotes = $this->getDoctrine()->getRepository(SitepagesNotes::class)->findBy(['spageid' => $spage->getId(), 'status' => 'Unread'], ['id' => 'DESC']);

        return $this->render('sitepages/view.html.twig',
            [
                'site' => $site,
                'spage' => $spage,
                'spnotes' => $spnotes,
            ]
        );
    }

    /**
     * @Route("/sitepage/{id}/edit", name="sitepage_edit")
     */
    public function SitepageEdit($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }
        $spage = $this->getDoctrine()->getRepository(Sitepages::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($spage->getSiteId());

        return $this->render('sitepages/edit.html.twig',
            [
                'site' => $site,
                'spage' => $spage,
            ]
        );
    } 

    /**
     * @Route("/sitepage/{id}/notes", name="sitepage_notes")
     */
    public function SitepageNotes($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }
        $spage = $this->getDoctrine()->getRepository(Sitepages::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($spage->getSiteId());
        $spnotes = $this->getDoctrine()->getRepository(SitepagesNotes::class)->findBy(['spageid' => $spage->getId()]);

        // 1) build the form
        $addnote = new SitepagesNotes();
        $form = $this->createForm(AddNoteType::class, $addnote);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 4) save the site!
            $entityManager = $this->getDoctrine()->getManager();

            $addnote->setSiteId($spage->getSiteId());
            $addnote->setSpageId($id);
            $addnote->setStatus('Unread');
            $addnote->setCreated(new \DateTime());

            $entityManager->persist($addnote);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('sitepage_notes', ['id' => $id]);
        }

        return $this->render('sitepages/notes/list.html.twig',
            [
                'site' => $site,
                'spage' => $spage,
                'spnotes' => $spnotes,
                'form' => $form->createView(),
            ]
        );
    } 

    /**
     * @Route("/sitepage/{id}/updatestatus/{status}", name="sitepage_updatestatus")
     */
    public function SitepageUpdateStatus($id, $status, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

            $spage = $this->getDoctrine()->getRepository(Sitepages::class)->find($id);
            $site = $this->getDoctrine()->getRepository(Sites::class)->find($spage->getSiteId());


            $entityManager = $this->getDoctrine()->getManager();

            $spage->setStatus($status);

            $spage->setUpdated(new \DateTime());
            $entityManager->persist($spage);
            $entityManager->flush();

            $getstatus = $spage->getStatus();
            
                $temp = array(
                'statuschange' => $getstatus, 
                );   
                $jsonData = $temp;  
         
            return new JsonResponse($jsonData);
        }
    }

}
