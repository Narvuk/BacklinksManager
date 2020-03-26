<?php

namespace App\Controller\Linktracking;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Sites;
use App\Entity\Backlinks;
use App\Entity\Prospects;
use App\Entity\Sitepages;
use App\Entity\Keywords;
use App\Entity\Linktracking\TrackingUrls;
use App\Entity\Linktracking\TrackingCampaigns;
use App\Form\Linktracking\AddTrackingCampaignType;
use App\Form\Sites\AddBacklinkType;
use App\Form\Sites\AddPageType;
use App\Form\Sites\AddKeywordType;
use App\Form\Sites\AddProspectType;

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

        // Show all & Get Assigned Prospect for Tracking Url
        $prospects = $this->getDoctrine()->getRepository(Prospects::class)->findBy(['siteid' => $turl->getSiteId()], ['id' => 'DESC']);
        if ($turl->getProspectId() != Null){
            $getprospect = $this->getDoctrine()->getRepository(Prospects::class)->find($turl->getProspectId());
        } else {
            $getprospect = "";
        }

        // Show all & Get Assigned Site Page for Tracking Url
        $sitepages = $this->getDoctrine()->getRepository(Sitepages::class)->findBy(['siteid' => $turl->getSiteId()], ['id' => 'DESC']);
        if ($turl->getSpageId() != Null){
            $spage = $this->getDoctrine()->getRepository(Sitepages::class)->find($turl->getSpageId());
        } else {
            $spage = "";
        }

        // Show all & Get Assigned Keyword for Tracking Url
        $keywords = $this->getDoctrine()->getRepository(Keywords::class)->findBy(['siteid' => $turl->getSiteId()], ['id' => 'DESC']);
        if ($turl->getKeywordId() != Null){
            $kword = $this->getDoctrine()->getRepository(Keywords::class)->find($turl->getKeywordId());
        } else {
            $kword = "";
        }

         return $this->render('linktracking/urls/view.html.twig',
            [
                'site' => $site,
                'turl' => $turl,
                'prospects' => $prospects,
                'getprospect' => $getprospect, 
                'spages' => $sitepages,
                'spage' => $spage,
                'keywords' => $keywords,
                'kword' => $kword,
            ]
        );
    }


    /**
     * @Route("/linktrack/{id}/updatestatus/{status}", name="linktrack_updatestatus")
     */
    public function LinktrackUpdateStatus($id, $status, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

        $turl = $this->getDoctrine()->getRepository(TrackingUrls::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($turl->getSiteId());


        $entityManager = $this->getDoctrine()->getManager();

        $turl->setStatus($status);

        $turl->setUpdated(new \DateTime());
        $entityManager->persist($turl);
        $entityManager->flush();

        $getstatus = $turl->getStatus();
        
            $temp = array(
               'statuschange' => $getstatus, 
            );   
            $jsonData = $temp;  
         
        return new JsonResponse($jsonData);
        
        }

    }

    /**
     * @Route("/linktrack/{id}/assignprospect/{prospectid}", name="linktrack_assignprospect")
     */
    public function LinkTrackAssignProspect($id, $prospectid, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

        $turl = $this->getDoctrine()->getRepository(TrackingUrls::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($turl->getSiteId());
        $prospect = $this->getDoctrine()->getRepository(Prospects::class)->find($prospectid);


        $entityManager = $this->getDoctrine()->getManager();

        $turl->setProspectId($prospectid);

        $turl->setUpdated(new \DateTime());
        $entityManager->persist($turl);
        $entityManager->flush();

        $getprospect = $prospect->getName();
        
            $temp = array(
               'getprospect' => $getprospect, 
            );   
            $jsonData = $temp;  
         
        return new JsonResponse($jsonData);
        
        }

    }

    /**
     * @Route("/linktrack/{id}/assignsitepage/{pageid}", name="linktrack_assignsitepage")
     */
    public function LinkTrackAssignSpage($id, $pageid, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

        $turl = $this->getDoctrine()->getRepository(TrackingUrls::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($turl->getSiteId());
        $spage = $this->getDoctrine()->getRepository(Sitepages::class)->find($pageid);


        $entityManager = $this->getDoctrine()->getManager();

        $turl->setSpageId($pageid);

        $turl->setUpdated(new \DateTime());
        $entityManager->persist($turl);
        $entityManager->flush();

        $spageurl = $spage->getUrl();
        
            $temp = array(
               'spageurl' => $spageurl, 
            );   
            $jsonData = $temp;  
         
        return new JsonResponse($jsonData);
        
        }

    }

    /**
     * @Route("/linktrack/{id}/assignkeyword/{keywordid}", name="linktrack_assignkeyword")
     */
    public function LinkTrackAssignKeyword($id, $keywordid, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

        $turl = $this->getDoctrine()->getRepository(TrackingUrls::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($turl->getSiteId());
        $kword = $this->getDoctrine()->getRepository(Keywords::class)->find($keywordid);


        $entityManager = $this->getDoctrine()->getManager();

        $turl->setKeywordId($keywordid);

        $turl->setUpdated(new \DateTime());
        $entityManager->persist($turl);
        $entityManager->flush();

        $getkword = $kword->getKeyword();
        
            $temp = array(
               'kword' => $getkword, 
            );   
            $jsonData = $temp;  
         
        return new JsonResponse($jsonData);
        
        }

    }

    /**
     * @Route("/linktrack/url/{id}/generateurl", name="trackingurl_generate")
     */
    public function TrackUrlGenerate($id, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

            $rootdomain = $request->getSchemeAndHttpHost();
            $tl = '/turl/';
            $dl = '/durl/';
            $turl = $this->getDoctrine()->getRepository(TrackingUrls::class)->find($id);
            $keyword = $this->getDoctrine()->getRepository(Keywords::class)->find($turl->getKeywordId());
            $ktitle = str_replace(' ', '-', $keyword->getKeyword());

            $fturl = $rootdomain . $tl . $turl->getId() . '-' . $ktitle;
            $fdurl = $rootdomain . $dl . $turl->getId() . '-' . $ktitle;

            $entityManager = $this->getDoctrine()->getManager();

            $turl->setTlink($fturl);
            $turl->setDlink($fdurl);
            $turl->setUpdated(new \DateTime());
            $entityManager->persist($turl);
            $entityManager->flush();
            
            if ($turl->getKeywordId() && $turl->getProspectId() && $turl->getSpageId()){
            
                $temp = array(
                    'turl' =>  $fturl,
                    'durl' =>  $fdurl,
                );   
                $jsonData = $temp;  
            } else {
                $temp = array(
                    'turl' => 'Please assign all Items to generate a valid tracking url',
                    'durl' => 'Please assign all Items to generate a valid direct url',
                );   
                $jsonData = $temp;  
            }
            // var_dump($jsonData['turl']);
            return new JsonResponse($jsonData);   
        }
    }
}