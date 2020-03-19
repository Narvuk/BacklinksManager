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


class UpdatesController extends AbstractController
{
    /**
     * @Route("/update/system", name="update_system")
     */
    public function MainSystemUpdate(KernelInterface $kernel)
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'doctrine:schema:update --force',

        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);

        return $this->redirectToRoute('update_clearcache');
    }

    /**
     * @Route("/update/clearcache", name="update_clearcache")
     */
    public function UpdateClearCache(KernelInterface $kernel)
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'cache:clear',

        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);

        return $this->redirectToRoute('core');
    }
}