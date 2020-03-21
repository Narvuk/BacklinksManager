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
use ZipArchive;


class UpdatesController extends AbstractController
{

    /**
     * @Route("/updates", name="updates_index")
     */
    public function UpdateSystemIndex(KernelInterface $kernel, Request $request)
    {
        $fileSystem = new Filesystem();
        $isfile = $fileSystem->exists('../UPDATELOCK');

        if ($isfile === False){

        }else{
            return $this->redirectToRoute('core');
        }
        return $this->render('system/updates/index.html.twig',
            [

            ]
        );

    }

    /**
     * @Route("/updates/databaseupdate", name="updates_databaseupdate")
     */
    public function MainUpdateDatabase(KernelInterface $kernel, Request $request)
    {
        if ($request->isXmlHttpRequest()) { 
           
            $fileSystem = new Filesystem();
            $isfile = $fileSystem->exists('../UPDATELOCK');

            if ($isfile === False){
                $fileSystem->appendToFile('../.env.local', "\nAPP_ENV=dev");

                $application = new Application($kernel);
                $application->setAutoExit(false);

                $input = new ArrayInput([
                    'command' => 'doctrine:schema:update',

                ]);

                $output = new BufferedOutput();
                $application->run($input, $output);

                $fileSystem->remove('../.env.local');

                $completed = 'Database Updated Successfully';
                $temp = array(
                    'updatecomplete' => $completed, 
                );   
                $jsonData = $temp;  
                
                $fileSystem->appendToFile('../UPDATELOCK', "\n Update is locked");

                return new JsonResponse($jsonData);
            }else{
                return $this->redirectToRoute('core');
            }

        }

        //var_dump($output);
        //return $this->redirectToRoute('core');
    }

    /**
     * @Route("/updates/clearcache", name="updates_clearcache")
     */
    public function UpdatesClearCache(KernelInterface $kernel, Request $request)
    {

        if ($request->isXmlHttpRequest()) { 

            $application = new Application($kernel);
            $application->setAutoExit(false);

            $input = new ArrayInput([
                'command' => 'cache:clear',

            ]);
            
            $output = new BufferedOutput();
            $application->run($input, $output);

            $completed = 'Cache Cleared Successfully';
            $temp = array(
                'cachecleared' => $completed, 
            );   
            $jsonData = $temp;  
        
            return new JsonResponse($jsonData);

        }

        //var_dump($output);
        //return $this->redirectToRoute('core');
    }

    /**
     * @Route("/updates/download/latest", name="updates_download_latest")
     */
    public function DownloadLatestVersion(KernelInterface $kernel, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $fileSystem = new Filesystem();
            $url = "https://stormdevelopers.com/test/test.zip";
            $zipFile = "../test.zip"; // Local Zip File Path
            $zipResource = fopen($zipFile, "w");
            // Get The Zip File From Server
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
            curl_setopt($ch, CURLOPT_FILE, $zipResource);
            $page = curl_exec($ch);
            if(!$page) {
            echo "Error :- ".curl_error($ch);
            }
            curl_close($ch);

            /* Open the Zip file */
            $zip = new ZipArchive;
            $extractPath = "../";
            if($zip->open($zipFile) != "true"){
            echo "Error :- Unable to open the Zip File";
            } 
            /* Extract Zip File */
            $zip->extractTo($extractPath);
            $zip->close();
            $fileSystem->remove('../test.zip');
        }

        $completed = 'Updated System Files';
        $temp = array(
            'filesupdated' => $completed, 
        );   
        $jsonData = $temp;  
        
        return new JsonResponse($jsonData);

    }


    /**
     * @Route("/updates/complete", name="updates_complete")
     */
    public function UpdateSystemComplete(KernelInterface $kernel, Request $request)
    {
        

    }
    
}