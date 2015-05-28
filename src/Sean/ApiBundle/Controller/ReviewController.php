<?php

namespace Sean\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Sean\Book\Forms\Book\ReviewType;

class ReviewController extends FOSRestController
{
    public function deleteReviewAction($slug, $id)
    {
        $review_service = $this->get('review_service');
        $review = $review_service->getReviewByReviewAndBookId($id, $slug);
        if($review)
        {
            if($review[0]->getReviewer()->getId() === $this->getUser()->getId())
            {
                $review_service->deleteReviewById($id);
                return $this->handleView($this->view(), 204);
            }
            else
            {
                throw new HttpException(401, 'you cannot delete someone elses review');
            }
        }
        throw $this->createNotFoundException('no review found');
    }
    
    public function getReviewsAction($slug)
    {
        $review_service = $this->get('review_service');
        $reviews = $review_service->getReviewsByBookId($slug);
        if($reviews)
        {
            return $this->handleView($this->view($reviews), 200);
        }
        else
        {
            return $this->handleView($this->view([]), 200);
        }
    }
    
    public function getReviewAction($slug, $id)
    {
        $review_service = $this->get('review_service');
        $review = $review_service->getReviewByReviewAndBookId($id, $slug);
        if($review)
        {
            return $this->handleView($this->view($review), 200);
        }
        else
        {
            throw $this->createNotFoundException('no review found');
        }
    }
    
    public function postReviewAction($id, Request $request)
    {
        $book_service = $this->get('book_service');
        $review_service = $this->get('review_service');
        
        $hasReviewed = false;
        if($this->getUser())
        {
            $hasReviewed = (bool)$review_service->getReviewsByUserAndBookId($this->getUser()->getId(), $id)->getResult();
        };
        
        if($hasReviewed)
        {
            throw new HttpException(403, 'you have already given a review for this book');
        };
        
        $book = $book_service->getBookById($id);
        $newReview = $review_service->getNewReview();
        $newReview->setBook($book);
        $newReview->setReviewer($this->getUser());
        $form = $this->createForm(new ReviewType(), $newReview, array('csrf_protection' => false));
        $form->bind($request);
        
        if ($form->isValid())
        {
            $review_service->storeReview($form->getData());
            return $this->handleView($this->view($form->getData(), 201));
        }
        
        return $this->handleView($this->view($form), 400);
    }
    
    public function putReviewAction($slug, $id, Request $request)
    {
        $review_service = $this->get('review_service');
        $oldReview = $review_service->getReviewByReviewAndBookId($id, $slug);
        if($oldReview)
        {
            if($oldReview[0]->getReviewer()->getId() === $this->getUser()->getId())
            {
                $form = $this->createForm(new ReviewType(), $oldReview[0], array('csrf_protection' => false));
                $form->bind($request);

                if ($form->isValid())
                {
                    $newReviewData = $form->getData();
                    $review_service->updateReview($newReviewData);
                    return $this->handleView($this->view($newReviewData), 200);
                }

                return $this->handleView($this->view($form), 400);
            }
            else
            {
                throw new HttpException(401, 'you cannot edit someone elses review');
            }
        }
        throw $this->createNotFoundException('review does not exist');
    }
}