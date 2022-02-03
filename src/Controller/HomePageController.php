<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Books;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;
use App\Entity\History;
use PhpParser\Node\Expr\Cast\Object_;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $books = $entityManager->getRepository(Books::class)->findAll();

        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
            'books' => $books,
        ]);
    }


    #[Route('/create', name: 'book_create')]
    public function create(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $book = new Books();
        $book->setTitle("Titre du Livre");
        $book->setAuthor("Nom de l'auteur");
        // $book->setDate(new DateTime("now"));
        $book->setDescription("Résumé");
        $book->setPublisher("Maison d'édition");
        $book->setCategory("Catégorie");
        $book->setStatus(true);
        $book->setLoanDate(null);
        $book->setDueDate(null);
        $book->setImageName(null);
        $book->setImageFile(null);
        $book->setLastUser(null);

        $form = $this->createFormBuilder($book)
            ->add('title', TextType::class, ["attr" => ["class" => "form-control"]])
            ->add('author', TextType::class, ["attr" => ["class" => "form-control"]])
            // ->add('author', CollectionType::class, [
            //     // each entry in the array will be an "email" field
            //     // 'entry_type' => EmailType::class,
            //     // these options are passed to each "email" type
            //     'entry_options' => [
            //         'attr' => ['class' => 'form-control'],
            //     ],
            // ])

            ->add('description', TextareaType::class, ["attr" => ["class" => "form-control"]])
            ->add('publisher', TextType::class, ["attr" => ["class" => "form-control"]])
            ->add('category', TextType::class, ["attr" => ["class" => "form-control"]])
            ->add('cover', FileType::class, [
                "mapped" => false,
                "required" => false,
                "constraints" => [new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => ['image/png', 'image/jpeg'],
                    'mimeTypesMessage' => 'Veuillez choisir une image au format jpeg ou png',])],])
            ->add('save', SubmitType::class, ["label" => "Envoyer", "attr" => ["class" => "btn btn-primary"]])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();
            $bookCover = $form->get('cover')->getData();

            if ($bookCover) {
                $originalFilename = pathinfo($bookCover->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = '/library/book_cover/'.$safeFilename.'-'.uniqid().'.'.$bookCover->guessExtension();
    
                // Move the file to the directory where brochures are stored
                try {
                    $bookCover->move(
                        $this->getParameter('book_cover_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
    
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $book->setImageName($newFilename);
            }

            // $book = $form->getData();
            $book->setDate(new \DateTime("now"));

            $em = $doctrine->getManager();
            $em->persist($book);
            $em->flush();
            $this->addFlash('success', 'Votre livre a bien été ajouté');


            return $this->redirectToRoute('home');
        }

        return $this->renderForm('home_page/create.html.twig', [
            'form' => $form,
        ]);
    }



    #[Route('/edit/{id}', name: 'book_edit')]
    public function edit(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Books::class)->find($id);




        $form = $this->createFormBuilder($book)
            ->add('title', TextType::class, ["attr" => ["class" => "form-control"]])
            ->add('author', TextType::class, ["attr" => ["class" => "form-control"]])
            ->add('description', TextareaType::class, ["attr" => ["class" => "form-control"]])
            ->add('publisher', TextType::class, ["attr" => ["class" => "form-control"]])
            ->add('category', TextType::class, ["attr" => ["class" => "form-control"]])
            // ->add('last_user', EntityType::class, ["class"=>User::class, "attr" => ["class" => "form-control"]])

            ->add('last_user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
                'attr' => ['class' => 'form-control'],
                'label' => 'Utilisateur',
                'placeholder' => 'Aucun',
                'required' => false
            ])


            ->add('save', SubmitType::class, ["label" => "Envoyer", "attr" => ["class" => "btn btn-primary"]])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Le livre a bien été modifié');
            $edit = $form->getData();

            if (is_null($edit->getLastUser())) {
                $book->setStatus(1);
            } else {
                $book->setStatus(0);
                $book->setLoanDate(new \DateTime());
                $book->setDueDate(new \DateTime('+30days'));
                $userId = $edit->getLastUser();
                $user = $entityManager->getRepository(User::class)->find($userId);

                $history = new History;
                $history->setUser($user);
                $history->setBook($book);
                $entityManager->persist($history);
                $entityManager->flush();
            }

            $book->setTitle($edit->getTitle());
            $book->setAuthor($edit->getAuthor());
            $book->setDescription($edit->getDescription());
            $book->setPublisher($edit->getPublisher());
            $book->setCategory($edit->getCategory());
            $book->setLoanDate($edit->getLoanDate());
            $book->setDueDate($edit->getDueDate());
            $book->setImageName($edit->getImageName());
            $book->setImageFile($edit->getImageFile());
            $book->setLastUser($edit->getLastUser());

      
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->renderForm('home_page/edit.html.twig', [
            'form' => $form,
            'id' => $id,
            'status' => $book->getStatus()
        ]);
    }


    #[Route('/delete/{id}', name: 'delete_book')]
    public function delete(Int $id, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $book = $em->getRepository(Books::class)->find($id);
        $history = $em->getRepository(History::class)->findOneBy(["book"=>$id]);

        if(!is_null($history)){
            $book->removeBookHistory($history);
        }
        
        $em->remove($book);
        $em->flush();


        $this->addFlash('success', 'Le livre a bien été supprimé');

        return $this->redirectToRoute('home');
    }


    #[Route('/return/{id}', name: 'book_return')]
    public function return(ManagerRegistry $doctrine, int $id): Response

    {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Books::class)->find($id);

        $book->setLastUser(null);
        $book->setLoanDate(null);
        $book->setDueDate(null);
        $book->setStatus(1);


        $entityManager->persist($book);
        $entityManager->flush();
        return $this->redirectToRoute('home');
    }
}
