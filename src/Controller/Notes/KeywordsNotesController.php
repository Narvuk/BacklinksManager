<?php

namespace App\Controller\Notes;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Sites;
use App\Entity\Backlinks;
use App\Entity\Sitepages;
use App\Entity\Keywords;
use App\Entity\Prospects;
use App\Entity\Notes\KeywordsNotes;

use App\Form\Keywords\AddNoteType;

use Symfony\Component\HttpFoundation\JsonResponse;


class KeywordsNotesController extends AbstractController
{

    /**
     * @Route("/keywords/note", name="keywords_note_noinfo")
     */
    public function KeywordNoteNoInfo(Request $request)
    {
        return $this->redirectToRoute('core');
    }

    /**
     * @Route("/keywords/note/{id}", name="keywords_note_view")
     */
    public function KeywordNoteView($id, Request $request)
    {
        $note = $this->getDoctrine()->getRepository(KeywordsNotes::class)->find($id);
        $keyword = $this->getDoctrine()->getRepository(Keywords::class)->find($note->getKeywordId());
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($keyword->getSiteId());

        return $this->render('keywords/notes/view.html.twig',
            [
                'site' => $site,
                'keyword' => $keyword,
                'note' => $note,
            ]
        );
    }

}