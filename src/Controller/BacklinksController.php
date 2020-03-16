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
     * @Route("/backlink/{id}", name="backlink_view")
     */
    public function Index($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        $backlink = $this->getDoctrine()->getRepository(Backlinks::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($backlink->getSiteId());

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


        return $this->render('backlinks/view.html.twig',
            [
                'site' => $site,
                'backlink' => $backlink,
                'spages' => $sitepages,
                'spage' => $spage,
                'keywords' => $keywords,
                'kword' => $kword,
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