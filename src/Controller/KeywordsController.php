<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Sites;
use App\Entity\Backlinks;
use App\Entity\Keywords;
use App\Entity\Notes\KeywordsNotes;
use App\Form\Keywords\AddNoteType;

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

        // Get Notes
        $knotes = $this->getDoctrine()->getRepository(KeywordsNotes::class)->findBy(['keywordid' => $keyword->getId(), 'status' => 'Unread'], ['id' => 'DESC']);

        return $this->render('keywords/view.html.twig',
            [
                'site' => $site,
                'keyword' => $keyword,
                'knotes' => $knotes,
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
     * @Route("/keyword/{id}/notes", name="keyword_notes")
     */
    public function KeywordNotes($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }
        $keyword = $this->getDoctrine()->getRepository(Keywords::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($keyword->getSiteId());
        $knotes = $this->getDoctrine()->getRepository(KeywordsNotes::class)->findBy(['keywordid' => $keyword->getId()]);

        // 1) build the form
        $addnote = new KeywordsNotes();
        $form = $this->createForm(AddNoteType::class, $addnote);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 4) save the site!
            $entityManager = $this->getDoctrine()->getManager();

            $addnote->setSiteId($keyword->getSiteId());
            $addnote->setKeywordId($id);
            $addnote->setStatus('Unread');
            $addnote->setCreated(new \DateTime());

            $entityManager->persist($addnote);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('keyword_notes', ['id' => $id]);
        }

        return $this->render('keywords/notes/list.html.twig',
            [
                'site' => $site,
                'keyword' => $keyword,
                'knotes' => $knotes,
                'form' => $form->createView(),
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