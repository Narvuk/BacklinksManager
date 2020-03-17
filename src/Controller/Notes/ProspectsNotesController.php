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
use App\Entity\Notes\ProspectsNotes;

use App\Form\Prospects\AddNoteType;

use Symfony\Component\HttpFoundation\JsonResponse;


class ProspectsNotesController extends AbstractController
{

    /**
     * @Route("/prospects/note", name="prospects_note_noinfo")
     */
    public function ProspectNoteNoInfo(Request $request)
    {
        return $this->redirectToRoute('core');
    }

    /**
     * @Route("/prospects/note/{id}", name="prospects_note_view")
     */
    public function ProspectNoteView($id, Request $request)
    {
        $note = $this->getDoctrine()->getRepository(ProspectsNotes::class)->find($id);
        $prospect = $this->getDoctrine()->getRepository(Prospects::class)->find($note->getProspectId());
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($prospect->getSiteId());

        return $this->render('prospects/notes/view.html.twig',
            [
                'site' => $site,
                'prospect' => $prospect,
                'note' => $note,
            ]
        );
    }

}