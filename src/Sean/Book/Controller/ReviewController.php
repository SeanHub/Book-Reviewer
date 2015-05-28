<?php

namespace Sean\Book\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Sean\Book\Forms\Book\ReviewType;

class ReviewController extends Controller
{
    /**
     * Add Review Controller
     * @param  object Request $request HTTP Request
     * @return object Symfony Response
     */
    public function addAction(Request $request, $id)
    {
        $book_service = $this->get("book_service");
        $review_service = $this->get("review_service");
        
        $hasReviewed = false;
        if($this->getUser())
        {
            $hasReviewed = (bool)$review_service->getReviewsByUserAndBookId($this->getUser()->getId(), $id)->getResult();
        };
        
        if($hasReviewed)
        {
            return new RedirectResponse($this->generateUrl('book_view', array('id' => $id)));
        };
        
        $book = $book_service->getBookById($id);
        $newReview = $review_service->getNewReview();
        $newReview->setBook($book);
        $newReview->setReviewer($this->getUser());
        $form = $this->createForm(new ReviewType(), $newReview);
        $form->handleRequest($request);
        
        if ($form->isValid())
        {
            $review_service->storeReview($form->getData());
            return new RedirectResponse($this->generateUrl('book_view', array('id' => $id)));
        };
        
        return $this->render('BookBundle:Review:add.html.twig', array('form' => $form->createView(), 'book' => $book));
    }
    
    /**
     * Edit Review Controller
     * @param  object Request $request HTTP Request
     * @return object Symfony Response
     */
    public function editAction(Request $request, $id)
    {
        $book_service = $this->get("book_service");
        $review_service = $this->get("review_service");
        
        $userReview = $this->getDoctrine()->getRepository('Sean\Book\Entity\Review');
        $userReview_query = $review_service->getReviewsByUserAndBookId($this->getUser()->getId(), $id);
        
        if(!$userReview_query->getResult())
        {
            return new RedirectResponse($this->generateUrl('book_view', array('id' => $id)));
        }
        
        $userReview_query = $userReview_query->getSingleResult();
        
        $book = $book_service->getBookById($id);
        $form = $this->createForm(new ReviewType(), $userReview_query);
        $form->handleRequest($request);
        
        if ($form->isValid())
        {
            $review_service->updateReview($form->getData());
            return new RedirectResponse($this->generateUrl('book_view', array('id' => $id)));
        };
        
        return $this->render('BookBundle:Review:edit.html.twig', array('form' => $form->createView(), 'book' => $book, 'review' => $userReview_query));
    }
    
    /**
     * Delete Review Controller
     * @param  object Request $request HTTP Request
     * @return object Symfony Response
     */
    public function deleteAction(Request $request, $id)
    {
        $book_service = $this->get("book_service");
        $review_service = $this->get("review_service");
        
        $book = $book_service->getBookById($id);
        $review_service->deleteReviewByUserAndBookId($this->getUser()->getId(), $id);
        return new RedirectResponse($this->generateUrl('book_view', array('id' => $id)));
    }
}