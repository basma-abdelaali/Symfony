<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Blog;
use App\Entity\Likes;
use App\Entity\Comment;
use App\Form\BlogType;
use App\Form\CommentType;
use App\Repository\BlogRepository;
use App\Repository\CommentRepository;
use App\Repository\LikesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class BlogController extends AbstractController
{

  /**
   * @Route("/add_blog", name="add_blog")
   */
  public function addBlog(Request $request, Security $security)
  {
    // use this so we can insert to table
    $entityBlog = $this->getDoctrine()->getManager();
    // get connected user
    // $connected_user = $security->getUser();
    // 1) build the form
    $blog = new Blog();
    $form = $this->createForm(BlogType::class, $blog);

    // 2) handle the submit (will only happen on POST)
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      // insert Blog
      $r_blog = $request->get('blog');
      $blog->setTitle($r_blog['title']);
      $blog->setDescription($r_blog['description']);
      $blog->setText($r_blog['text']);
      $blog->setDate(new \DateTime());
      $user = $this->get('security.token_storage')->getToken()->getUser();
      $blog->setAuthor($user);

      $entityBlog->persist($blog);
      // actually executes the queries (i.e. the INSERT query)
      $entityBlog->flush();

      return $this->redirectToRoute('home');
    }

    return $this->render(
      'blog/create_blog.html.twig',
      array('form' => $form->createView())
    );
  }

  /**
   * @Route("/blog/{id}", name="blog")
   */
  public function blog(Request $request, Security $security, BlogRepository $blogRepository, CommentRepository $commenttRepository, LikesRepository $LikesRepository)
  {
    // get blog by id
    $blog = $blogRepository->find($request->get("id"));
    $comment = new Comment();
    $form = $this->createForm(CommentType::class, $comment);
    $entityComment = $this->getDoctrine()->getManager();
    // $comment = new Commentt();
    $form = $this->createForm(CommentType::class, $comment);
    $user = $this->get('security.token_storage')->getToken()->getUser();
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      // insert comment
      $r_comment = $request->get('comment');
      $comment->setComment($r_comment['comment']);
      $comment->setDateComment(new \DateTime());
      $comment->setUserId($user);
      $comment->setBlogId($blog);
      $entityComment->persist($comment);
      // actually executes the queries (i.e. the INSERT query)
      $entityComment->flush();
      unset($entityComment);
      unset($form);
      $comment = new Comment();
      $form = $this->createForm(CommentType::class, $comment);
    }
    $comments = $commenttRepository->findByExampleField($request->get("id"));
    $likes = $LikesRepository->findByExampleField($request->get("id"));
    $check_user = $LikesRepository->findIfuserExist($user,$request->get("id"));
    $count_likes = count($likes);
    $count_check_user = count($check_user);
    
    // dd($check_user);
    // dump($comments);
    // exit;
    return $this->render(
      'blog/blog.html.twig',
      [
        'blog' => $blog,
        'form' => array('form' => $form->createView()),
        'comments' => $comments,
        'likes' => $count_likes,
        'liked' => $count_check_user
      ]
    );
    return;
  }

  /**
   * @Route("/delete_blog/{id}", name="delete_blog")
   */
  public function deleteBlog(Request $request, Security $security, BlogRepository $blogRepository, CommentRepository $commenttRepository, LikesRepository $LikesRepository)
  {
    // get blog by id
    $blog = $blogRepository->find($request->get("id"));
    $likes = $LikesRepository->findByExampleField($request->get("id"));
    $comments = $commenttRepository->findByExampleField($request->get("id"));
    foreach ($comments as $key => $value) {
      $blog->removeComment($value[0]);
    }
    foreach ($likes as $key2 => $value2 ){
      $blog->removeLikes2($value2);
    }

    $em = $this->getDoctrine()->getManager();
    // delete blog
    $em->remove($blog);
    $em->flush();
    return $this->redirectToRoute('home');
  }

  /**
   * @Route("/like/{user_id}/{blog_id}", name="like")
   */
  public function addLikes(Request $request, Security $security, BlogRepository $blogRepository)
  {

    if ($request->isMethod('POST')) {
      $user = $this->get('security.token_storage')->getToken()->getUser();
      $blog_id = $request->get("user_id");
      $blog = $blogRepository->find($request->get('blog_id'));
      $entityLikes = $this->getDoctrine()->getManager();
      $likes = new Likes();
      $likes->setUserId($user);
      $likes->setBlogId($blog);
      $entityLikes->persist($likes);
      $entityLikes->flush();
      return $this->redirectToRoute('home');
    }
  }
}
