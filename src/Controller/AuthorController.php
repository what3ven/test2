<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\AuthorType;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AuthorController extends AbstractController
{
    #[Route("/db/authors", name: 'book_index')]
    public function index(AuthorRepository $authorRepository, BookRepository $bookRepository): Response
    {
        $author = new Author();
        $formAuthor= $this->createForm(AuthorType::class, $author);

        $book = new Book();
        $formBook = $this->createForm(BookType::class, $book);

        return $this->render('Author/authors.html.twig', [
            'books' => $bookRepository->findAll(),
            'authors' => $authorRepository->findAll(),
            'author' => $author,
            'formAuthor' => $formAuthor->createView(),
            'book' => $book,
            'formBook' => $formBook->createView(),
        ]);
    }

    #[Route("/db/createauthor", name:"addAuthor")]
    public function new(Author $author = null, EntityManagerInterface $manager, Request $request): Response
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
        return $this->render("author/addauthor.html.twig", [
            'formAuthor' => $form->createView(),
            'editMode' => $author-> getId() != null
        ]);
    }

    #[Route("/db/editauthor/{id}", name:"author_edit")]
    public function edit(EntityManagerInterface $manager, Request $request, Author $author): Response
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

    /**
     * @Route("/db/delete/{id}", name="delete_delete", methods={"POST"})
     */
    #[Route("/db/delete/{id}", name:"author_delete")]
    public function delete(EntityManagerInterface $manager, Request $request, Author $author): Response
    {
        if ($this->isCsrfTokenValid('delete'.$author->getId(), $request->request->get('_token'))) {
            $manager->remove($author);
            $manager->flush();
        }

        return $this->redirectToRoute('author_index', []);
    }

}
