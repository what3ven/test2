<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookForm;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BookRepository;

class BookController extends AbstractController
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/db/books", name="mainpage")
     */
    public function index(BookRepository $repo_book)
    {
        $books = $repo_book->findBy([], ['title' => 'ASC']);
        return $this->render('', [
            'books' => $books
        ]);
    }

    /**
     * @return Response
     *
     */
    #[Route("/db/createbook")]
    public function create(Book $book = null, EntityManagerInterface $manager, Request $request)
    {
        if (! $book)
        {
            $book = new Book();
        }
        $form = $this->createForm(BookForm::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form-> isValid())
        {
            $manager->persist($book);
            $manager->flush($book);
            return $this->RedirectToRoute('mainpage');
        }

        return $this->render('index.html.twig', [
            'formBook' => $form->createView(),
            'editMode' => $book-> getId() != null
        ]);
    }
}
