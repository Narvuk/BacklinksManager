<?php

namespace App\Controller\Linktracking;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Sites;
use App\Entity\Backlinks;
use App\Entity\Prospects;
use App\Entity\Linktracking\TrackingUrls;
use App\Entity\Linktracking\TrackingCampaigns;
use App\Form\Linktracking\AddTrackingCampaignType;
use App\Form\Sites\AddBacklinkType;
use App\Form\Sites\AddPageType;
use App\Form\Sites\AddKeywordType;
use App\Form\Sites\AddProspectType;
use App\Entity\Notes\ProspectsNotes;
use App\Form\Prospects\AddNoteType;

use Symfony\Component\HttpFoundation\JsonResponse;


class TrackingUrlsController extends AbstractController
{

    /**
     * @Route("/linktrack/url", name="trackingurl_noinfo")
     */
    public function TrackCampNoInfo(Request $request)
    {
        return $this->redirectToRoute('core');
    }

    /**
     * @Route("/linktrack/urls", name="trackingurl_noinfo")
     */
    public function TrackCampsNoInfo(Request $request)
    {
        return $this->redirectToRoute('core');
    }

    /**
     * @Route("/linktrack/url/{id}", name="trackingurl_view")
     */
    public function TrackCampaignMain($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        $turl = $this->getDoctrine()->getRepository(TrackingUrls::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($turl->getSiteId());

         return $this->render('linktracking/urls/view.html.twig',
            [
                'site' => $site,
                'turl' => $turl,
            ]
        );
    }

}