<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Sites;
use App\Entity\Backlinks;
use App\Entity\Prospects;
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

        return $this->render('prospects/view.html.twig',
            [
                'site' => $site,
                'prospect' => $prospect,
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