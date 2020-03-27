<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Sites;
use App\Entity\Backlinks;
use App\Entity\Sitepages;
use App\Entity\Keywords;
use App\Entity\Prospects;
use App\Entity\System\DataSettings;
use App\Entity\Linktracking\TrackingUrls;
use App\Entity\Linktracking\TrackingCampaigns;
use App\Entity\Notes\BacklinksNotes;

use App\Form\Backlinks\AddNoteType;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DomCrawler\Crawler;


class BacklinksController extends AbstractController
{

    /**
     * @Route("/backlink", name="backlink_noinfo")
     */
    public function BacklinkNoInfo(Request $request)
    {
        return $this->redirectToRoute('core');
    }

    /**
     * @Route("/backlinks", name="backlinks_noinfo")
     */
    public function BacklinksNoInfo(Request $request)
    {
        return $this->redirectToRoute('core');
    }

    /**
     * @Route("/backlink/{id}", name="backlink_view")
     */
    public function Index($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        // Repos
        $blnotesrepo = $this->getDoctrine()->getRepository(BacklinksNotes::class);
        $turlrepo = $this->getDoctrine()->getRepository(TrackingUrls::class);

        $backlink = $this->getDoctrine()->getRepository(Backlinks::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($backlink->getSiteId());
       // $turl = $turlrepo->find($backlink->getTurlId());

        // Show all & Get Assigned Prospect for Backlink
        $prospects = $this->getDoctrine()->getRepository(Prospects::class)->findBy(['siteid' => $backlink->getSiteId()], ['id' => 'DESC']);
        if ($backlink->getProspectId() != Null){
            $getprospect = $this->getDoctrine()->getRepository(Prospects::class)->find($backlink->getProspectId());
        } else {
            $getprospect = "";
        }

        // Show all & Get Assigned Site Page for Backlink
        $sitepages = $this->getDoctrine()->getRepository(Sitepages::class)->findBy(['siteid' => $backlink->getSiteId()], ['id' => 'DESC']);
        if ($backlink->getSpageId() != Null){
            $spage = $this->getDoctrine()->getRepository(Sitepages::class)->find($backlink->getSpageId());
        } else {
            $spage = "";
        }

        // Show all & Get Assigned Keyword for Backlink
        $keywords = $this->getDoctrine()->getRepository(Keywords::class)->findBy(['siteid' => $backlink->getSiteId()], ['id' => 'DESC']);
        if ($backlink->getKeywordId() != Null){
            $kword = $this->getDoctrine()->getRepository(Keywords::class)->find($backlink->getKeywordId());
        } else {
            $kword = "";
        }

        // Show all & Get Assigned Tracking Url for Backlink
        $tracklinks = $turlrepo->findBy(['siteid' => $backlink->getSiteId()], ['id' => 'DESC']);
        if ($backlink->getTurlId() != Null){
            $turl = $turlrepo->find($backlink->getTurlId());
        } else {
            $turl = "";
        }

        // Get Notes
        $blnotestasks = $blnotesrepo->findBy(['backlinkid' => $backlink->getId(), 'status' => 'Unread', 'type' => 'Task'], ['id' => 'DESC']);
        $blnotesissues = $blnotesrepo->findBy(['backlinkid' => $backlink->getId(), 'status' => 'Unread', 'type' => 'Issue'], ['id' => 'DESC']);

        return $this->render('backlinks/view.html.twig',
            [
                'site' => $site,
                'backlink' => $backlink,
                'prospects' => $prospects,
                'getprospect' => $getprospect, 
                'spages' => $sitepages,
                'spage' => $spage,
                'keywords' => $keywords,
                'kword' => $kword,
                'tracklinks' => $tracklinks,
                'turl' => $turl,
                'blnotestasks' => $blnotestasks,
                'blnotesissues' => $blnotesissues,
            ]
        );
    }

    /**
     * @Route("/backlink/{id}/edit", name="backlink_edit")
     */
    public function BacklinkEdit($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($id);
        $backlink = $this->getDoctrine()->getRepository(Backlinks::class)->find($id);

        return $this->render('backlinks/edit.html.twig',
            [
                'site' => $site,
                'backlink' => $backlink,
            ]
        );
    } 

    /**
     * @Route("/backlink/{id}/notes", name="backlink_notes")
     */
    public function BacklinkNotes($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        // Repos
        $datasettings = $this->getDoctrine()->getRepository(DataSettings::class)->find(1);
        $blnotes = $this->getDoctrine()->getRepository(BacklinksNotes::class);

        $backlink = $this->getDoctrine()->getRepository(Backlinks::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($backlink->getSiteId());

        // Paginition
        $page = isset($_GET['page']) ? $_GET['page'] : "1";
        $limit = $datasettings->getMaxPageRows();
        $countmax = count($blnotes->findBy(['backlinkid' => $backlink->getId()], ['id' => 'DESC']));
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
            $blnotes = $blnotes->findBy(['backlinkid' => $backlink->getId()], ['id' => 'DESC'], $limit, $offset);
        } 

        // 1) build the form
        $addnote = new BacklinksNotes();
        $form = $this->createForm(AddNoteType::class, $addnote);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 4) save the site!
            $entityManager = $this->getDoctrine()->getManager();

            $addnote->setSiteId($backlink->getSiteId());
            $addnote->setBacklinkId($id);
            $addnote->setStatus('Unread');
            $addnote->setCreated(new \DateTime());

            $entityManager->persist($addnote);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('backlink_notes', ['id' => $id]);
        }

        return $this->render('backlinks/notes/list.html.twig',
            [
                'site' => $site,
                'backlink' => $backlink,
                'blnotes' => $blnotes,
                'currentpage' => $currentpage,
                'previouspage' => $previouspage,
                'nextpage' => $nextpage,
                'maxpages' => $maxpages,
                'form' => $form->createView(),
            ]
        );
    } 

    /**
     * @Route("/backlink/{id}/updatestatus/{status}", name="backlink_updatestatus")
     */
    public function BacklinksUpdateStatus($id, $status, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

        $backlink = $this->getDoctrine()->getRepository(Backlinks::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($backlink->getSiteId());


        $entityManager = $this->getDoctrine()->getManager();

        $backlink->setStatus($status);

        $backlink->setUpdated(new \DateTime());
        $entityManager->persist($backlink);
        $entityManager->flush();

        $getstatus = $backlink->getStatus();
        
            $temp = array(
               'statuschange' => $getstatus, 
            );   
            $jsonData = $temp;  
         
        return new JsonResponse($jsonData);
        
        }

    }

    /**
     * @Route("/backlink/{id}/assignprospect/{prospectid}", name="backlink_assignprospect")
     */
    public function ProspectAssignPage($id, $prospectid, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

        $backlink = $this->getDoctrine()->getRepository(Backlinks::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($backlink->getSiteId());
        $prospect = $this->getDoctrine()->getRepository(Prospects::class)->find($prospectid);


        $entityManager = $this->getDoctrine()->getManager();

        $backlink->setProspectId($prospectid);

        $backlink->setUpdated(new \DateTime());
        $entityManager->persist($backlink);
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
     * @Route("/backlink/{id}/assignsitepage/{pageid}", name="backlink_assignsite")
     */
    public function BacklinksAssignPage($id, $pageid, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

        $backlink = $this->getDoctrine()->getRepository(Backlinks::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($backlink->getSiteId());
        $spage = $this->getDoctrine()->getRepository(Sitepages::class)->find($pageid);


        $entityManager = $this->getDoctrine()->getManager();

        $backlink->setSpageId($pageid);

        $backlink->setUpdated(new \DateTime());
        $entityManager->persist($backlink);
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
     * @Route("/backlink/{id}/assignkeyword/{keywordid}", name="backlink_assignkeyword")
     */
    public function KeywordAssignPage($id, $keywordid, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

        $backlink = $this->getDoctrine()->getRepository(Backlinks::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($backlink->getSiteId());
        $kword = $this->getDoctrine()->getRepository(Keywords::class)->find($keywordid);


        $entityManager = $this->getDoctrine()->getManager();

        $backlink->setKeywordId($keywordid);

        $backlink->setUpdated(new \DateTime());
        $entityManager->persist($backlink);
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
     * @Route("/backlink/{id}/assigntrackurl/{turlid}", name="backlink_assigntrackurl")
     */
    public function TrackUrlAssignPage($id, $turlid, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

        $backlink = $this->getDoctrine()->getRepository(Backlinks::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($backlink->getSiteId());
        $turl = $this->getDoctrine()->getRepository(TrackingUrls::class)->find($turlid);


        $entityManager = $this->getDoctrine()->getManager();

        $backlink->setTurlId($turlid);

        $backlink->setUpdated(new \DateTime());
        $entityManager->persist($backlink);
        $entityManager->flush();

        $tracklink = $turl->getTlink();
        
            $temp = array(
               'tracklink' => $tracklink, 
            );   
            $jsonData = $temp;  
         
        return new JsonResponse($jsonData);
        
        }

    }

    /**
     * @Route("/backlink/{id}/activechecker", name="backlink_checker")
     */
    public function BacklinkChecker($id, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

            $backlink = $this->getDoctrine()->getRepository(Backlinks::class)->find($id);
            $site = $this->getDoctrine()->getRepository(Sites::class)->find($backlink->getSiteId());
            $trackurl = $this->getDoctrine()->getRepository(TrackingUrls::class)->find($backlink->getTurlId());

            $geturl = $backlink->getBacklink();
            $getpage = $trackurl->getTlink();

            // fetch url
            $ch = curl_init($geturl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, true);


            $content = curl_exec($ch);
            curl_close($ch);

            //new dom
            $crawler = new Crawler($content);

            //search target for link
            //$cfilter = $crawler->filterXPath('//a');
            $attributes = $crawler
            ->filterXpath('//a')
            ->extract(['href'])
            ;
            
            if (in_array($getpage, $attributes))
            {
                $entityManager = $this->getDoctrine()->getManager();

                $backlink->setStatus('Active');
                $backlink->setLastChecked(new \DateTime('Now'));

                $entityManager->persist($backlink);
                $entityManager->flush();

                $isactive = 'Active';

            } else {

                $entityManager = $this->getDoctrine()->getManager();

                $backlink->setStatus('Lost');
                $backlink->setLastChecked(new \DateTime('Now'));

                $entityManager->persist($backlink);
                $entityManager->flush();

                $isactive = 'Lost';
            }

            $getlastchecked = $backlink->getLastChecked();
            $formatlc = $getlastchecked->format('d-m-Y / H:i');
        
            $temp = array(
                'lastchecked' => $formatlc,
               'isactive' => $isactive, 
            );   
            $jsonData = $temp;  
         
            return new JsonResponse($jsonData);
        
        }

    }
    
}