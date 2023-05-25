<?php

namespace App\Controller;
use App\Form\ArticleType;
use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function home(ManagerRegistry $doctrine): Response
    {
        $articles= $doctrine->getRepository(Article::class)->findBy([], ['datepub' => 'DESC']);
        //$form = $this->createForm(ArticleType::class,$articles);
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController','articles' => $articles]
        );
    }

    
    #[Route('/article/nouveau', name: 'new_article')]

    public function new(Request $request,ManagerRegistry $doctrine) {
        $article = new Article();
      
        $form = $this->createForm(ArticleType::class,$article);

        $form->handleRequest($request);
  
        if($form->isSubmitted() && $form->isValid()) {
          $article = $form->getData();
  
          $entityManager = $doctrine->getManager();
          $entityManager->persist($article);
          $entityManager->flush();
  
          return $this->redirectToRoute('app_article');
        }
        return $this->render('article/new.html.twig',['form' => $form->createView()]);
    }

    #[Route('/article/edit/{id}', name: 'edit_article')]

    public function edit(Request $request,ManagerRegistry $doctrine, $id) {
        $article = new Article();
        $article = $doctrine()->getRepository(Article::class)->find($id);
  
        $form = $this->createForm(ArticleType::class,$article);
  
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
  
          $entityManager = $doctrine()->getManager();
          $entityManager->flush();
            
          return $this->redirectToRoute('app_article');
        }
  
        return $this->render('article/edit.html.twig', ['form' => $form->createView()]);
      }

      public function delete(Request $request, $id,ManagerRegistry $doctrine) {
        $article = $doctrine()->getRepository(Article::class)->find($id);
  
        $entityManager = $doctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();
  
        $response = new Response();
        $response->send();

        return $this->redirectToRoute('article_list');
      }
}
