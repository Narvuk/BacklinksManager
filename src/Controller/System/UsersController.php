<?php

namespace App\Controller\System;

use App\Repository\System\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
use App\Form\System\Users\UserType;
use App\Form\System\Users\EditUserType;

class UsersController extends AbstractController
{
    /**
     * @Route("/system/users", name="system_users")
     */
    public function Index(Request $request)
    {
        $username = "";
        $users = $this->getDoctrine()
            ->getRepository(Users::class)
            ->loadUserByUsername($username);

        return $this->render('system/users/index.html.twig',
            [
                'users' => $users,
            ]
        );
    }

    /**
     * @Route("/system/users/view/{id}", name="system_users_view")
     */
    public function UsersView($id, Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository(Users::class)
            ->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No Account found for this id ' . $id
            );
        }

        return $this->render('system/users/view.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    /**
     * @Route("/system/users/edit/{id}", name="system_users_edit")
     */
    public function UsersEdit($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(Users::class)->find($id);
        $form = $this->createForm(EditUserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            // 4) save the account!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message

            return $this->redirectToRoute('system_users');

        }

        return $this->render('system/users/edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/system/users/new", name="system_users_add")
     */
    public function UsersAdd(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form
        $user = new Users();
        $form = $this->createForm(UserType::class, $user);

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

            return $this->redirectToRoute('system_users');
        }

        return $this->render('system/users/add.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}