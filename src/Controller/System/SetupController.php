<?PHP
namespace App\Controller\System;

use App\Service\DatabaseInstallData;
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
use App\Entity\System\DataSettings;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Role\Role;


class SetupController extends AbstractController
{
    /**
     * @Route("/setup", name="setup")
     */
    public function Index()
    {

        // lock setup if database exists - If not run install
        try{
            $repository = $this->getDoctrine()->getRepository(Users::class);
            $user = $repository->findAll();
            if ( count($user) > 0 ){
                return $this->redirectToRoute('login');
            }
        }
        catch(\Exception $e){
            return $this->render('system/setup/step0.html.twig',
                [
 
                ]
            );
        }

        return $this->render('system/setup/step0.html.twig',
            [

            ]
        );
    }

    /**
     * @Route("/setup/step1dbcheck", name="setup_step1dbcheck")
     */
    public function Step1dbcheck()
    {
        // Check To See if connection exists - If error continue setup
        try{
            $em = $this->getDoctrine()->getManager()->getConnection()->connect();
            if (isset($em)){
                return $this->redirectToRoute('setup_step2');
            }
        }
        catch(\Exception $e){
            return $this->redirectToRoute('setup_step1');
        }
    }

    /**
     * @Route("/setup/step1", name="setup_step1")
     */
    public function Step1(Request $request)
    {

        // lock setup if database exists - If not run install
        try{
            $repository = $this->getDoctrine()->getRepository(Users::class);
            $user = $repository->findAll();
            if ( count($user) > 0 ){
                return $this->redirectToRoute('login');
            }
        }
        catch(\Exception $e){
        }

        $form = $this->createForm(DbdetailsType::class);
        $form->handleRequest($request);

        // $this->addFlash('info', 'Some useful info');

        if ($form->isSubmitted() && $form->isValid()) {
            $DBFormData = $form->getData();

            $DBuser = $DBFormData['dbuser'];
            $DBpass = $DBFormData['dbuserpass'];
            $DBhost = $DBFormData['dbhost'];
            $DBport = $DBFormData['dbport'];
            $DBname = $DBFormData['dbname'];

            $fileSystem = new Filesystem();
            $fileSystem->appendToFile('../.env', "\nAPP_ENV=prod");
            $fileSystem->appendToFile('../.env', "\nDATABASE_URL=mysql://$DBuser:$DBpass@$DBhost:$DBport/$DBname");

            return $this->redirectToRoute('setup_step2');


        }

        return $this->render('system/setup/step1.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/setup/step2", name="setup_step2")
     */
    public function Step2()
    {

        // lock setup if database exists - If not run install
        try{
            $repository = $this->getDoctrine()->getRepository(Users::class);
            $user = $repository->findAll();
            if ( count($user) > 0 ){
                return $this->redirectToRoute('login');
            }
        }
        catch(\Exception $e){
        }

        return $this->render('system/setup/step2.html.twig',
            [
                
            ]
        );
    }

    /**
     * @Route("/setup/step2a", name="setup_step2a")
     */
    public function Step2a(KernelInterface $kernel)
    {

        // lock setup if database exists - If not run install
        try{
            $repository = $this->getDoctrine()->getRepository(Users::class);
            $user = $repository->findAll();
            if ( count($user) > 0 ){
                return $this->redirectToRoute('login');
            }
        }
        catch(\Exception $e){
        }

        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'doctrine:schema:create',

        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);

        return $this->redirectToRoute('setup_step2b');
    }

    /**
     * @Route("/setup/step2b", name="setup_step2b")
     */
    public function Step2b(DatabaseInstallData $databaseinstalldata)
    {

        // lock setup if database exists - If not run install
        try{
            $repository = $this->getDoctrine()->getRepository(Users::class);
            $user = $repository->findAll();
            if ( count($user) > 0 ){
                return $this->redirectToRoute('login');
            }
        }
        catch(\Exception $e){
        }

        $entityManager = $this->getDoctrine()->getManager();


        $datasetting = new DataSettings();
        $datasetting->setMaxPageRows('20');

        $entityManager->persist($datasetting);
        
        $entityManager->flush();

        /*
         Install Data
        */
        $databaseinstalldata->SettingsData();

        return $this->redirectToRoute('setup_step3');
    }

    /**
     * @Route("/setup/step3", name="setup_step3")
     */
    public function Step3()
    {

        // lock setup if database exists - If not run install
        try{
            $repository = $this->getDoctrine()->getRepository(Users::class);
            $user = $repository->findAll();
            if ( count($user) > 0 ){
                return $this->redirectToRoute('login');
            }
        }
        catch(\Exception $e){
        }

        return $this->render('system/setup/step3.html.twig',
            [
                
            ]
        );
    }

    /**
     * @Route("/setup/step4", name="setup_step4")
     */
    public function Step4(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        // lock setup if database exists - If not run install
        try{
            $repository = $this->getDoctrine()->getRepository(Users::class);
            $user = $repository->findAll();
            if ( count($user) > 0 ){
                return $this->redirectToRoute('login');
            }
        }
        catch(\Exception $e){
        }

        // 1) build the form
        $user = new Users();
        $form = $this->createForm(AdmindetailsType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('setup_step5');
        }

        return $this->render('system/setup/step4.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/setup/step5", name="setup_step5")
     */
    public function Step5()
    {
        $fileSystem = new Filesystem();
        $fileSystem->appendToFile('../UPDATELOCK', "\n Update is locked");

        $theme = 'core';
        return $this->render('system/setup/step5.html.twig',
            [
                
            ]
        );
    }


}
