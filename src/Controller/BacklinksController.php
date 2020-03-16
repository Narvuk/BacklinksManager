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
use App\Entity\Notes\BacklinksNotes;

use App\Form\Backlinks\AddNoteType;

use Symfony\Component\HttpFoundation\JsonResponse;


class BacklinksController extends AbstractController
{

    /**
     * @Route("/backlink", name="backlink_noinfo")
     */
    public function BacklinkNoInfo(Request $request)
    {
        return $this->redirectToRoute('core');
    }

    /**
     * @Route("/backlinks", name="backlinks_noinfo")
     */
    public function BacklinksNoInfo(Request $request)
    {
        return $this->redirectToRoute('core');
    }

    /**
     * @Route("/backlink/{id}", name="backlink_view")
     */
    public function Index($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        $backlink = $this->getDoctrine()->getRepository(Backlinks::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($backlink->getSiteId());

        // Show all & Get Assigned Prospect for Backlink
        $prospects = $this->getDoctrine()->getRepository(Prospects::class)->findBy(['siteid' => $backlink->getSiteId()], ['id' => 'DESC']);
        if ($backlink->getProspectId() != Null){
            $getprospect = $this->getDoctrine()->getRepository(Prospects::class)->find($backlink->getProspectId());
        } else {
            $getprospect = "";
        }

        // Show all & Get Assigned Site Page for Backlink
        $sitepages = $this->getDoctrine()->getRepository(Sitepages::class)->findBy(['siteid' => $backlink->getSiteId()], ['id' => 'DESC']);
        if ($backlink->getSpageId() != Null){
            $spage = $this->getDoctrine()->getRepository(Sitepages::class)->find($backlink->getSpageId());
        } else {
            $spage = "";
        }

        // Show all & Get Assigned Keyword for Backlink
        $keywords = $this->getDoctrine()->getRepository(Keywords::class)->findBy(['siteid' => $backlink->getSiteId()], ['id' => 'DESC']);
        if ($backlink->getKeywordId() != Null){
            $kword = $this->getDoctrine()->getRepository(Keywords::class)->find($backlink->getKeywordId());
        } else {
            $kword = "";
        }

        // Get Notes
        $blnotes = $this->getDoctrine()->getRepository(BacklinksNotes::class)->findBy(['backlinkid' => $backlink->getId(), 'status' => 'Unread'], ['id' => 'DESC']);

        return $this->render('backlinks/view.html.twig',
            [
                'site' => $site,
                'backlink' => $backlink,
                'prospects' => $prospects,
                'getprospect' => $getprospect, 
                'spages' => $sitepages,
                'spage' => $spage,
                'keywords' => $keywords,
                'kword' => $kword,
                'blnotes' => $blnotes,
            ]
        );
    }

    /**
     * @Route("/backlink/{id}/edit", name="backlink_edit")
     */
    public function BacklinkEdit($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($id);
        $backlink = $this->getDoctrine()->getRepository(Backlinks::class)->find($id);

        return $this->render('backlinks/edit.html.twig',
            [
                'site' => $site,
                'backlink' => $backlink,
            ]
        );
    } 

    /**
     * @Route("/backlink/{id}/notes", name="backlink_notes")
     */
    public function BacklinkNotes($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }
        $backlink = $this->getDoctrine()->getRepository(Backlinks::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($backlink->getSiteId());
        $blnotes = $this->getDoctrine()->getRepository(BacklinksNotes::class)->findBy(['backlinkid' => $backlink->getId()]);

        // 1) build the form
        $addnote = new BacklinksNotes();
        $form = $this->createForm(AddNoteType::class, $addnote);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 4) save the site!
            $entityManager = $this->getDoctrine()->getManager();

            $addnote->setSiteId($backlink->getSiteId());
            $addnote->setBacklinkId($id);
            $addnote->setStatus('Unread');
            $addnote->setCreated(new \DateTime());

            $entityManager->persist($addnote);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('backlink_notes', ['id' => $id]);
        }

        return $this->render('backlinks/notes/list.html.twig',
            [
                'site' => $site,
                'backlink' => $backlink,
                'blnotes' => $blnotes,
                'form' => $form->createView(),
            ]
        );
    } 

    /**
     * @Route("/backlink/{id}/updatestatus/{status}", name="backlink_updatestatus")
     */
    public function BacklinksUpdateStatus($id, $status, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

        $backlink = $this->getDoctrine()->getRepository(Backlinks::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($backlink->getSiteId());


        $entityManager = $this->getDoctrine()->getManager();

        $backlink->setStatus($status);

        $backlink->setUpdated(new \DateTime());
        $entityManager->persist($backlink);
        $entityManager->flush();

        $getstatus = $backlink->getStatus();
        
            $temp = array(
               'statuschange' => $getstatus, 
            );   
            $jsonData = $temp;  
         
        return new JsonResponse($jsonData);
        
        }

    }

    /**
     * @Route("/backlink/{id}/assignprospect/{prospectid}", name="backlink_assignprospect")
     */
    public function ProspectAssignPage($id, $prospectid, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

        $backlink = $this->getDoctrine()->getRepository(Backlinks::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($backlink->getSiteId());
        $prospect = $this->getDoctrine()->getRepository(Prospects::class)->find($prospectid);


        $entityManager = $this->getDoctrine()->getManager();

        $backlink->setProspectId($prospectid);

        $backlink->setUpdated(new \DateTime());
        $entityManager->persist($backlink);
        $entityManager->flush();

        $getprospect = $prospect->getName();
        
            $temp = array(
               'getprospect' => $getprospect, 
            );   
            $jsonData = $temp;  
         
        return new JsonResponse($jsonData);
        
        }

    }

    /**
     * @Route("/backlink/{id}/assignsitepage/{pageid}", name="backlink_assignsite")
     */
    public function BacklinksAssignPage($id, $pageid, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

        $backlink = $this->getDoctrine()->getRepository(Backlinks::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($backlink->getSiteId());
        $spage = $this->getDoctrine()->getRepository(Sitepages::class)->find($pageid);


        $entityManager = $this->getDoctrine()->getManager();

        $backlink->setSpageId($pageid);

        $backlink->setUpdated(new \DateTime());
        $entityManager->persist($backlink);
        $entityManager->flush();

        $spageurl = $spage->getUrl();
        
            $temp = array(
               'spageurl' => $spageurl, 
            );   
            $jsonData = $temp;  
         
        return new JsonResponse($jsonData);
        
        }

    }

    /**
     * @Route("/backlink/{id}/assignkeyword/{keywordid}", name="backlink_assignkeyword")
     */
    public function KeywordAssignPage($id, $keywordid, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

        $backlink = $this->getDoctrine()->getRepository(Backlinks::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($backlink->getSiteId());
        $kword = $this->getDoctrine()->getRepository(Keywords::class)->find($keywordid);


        $entityManager = $this->getDoctrine()->getManager();

        $backlink->setKeywordId($keywordid);

        $backlink->setUpdated(new \DateTime());
        $entityManager->persist($backlink);
        $entityManager->flush();

        $getkword = $kword->getKeyword();
        
            $temp = array(
               'kword' => $getkword, 
            );   
            $jsonData = $temp;  
         
        return new JsonResponse($jsonData);
        
        }

    }
    
}