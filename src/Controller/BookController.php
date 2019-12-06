<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/", name="book_index", methods={"GET"})
     */
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    /**
     * @Route("/century", name="century")
     */
    public function century(BookRepository $bookRepository): Response
    {
        return $this->render('book/table.html.twig', [
            'tables' => [
                [
                    'title' => 'Before 1900',
                    'books' => $bookRepository->findByCentury(0, 1900)
                ],
                [
                    'title' => '1900 - 2000',
                    'books' => $bookRepository->findByCentury(1901, 2000)
                ],
                [
                    'title' => 'After 2000',
                    'books' => $bookRepository->findByCentury(2001, PHP_INT_MAX)
                ]
            ]
        ]);
    }

    /**
     * @Route("/author", name="author")
     */
    public function author(BookRepository $bookRepository): Response
    {
        $tables = [];

        foreach ($bookRepository->getAuthors() as $author) {
            $tables[] = [
                'title' => $author,
                'books' => $bookRepository->findBy(['author' => $author])
            ];
        }

        return $this->render('book/table.html.twig', [
            'tables' => $tables
        ]);
    }

    /**
     * @Route("/price", name="price")
     */
    public function price(BookRepository $bookRepository): Response
    {
        return $this->render('book/table.html.twig', [
            'tables' => [
                [
                    'title' => 'Sort by price',
                    'books' => $bookRepository->findBy([], ['price' => 'asc'])
                ]
            ]
        ]);
    }

    /**
     * @Route("/years", name="years")
     */
    public function years(BookRepository $bookRepository): Response
    {
        return $this->render('book/table.html.twig', [
            'tables' => [
                [
                    'title' => 'Last 2 years',
                    'books' => $bookRepository->findLast2Years()
                ]
            ]
        ]);
    }

    /**
     * @Route("/new", name="book_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="book_show", methods={"GET"})
     */
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="book_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Book $book): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="book_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Book $book): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('book_index');
    }
}
