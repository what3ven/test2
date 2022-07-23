<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\AuthorType;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AuthorController extends AbstractController
{

    private AuthorRepository $authorRepository;
    private BookRepository $bookRepository;

    /**
     * @param AuthorRepository $authorRepository
     * @param BookRepository $bookRepository
     */
    public function __construct(AuthorRepository $authorRepository, BookRepository $bookRepository)
    {
        $this->authorRepository = $authorRepository;
        $this->bookRepository = $bookRepository;
    }

    #[Route("/db/authors", name: 'author_index')]
    public function indexAction(): Response
    {
        $author = new Author();
        $formAuthor= $this->createForm(AuthorType::class, $author);

        $book = new Book();
        $formBook = $this->createForm(BookType::class, $book);

        return $this->render('Author/main.html.twig', [
            'books' => $this->bookRepository->findAll(),
            'authors' => $this->authorRepository->findAll(),
            'author' => $author,
            'formAuthor' => $formAuthor->createView(),
            'book' => $book,
            'formBook' => $formBook->createView(),
        ]);
    }

    #[Route("/db/create-author", name:"addAuthor")]
    public function createAction(EntityManagerInterface $manager, Request $request, Author $author = null): Response
    {
        if (! $author) {
            $author = new Author();
        }

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form-> isValid()) {
            $manager->persist($author);
            $manager->flush();
            return $this->redirectToRoute('book_index', []);
        }

        return $this->render('author/addauthor.html.twig', [
            'formAuthor' => $form->createView(),
            'editMode' => $author->getId() !== null
        ]);
    }

    #[Route("/db/editauthor/{id}", name:"author_edit")]
    public function editAction(EntityManagerInterface $manager, Request $request, Author $author): Response
    {
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            return $this->redirectToRoute('author_index', []);
        }

        return $this->renderForm('author/editauthor.html.twig', [
            'author' => $author,
            'form' => $form,
        ]);
    }

    #[Route("/db/delete/{id}", name:"author_delete")]
    public function deleteAction(EntityManagerInterface $manager, Request $request, Author $author): Response
    {
        $manager->remove($author);
        $manager->flush();

        return $this->redirectToRoute('author_index', []);
    }



}
