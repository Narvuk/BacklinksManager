<?php

namespace App\Controller\Linktracking;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Sites;
use App\Entity\Backlinks;
use App\Entity\Prospects;
use App\Entity\Linktracking\TrackingCampaigns;
use App\Form\Linktracking\AddTrackingCampaignType;
use App\Form\Sites\AddBacklinkType;
use App\Form\Sites\AddPageType;
use App\Form\Sites\AddKeywordType;
use App\Form\Sites\AddProspectType;
use App\Entity\Notes\ProspectsNotes;
use App\Form\Prospects\AddNoteType;

use Symfony\Component\HttpFoundation\JsonResponse;


class TrackingCampaignsController extends AbstractController
{
    /**
     * @Route("/trackcamp", name="trackcamp_noinfo")
     */
    public function TrackCampNoInfo(Request $request)
    {
        return $this->redirectToRoute('core');
    }

    /**
     * @Route("/trackcamp/{id}", name="trackcamp_view")
     */
    public function Index($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        $tcamp = $this->getDoctrine()->getRepository(TrackingCampaigns::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($tcamp->getSiteId());

         return $this->render('linktracking/view.html.twig',
            [
                'site' => $site,
                'tcamp' => $tcamp,
            ]
        );
    }
}