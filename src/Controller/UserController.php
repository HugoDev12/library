<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

	#[Route('/adduser', name: 'app_register')]
    public function userRegister(Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine): Response
    {
        $user = new User();
		$user->setEmail('Email de l\'utilisateur');
		$user->setFirstName('Prénom de l\'utilisateur');
		$user->setLastName('Nom de l\'utilisateur');
		$user->setPhoneNumber('Numéro de l\'utilisateur');
		$user->setAddress('Adresse de l\'utilisateur');
		$user->setRoles(['ROLE_USER']);
        $form = $this->createForm(RegistrationFormType::class, $user);

		$form = $this->createFormBuilder($user)
			->add('email', EmailType::class, ["attr" => ["class" => "form-control"]])
			->add('first_name', TextType::class, ["attr" => ["class" => "form-control"]])
			->add('last_name', TextType::class, ["attr" => ["class" => "form-control"]])
			->add('phone_number', TextType::class, ["attr" => ["class" => "form-control"]])
			->add('address', TextType::class, ["attr" => ["class" => "form-control"]])
			->add('save', SubmitType::class, ["label" => "Envoyer", "attr" => ["class" => "btn btn-primary"]])
			->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			$datas = $form->getData();

			$em = $doctrine->getManager();
            $em->persist($datas);
            $em->flush();
            $this->addFlash('success', 'L\'utilisateur a bien été ajouté !');


            return $this->redirectToRoute('user');
        }

        return $this->render('user/addUser.html.twig', [
            'registrationForm'  => $form->createView(),
        ]);

	}
}
