<?php
namespace App\Controller;

use App\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function home(BlogRepository $BlogRepository)
    {
        // Get all blogs
        $blogs = $BlogRepository->findAll();
        return $this->render('home.html.twig', [
            'blogs' => $blogs
        ]);
    }
}