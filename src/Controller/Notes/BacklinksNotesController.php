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
use App\Entity\Notes\BacklinksNotes;

use App\Form\Backlinks\AddNoteType;

use Symfony\Component\HttpFoundation\JsonResponse;


class BacklinksNotesController extends AbstractController
{

    /**
     * @Route("/backlinks/note", name="backlinks_note_noinfo")
     */
    public function BacklinkNoteNoInfo(Request $request)
    {
        return $this->redirectToRoute('core');
    }

    /**
     * @Route("/backlinks/note/{id}", name="backlinks_note_view")
     */
    public function BacklinkNoteView($id, Request $request)
    {
        $note = $this->getDoctrine()->getRepository(BacklinksNotes::class)->find($id);
        $backlink = $this->getDoctrine()->getRepository(Backlinks::class)->find($note->getbacklinkId());
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($backlink->getSiteId());

        return $this->render('backlinks/notes/view.html.twig',
            [
                'site' => $site,
                'backlink' => $backlink,
                'note' => $note,
            ]
        );
    }

}