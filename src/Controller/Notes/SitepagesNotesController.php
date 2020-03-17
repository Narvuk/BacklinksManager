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
use App\Entity\Notes\SitepagesNotes;

use App\Form\Sitepages\AddNoteType;

use Symfony\Component\HttpFoundation\JsonResponse;


class SitepagesNotesController extends AbstractController
{

    /**
     * @Route("/sitepages/note", name="sitepages_note_noinfo")
     */
    public function SitepageNoteNoInfo(Request $request)
    {
        return $this->redirectToRoute('core');
    }

    /**
     * @Route("/sitepages/note/{id}", name="sitepages_note_view")
     */
    public function SitepageNoteView($id, Request $request)
    {
        $note = $this->getDoctrine()->getRepository(SitepagesNotes::class)->find($id);
        $spage = $this->getDoctrine()->getRepository(Sitepages::class)->find($note->getSpageId());
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($spage->getSiteId());

        return $this->render('sitepages/notes/view.html.twig',
            [
                'site' => $site,
                'spage' => $spage,
                'note' => $note,
            ]
        );
    }

}