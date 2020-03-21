<?PHP
namespace App\Controller\System;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use App\Form\System\Setup\DbdetailsType;
use App\Form\System\Setup\AdmindetailsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Entity\System\Users;
use App\Entity\System\Settings;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\HttpFoundation\JsonResponse;


class ToolsController extends AbstractController
{

    /**
     * @Route("/system/tools", name="system_tools")
     */
    public function SystemToolsIndex(KernelInterface $kernel, Request $request)
    {

        $fileSystem = new Filesystem();
        $isupdatelock = $fileSystem->exists('../UPDATELOCK');
        if ($isupdatelock === True){
            $updatelock = 'Locked';
        }else{
            $updatelock = 'Unlocked';
        }

        $islivemode = $fileSystem->exists('../.env.local');
        if ($islivemode === True){
            $sysmode = 'Developer Mode';
        }else{
            $sysmode = 'Live Mode';
        }
        

        return $this->render('system/tools/index.html.twig',
            [
                'updatelock' => $updatelock,
                'sysmode' => $sysmode,
            ]
        );

    }


    /**
     * @Route("/system/tools/updatelock", name="system_tools_updatelock")
     */
    public function SystemToolsUpdateLock(KernelInterface $kernel, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 
           
            $fileSystem = new Filesystem();
            $isfile = $fileSystem->exists('../UPDATELOCK');

            if ($isfile === False){
                $fileSystem->appendToFile('../UPDATELOCK', "\n Update is locked");

                $completed = 'Locked';
                $temp = array(
                'locked' => $completed, 
                 );   
                $jsonData = $temp;  

                return new JsonResponse($jsonData);
            } else {

                $completed = 'Already Locked';
                $temp = array(
                    'locked' => $completed, 
                );   
                $jsonData = $temp;  

                return new JsonResponse($jsonData);
            }
        }
        
    }

    /**
     * @Route("/system/tools/updateunlock", name="system_tools_updateunlock")
     */
    public function SystemToolsUpdateUnlock(KernelInterface $kernel, Request $request)
    {
        if ($request->isXmlHttpRequest()) { 
           
            $fileSystem = new Filesystem();
            $isfile = $fileSystem->exists('../UPDATELOCK');

            if ($isfile === TRUE){
                $fileSystem->remove('../UPDATELOCK');
                
                $completed = 'Unlocked';
                $temp = array(
                'unlocked' => $completed, 
                );   
                $jsonData = $temp;  

                return new JsonResponse($jsonData);
            } else {

                $completed = 'Already Unlocked';
                $temp = array(
                    'unlocked' => $completed, 
                );   
                $jsonData = $temp;  

                return new JsonResponse($jsonData);
            }
        }

    }

    /**
     * @Route("/system/tools/devmode", name="system_tools_devmode")
     */
    public function SystemToolsDevMode(KernelInterface $kernel, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 
           
            $fileSystem = new Filesystem();
            $isfile = $fileSystem->exists('../.env.local');

            if ($isfile === False){
                $fileSystem->appendToFile('../.env.local', "\nAPP_ENV=dev");

                $completed = 'Developer Mode';
                $temp = array(
                'devmode' => $completed, 
                 );   
                $jsonData = $temp;  

                return new JsonResponse($jsonData);
            } else {

                $completed = 'Developer Mode';
                $temp = array(
                    'devmode' => $completed, 
                );   
                $jsonData = $temp;  

                return new JsonResponse($jsonData);
            }
        }
        
    }

    /**
     * @Route("/system/tools/livemode", name="system_tools_livemode")
     */
    public function SystemToolsLiveMode(KernelInterface $kernel, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 
           
            $fileSystem = new Filesystem();
            $isfile = $fileSystem->exists('../.env.local');

            if ($isfile === True){
                $fileSystem->remove('../.env.local');

                $completed = 'Live Mode';
                $temp = array(
                'livemode' => $completed, 
                 );   
                $jsonData = $temp;  

                return new JsonResponse($jsonData);
            } else {

                $completed = 'Live Mode';
                $temp = array(
                    'livemode' => $completed, 
                );   
                $jsonData = $temp;  

                return new JsonResponse($jsonData);
            }
        }
        
    }

    
}
