<?php

namespace App\Controller\Linktracking;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Sites;
use App\Entity\Sitepages;
use App\Entity\Backlinks;
use App\Entity\Prospects;
use App\Entity\Keywords;
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


class TrackingController extends AbstractController
{
    /**
     * @Route("/t={turlid}{prospectid}{spageid}{keywordid}", name="ref_info")
     */
    public function TrackIncominglink($turlid, $prospectid, $spageid, $keywordid, Request $request)
    {

        $turl = $this->getDoctrine()->getRepository(TrackingUrls::class)->find($turlid);
        $prospect = $this->getDoctrine()->getRepository(Prospects::class)->find($prospectid);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($prospect->getSiteId());
        $tcamp = $this->getDoctrine()->getRepository(TrackingCampaigns::class)->find($turl->getTcampaignId());
        
        $referer = $request->server->get('HTTP_REFERER');

        if ($referer === NULL){
            $referer = 'Direct';
        } else {

            $getdomain = parse_url($referer);
            $formatdomain = $getdomain['host'];

            $findrefbl =  $this->getDoctrine()->getRepository(Backlinks::class)->findOneBy(['backlink' => $referer, 'keywordid' => $keywordid]);

            if ($findrefbl === NULL){

                $addbacklink = new Backlinks();

                $entityManager = $this->getDoctrine()->getManager();

                $addbacklink->setSiteId($prospect->getSiteId());
                $addbacklink->setProspectId($prospect->getId());
                $addbacklink->setBacklink($referer);
                $addbacklink->setKeywordId($keywordid);
                $addbacklink->setSpageId($spageid);
                $addbacklink->setDomain($formatdomain);
                $addbacklink->setStatus('Active');
                $addbacklink->setCreated(new \DateTime());

                $entityManager->persist($addbacklink);
                $entityManager->flush();

            } else {

                $findrefbl =  $this->getDoctrine()->getRepository(Backlinks::class)->findOneBy(['backlink' => $referer, 'keywordid' => $keywordid]);

                $entityManager = $this->getDoctrine()->getManager();

                $findrefbl->setStatus('Active');
                $findrefbl->setLastChecked(new \DateTime());

                $entityManager->persist($findrefbl);
                $entityManager->flush();

            }

        }
        
        $entityManager = $this->getDoctrine()->getManager();
        
        // Tracking Url
        $turl->setUrlHits($turl->getUrlHits() + 1);
        $turl->setLastHit(new \DateTime('Now'));
        $turl->setStatus('Active');

        // Tracking Campaign
        $tcamp->setTotalHits($tcamp->getTotalHits() + 1);
        $tcamp->setStatus('Active');

        // Prospect
        $prospstatus = $prospect->getStatus();
        if ($prospstatus === 'New'){
            $prospect->setStatus('Active');
        }
        if ($prospstatus === 'Inactive'){
            $prospect->setStatus('Active');
        }

        $entityManager->persist($turl);
        $entityManager->persist($tcamp);
        $entityManager->persist($prospect);
        $entityManager->flush();
        
        $page = $this->getDoctrine()->getRepository(Sitepages::class)->find($spageid);
        $getpage = $page->getUrl();
        // This return redirect allows referer to follow : yay
        //var_dump($getpage);
        return $this->redirect($getpage);
    }


    /**
     * @Route("/d={turlid}{prospectid}{spageid}{keywordid}", name="refnotrack_info")
     */
    public function NoTrackIncominglink($turlid, $prospectid, $spageid, $keywordid, Request $request)
    {
        $turl = $this->getDoctrine()->getRepository(TrackingUrls::class)->find($turlid);
        $prospect = $this->getDoctrine()->getRepository(Prospects::class)->find($prospectid);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($prospect->getSiteId());

        // Do not need prospect or keyword
        
                
        $page = $this->getDoctrine()->getRepository(Sitepages::class)->find($spageid);
        $getpage = $page->getUrl();
        // This return redirect allows referer to follow : yay
        //var_dump($getpage);
        return $this->redirect($getpage);
    }

}