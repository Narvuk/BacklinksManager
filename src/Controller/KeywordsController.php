<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Sites;
use App\Entity\Backlinks;
use App\Entity\Keywords;
use App\Entity\System\DataSettings;
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

        // Repos
        $knotesrepo = $this->getDoctrine()->getRepository(KeywordsNotes::class);
        $blrepo = $this->getDoctrine()->getRepository(Backlinks::class);

        $keyword = $this->getDoctrine()->getRepository(Keywords::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($keyword->getSiteId());

        // Get Notes
        $knotestasks = $knotesrepo->findBy(['keywordid' => $keyword->getId(), 'status' => 'Unread', 'type' => 'Task'], ['id' => 'DESC']);
        $knotesissues = $knotesrepo->findBy(['keywordid' => $keyword->getId(), 'status' => 'Unread', 'type' => 'Issue'], ['id' => 'DESC']);

        // Latest Backlinks
        $backlinks = $blrepo->findBy(['keywordid' => $id],['id' => 'DESC'], $limit = 5);
        # Count Backlinks
        $getbl = $blrepo->findBy(['keywordid' => $id]);
        $totalbacklinks = count($getbl);

        return $this->render('keywords/view.html.twig',
            [
                'site' => $site,
                'keyword' => $keyword,
                'knotestasks' => $knotestasks,
                'knotesissues' => $knotesissues,
                'backlinks' => $backlinks,
                'totalbacklinks' => $totalbacklinks,
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

        // Repos
        $datasettings = $this->getDoctrine()->getRepository(DataSettings::class)->find(1);
        $knotes = $this->getDoctrine()->getRepository(KeywordsNotes::class);

        $keyword = $this->getDoctrine()->getRepository(Keywords::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($keyword->getSiteId());

        // Paginition
        $page = isset($_GET['page']) ? $_GET['page'] : "1";
        $limit = $datasettings->getMaxPageRows();
        $countmax = count($knotes->findBy(['keywordid' => $keyword->getId()], ['id' => 'DESC']));
        $getmaxpages = ceil($countmax / $limit);
        if ($getmaxpages < 1){
            $maxpages = 1;
        } else {
            $maxpages = $getmaxpages;
        }
        if (isset($_GET['page']) && $_GET['page']!="")
            {
                $currentpage = $_GET['page'];
            } else {
                $currentpage = 1;
            }
        $previouspage = $currentpage - 1;
        $nextpage = $currentpage + 1;
        if ($page){
            $offset = ($page - 1) * $limit;
            $knotes = $knotes->findBy(['keywordid' => $keyword->getId()], ['id' => 'DESC'], $limit, $offset);
        } 

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
                'currentpage' => $currentpage,
                'previouspage' => $previouspage,
                'nextpage' => $nextpage,
                'maxpages' => $maxpages,
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