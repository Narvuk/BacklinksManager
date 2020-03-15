<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Sites;
use App\Entity\Backlinks;
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

        return $this->render('backlinks/view.html.twig',
            [
                'site' => $site,
                'backlink' => $backlink,
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
    
}