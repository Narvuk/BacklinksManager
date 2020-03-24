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
use App\Entity\Linktracking\TrackingUrls;
use App\Form\Linktracking\AddTrackingCampaignType;
use App\Form\Linktracking\AddTrackingUrlType;
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
     * @Route("/linktrack/campaign", name="trackcamp_noinfo")
     */
    public function TrackCampNoInfo(Request $request)
    {
        return $this->redirectToRoute('core');
    }

    /**
     * @Route("/linktrack/campaigns", name="trackcamps_noinfo")
     */
    public function TrackCampsNoInfo(Request $request)
    {
        return $this->redirectToRoute('core');
    }

    /**
     * @Route("/linktrack/campaign/{id}", name="trackcamp_view")
     */
    public function TrackCampaignMain($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        $turlsrepo = $this->getDoctrine()->getRepository(TrackingUrls::class);

        $tcamp = $this->getDoctrine()->getRepository(TrackingCampaigns::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($tcamp->getSiteId());
        $prospect = $this->getDoctrine()->getRepository(Prospects::class)->find($tcamp->getProspectId());
        $turls = $turlsrepo->findBy(['tcampaignid' => $id], ['id' => 'DESC'], $limit = 5);

        $counturls = count($turlsrepo->findBy(['tcampaignid' => $id], ['id' => 'DESC']));


        // 1) build the form
        $addturl = new TrackingUrls();
        $form = $this->createForm(AddTrackingUrlType::class, $addturl);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 4) save the site!
            $entityManager = $this->getDoctrine()->getManager();

            $addturl->setSiteId($tcamp->getSiteId());
            $addturl->setTcampaignId($id);
            $addturl->setUrlHits('0');
            $addturl->setStatus('New');
            $addturl->setCreated(new \DateTime());

            $entityManager->persist($addturl);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('trackcamp_view', ['id' => $id]);
        }

         return $this->render('linktracking/campaigns/view.html.twig',
            [
                'site' => $site,
                'tcamp' => $tcamp,
                'turls' => $turls,
                'prospect' => $prospect,
                'counturls' => $counturls,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/linktrack/campaign/{id}/urls", name="trackcamp_urls_view")
     */
    public function TrackCampaignUrls($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        $tcamp = $this->getDoctrine()->getRepository(TrackingCampaigns::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($tcamp->getSiteId());
        $turls = $this->getDoctrine()->getRepository(TrackingUrls::class)->findBy(['tcampaignid' => $id], ['id' => 'DESC']);

        // 1) build the form
        $addturl = new TrackingUrls();
        $form = $this->createForm(AddTrackingUrlType::class, $addturl);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 4) save the site!
            $entityManager = $this->getDoctrine()->getManager();

            $addturl->setSiteId($tcamp->getSiteId());
            $addturl->setTcampaignId($id);
            $addturl->setUrlHits('0');
            $addturl->setStatus('New');
            $addturl->setCreated(new \DateTime());

            $entityManager->persist($addturl);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('trackcamp_urls_view', ['id' => $id]);
        }

         return $this->render('linktracking/campaigns/urls.html.twig',
            [
                'site' => $site,
                'tcamp' => $tcamp,
                'turls' => $turls,
                'form' => $form->createView(),
            ]
        );
    }
}