<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Sites;
use App\Entity\Backlinks;
use App\Entity\Keywords;
use Symfony\Component\HttpFoundation\JsonResponse;


class KeywordsController extends AbstractController
{

    /**
     * @Route("/keyword", name="keyword_noinfo")
     */
    public function ProspectNoInfo(Request $request)
    {
        return $this->redirectToRoute('core');
    }

    /**
     * @Route("/keyword/{id}", name="keyword_view")
     */
    public function KeywordView($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        $keyword = $this->getDoctrine()->getRepository(Keywords::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($keyword->getSiteId());

        return $this->render('keywords/view.html.twig',
            [
                'site' => $site,
                'keyword' => $keyword,
            ]
        );
    }

    /**
     * @Route("/keyword/{id}/edit", name="keyword_edit")
     */
    public function KeywordEdit($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }
        $keyword = $this->getDoctrine()->getRepository(Keywords::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($keyword->getSiteId());

        return $this->render('keywords/edit.html.twig',
            [
                'site' => $site,
                'keyword' => $keyword,
            ]
        );
    } 

    /**
     * @Route("/keyword/{id}/updatestatus/{status}", name="keyword_updatestatus")
     */
    public function KeywordUpdateStatus($id, $status, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

            $keyword = $this->getDoctrine()->getRepository(Keywords::class)->find($id);
            $site = $this->getDoctrine()->getRepository(Sites::class)->find($keyword->getSiteId());


            $entityManager = $this->getDoctrine()->getManager();

            $keyword->setStatus($status);

            $keyword->setUpdated(new \DateTime());
            $entityManager->persist($keyword);
            $entityManager->flush();

            $getstatus = $keyword->getStatus();
            
                $temp = array(
                'statuschange' => $getstatus, 
                );   
                $jsonData = $temp;  
         
            return new JsonResponse($jsonData);
        }
    }

}