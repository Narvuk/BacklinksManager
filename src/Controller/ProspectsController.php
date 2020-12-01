<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Sites;
use App\Entity\Backlinks;
use App\Entity\Prospects;
use App\Entity\Sitepages;
use App\Entity\Linktracking\TrackingCampaigns;
use App\Form\Linktracking\AddTrackingCampaignType;
use App\Form\Sites\AddBacklinkType;
use App\Form\Sites\AddPageType;
use App\Form\Sites\AddKeywordType;
use App\Form\Sites\AddProspectType;
use App\Form\Prospects\EditProspectType;
use App\Entity\Notes\ProspectsNotes;
use App\Form\Prospects\AddNoteType;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\SystemSettings;

class ProspectsController extends AbstractController
{

    /**
     * @Route("/prospect", name="prospect_noinfo")
     */
    public function ProspectNoInfo(Request $request)
    {
        return $this->redirectToRoute('core');
    }

    /**
     * @Route("/prospect/{id}", name="prospect_view")
     */
    public function ProspectView($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }


        $prospect = $this->getDoctrine()->getRepository(Prospects::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($prospect->getSiteId());

        //Repos
        $blrepo = $this->getDoctrine()->getRepository(Backlinks::class);
        $trackcamprepo = $this->getDoctrine()->getRepository(TrackingCampaigns::class);
        $pnotesrepo = $this->getDoctrine()->getRepository(ProspectsNotes::class);

        //count stats
        $bcount = count($blrepo->findBy(['prospectid' => $prospect->getId()], ['id' => 'DESC']));
        $tcampcount = count($trackcamprepo->findBy(['prospectid' => $prospect->getId()], ['id' => 'DESC']));

        // Get Notes
        $pnotestasks = $pnotesrepo->findBy(['prospectid' => $prospect->getId(), 'status' => 'Unread', 'type' => 'Task'], ['id' => 'DESC']);
        $pnotesissues = $pnotesrepo->findBy(['prospectid' => $prospect->getId(), 'status' => 'Unread', 'type' => 'Issue'], ['id' => 'DESC']);

        $tcampaigns = $this->getDoctrine()->getRepository(TrackingCampaigns::class)->findBy(['prospectid' => $prospect->getId()], ['id' => 'DESC'], $limit = 5);

        // 1) build the form
        $addtcampaign = new TrackingCampaigns();
        $form = $this->createForm(AddTrackingCampaignType::class, $addtcampaign);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 4) save the site!
            $entityManager = $this->getDoctrine()->getManager();

            $addtcampaign->setSiteId($prospect->getSiteId());
            $addtcampaign->setProspectId($prospect->getId());
            $addtcampaign->setTotalHits('0');
            $addtcampaign->setStatus('New');
            $addtcampaign->setCreated(new \DateTime());

            $entityManager->persist($addtcampaign);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('prospect_view', ['id' => $id]);
        }


        return $this->render('prospects/view.html.twig',
            [
                'site' => $site,
                'prospect' => $prospect,
                'pnotestasks' => $pnotestasks,
                'pnotesissues' => $pnotesissues,
                'bcount' => $bcount,
                'tcampcount' => $tcampcount,
                'tcampaigns' => $tcampaigns,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/prospect/{id}/edit", name="prospect_edit")
     */
    public function ProspectEdit($id, Request $request)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        $prospect = $this->getDoctrine()->getRepository(Prospects::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($prospect->getSiteId());

        // 1) build the form
        $form = $this->createForm(EditProspectType::class, $prospect);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 4) save the site!
            $entityManager = $this->getDoctrine()->getManager();

           
            $prospect->setUpdated(new \DateTime());

            $entityManager->persist($prospect);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('prospect_view', ['id' => $id]);
        }

        return $this->render('prospects/edit.html.twig',
            [
                'site' => $site,
                'prospect' => $prospect,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/prospect/{id}/tcampaigns", name="prospect_tcampaigns_view")
     */
    public function ProspectTrackingCampaigns($id, Request $request, SystemSettings $systemsettings)
    {

        // Repos
        $tcampaigns = $this->getDoctrine()->getRepository(TrackingCampaigns::class);

        $prospect = $this->getDoctrine()->getRepository(Prospects::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($prospect->getSiteId());

        // Paginition
        $page = isset($_GET['page']) ? $_GET['page'] : "1";
        $limit = $systemsettings->getMaxPerPage();
        $countmax = count($tcampaigns->findBy(['prospectid' => $prospect->getId()], ['id' => 'DESC']));
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
            $tcampaigns = $tcampaigns->findBy(['prospectid' => $prospect->getId()], ['id' => 'DESC'], $limit, $offset);
        } 

        // 1) build the form
        $addtcampaign = new TrackingCampaigns();
        $form = $this->createForm(AddTrackingCampaignType::class, $addtcampaign);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 4) save the site!
            $entityManager = $this->getDoctrine()->getManager();

            $addtcampaign->setSiteId($prospect->getSiteId());
            $addtcampaign->setProspectId($prospect->getId());
            $addtcampaign->setTotalHits('0');
            $addtcampaign->setStatus('New');
            $addtcampaign->setCreated(new \DateTime());

            $entityManager->persist($addtcampaign);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('prospect_tcampaigns_view', ['id' => $id]);
        }

        return $this->render('prospects/tcampaigns.html.twig',
            [
                'site' => $site,
                'prospect' => $prospect,
                'tcampaigns' => $tcampaigns,
                'currentpage' => $currentpage,
                'previouspage' => $previouspage,
                'nextpage' => $nextpage,
                'maxpages' => $maxpages,
                'form' => $form->createView(),
            ]
        );
    }


    /**
     * @Route("/prospect/{id}/backlinks", name="prospect_backlinks_view")
     */
    public function ProspectBacklinksView($id, Request $request, SystemSettings $systemsettings)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        // Repos
        $backlinks = $this->getDoctrine()->getRepository(Backlinks::class);

        $prospect = $this->getDoctrine()->getRepository(Prospects::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($prospect->getSiteId());

        // Paginition
        $page = isset($_GET['page']) ? $_GET['page'] : "1";
        $limit = $systemsettings->getMaxPerPage();
        $countmax = count($backlinks->findBy(['prospectid' => $prospect->getId()], ['id' => 'DESC']));
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
            $backlinks = $backlinks->findBy(['prospectid' => $prospect->getId()], ['id' => 'DESC'], $limit, $offset);
        } 

        // 1) build the form
        $addbacklink = new Backlinks();
        $form = $this->createForm(AddBacklinkType::class, $addbacklink);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 4) save the site!
            $entityManager = $this->getDoctrine()->getManager();

            $getdomain = parse_url($form->get('Backlink')->getData());
            $formatdomain = $getdomain['host'];

            $addbacklink->setSiteId($prospect->getSiteId());
            $addbacklink->setProspectId($prospect->getId());
            $addbacklink->setDomain($formatdomain);
            $addbacklink->setStatus('New');
            $addbacklink->setCreated(new \DateTime());

            $entityManager->persist($addbacklink);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('prospect_backlinks_view', ['id' => $id]);
        }

        return $this->render('prospects/backlinks.html.twig',
            [
                'site' => $site,
                'prospect' => $prospect,
                'backlinks' => $backlinks,
                'currentpage' => $currentpage,
                'previouspage' => $previouspage,
                'nextpage' => $nextpage,
                'maxpages' => $maxpages,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/prospect/{id}/notes", name="prospect_notes")
     */
    public function ProspectNotes($id, Request $request, SystemSettings $systemsettings)
    {
        if ($id == NULL){
            return $this->redirectToRoute('core');
        }

        // Repos
        $pnotes = $this->getDoctrine()->getRepository(ProspectsNotes::class);

        $prospect = $this->getDoctrine()->getRepository(Prospects::class)->find($id);
        $site = $this->getDoctrine()->getRepository(Sites::class)->find($prospect->getSiteId());

        // Paginition
        $page = isset($_GET['page']) ? $_GET['page'] : "1";
        $limit = $systemsettings->getMaxPerPage();
        $countmax = count($pnotes->findBy(['prospectid' => $prospect->getId()], ['id' => 'DESC']));
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
            $pnotes = $pnotes->findBy(['prospectid' => $prospect->getId()], ['id' => 'DESC'], $limit, $offset);
        } 

        // 1) build the form
        $addnote = new ProspectsNotes();
        $form = $this->createForm(AddNoteType::class, $addnote);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 4) save the site!
            $entityManager = $this->getDoctrine()->getManager();

            $addnote->setSiteId($prospect->getSiteId());
            $addnote->setProspectId($id);
            $addnote->setStatus('Unread');
            $addnote->setCreated(new \DateTime());

            $entityManager->persist($addnote);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('prospect_notes', ['id' => $id]);
        }

        return $this->render('prospects/notes/list.html.twig',
            [
                'site' => $site,
                'prospect' => $prospect,
                'pnotes' => $pnotes,
                'currentpage' => $currentpage,
                'previouspage' => $previouspage,
                'nextpage' => $nextpage,
                'maxpages' => $maxpages,
                'form' => $form->createView(),
            ]
        );
    } 

    /**
     * @Route("/prospect/{id}/updatestatus/{status}", name="prospect_updatestatus")
     */
    public function ProspectUpdateStatus($id, $status, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

            $prospect = $this->getDoctrine()->getRepository(Prospects::class)->find($id);
            $site = $this->getDoctrine()->getRepository(Sites::class)->find($prospect->getSiteId());


            $entityManager = $this->getDoctrine()->getManager();

            $prospect->setStatus($status);

            $prospect->setUpdated(new \DateTime());
            $entityManager->persist($prospect);
            $entityManager->flush();

            $getstatus = $prospect->getStatus();
            
                $temp = array(
                'statuschange' => $getstatus, 
                );   
                $jsonData = $temp;  
         
            return new JsonResponse($jsonData);
        }
    }

}