<?php
namespace MicroCMS\Controler;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use MicroCMS\Form\Type\ArticleType;
use MicroCMS\Domain\Article;

class APIControler {
    
    public function ReportAction($comment_id, Application $app)
    {
        $comment = $app['dao.comment']->find($comment_id);
        $article = $comment->getArticle();
        $id = $article->getId();
        $comment->setState('signale');
        $app['dao.comment']->save($comment);
        $app['session']->getFlashBag()->add('success', "Le commentaire à bien été signalé.");
        return $app->redirect($app['url_generator']->generate('article', array ('id' => $id)));   
    }
    
    public function AddArticleAction(Request $request, Application $app)
    {
        $article = new Article();
        $articleForm = $app['form.factory']->create(ArticleType::class, $article);
        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $state = $articleForm['state']->getData();
            if($state == false)
            {
                $article->setState('brouillon');
                $app['dao.article']->save($article);
                $app['session']->getFlashBag()->add('success', 'L\'artcile a été mis aux brouillons.');
            }
            else
            {
                $article->setState('publie');
                $app['dao.article']->save($article);
                $app['session']->getFlashBag()->add('success', 'L\'artcile a été publié.');
            }
            return $app->redirect('/admin');
        }
        return $app['twig']->render('article_form.html.twig', array(
            'title' => 'New article',
            'articleForm' => $articleForm->createView()));  
    }
    
    public function EditArticleAction($id, Request $request, Application $app)
    {
        $article = $app['dao.article']->find($id);
        $articleForm = $app['form.factory']->create(ArticleType::class, $article);
        $articleForm->handleRequest($request);
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $state = $articleForm['state']->getData();
            if($state == false)
            {
                $article->setState('brouillon');
                $app['dao.article']->save($article);
                $app['session']->getFlashBag()->add('success', 'L\'artcile a été mis aux brouillons.');
            }
            else
            {
                $article->setState('publie');
                $app['dao.article']->save($article);
                $app['session']->getFlashBag()->add('success', 'L\'artcile a été publié.');
            }
            return $app->redirect('/admin');
        }
        return $app['twig']->render('article_form.html.twig', array(
            'title' => 'Edit article',
            'articleForm' => $articleForm->createView()));
    }
    
    public function DeleteArticleAction($id, Application $app) {
         // Delete all associated comments
        $app['dao.comment']->deleteAllByArticle($id);
        // Delete the article
        $app['dao.article']->delete($id);
        $app['session']->getFlashBag()->add('success', 'L\'artcile a été supprimé.');
        // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));   
    }
    
    public function EditCommentAction(Request $request, Application $app){
            $comment = $app['dao.comment']->find($id);
            $commentForm = $app['form.factory']->create(CommentUserType::class, $comment);
            $commentForm->handleRequest($request);
            if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                $app['dao.comment']->save($comment);
                $app['session']->getFlashBag()->add('success', 'Le commentaire a été mis à jour.');
                return $app->redirect('/admin');
            }
            return $app['twig']->render('commentform.html.twig', array(
                'title' => 'Edit comment',
                'commentForm' => $commentForm->createView()));     
    }
    
    public function DeleteCommentAction($id, Application $app){
        $app['dao.comment']->delete($id);
        $app['session']->getFlashBag()->add('success', 'Le commentaire à été supprimé.');
        return $app->redirect('/admin');
    }
    
    public function ModerateCommentAction($id, Application $app){
        $comment = $app['dao.comment']->find($id);
        $comment->setContent("Le commentaire ne correspondant pas aux CGU à été modéré");
        $comment->setState('modéré');
        $app['dao.comment']->save($comment);
        $app['session']->getFlashBag()->add('success', 'Le commentaire à été modéré.');
        return $app->redirect('/admin');
    }
    
    public function RehabCommentAction($id, Request $request, Application $app){
        $comment = $app['dao.comment']->find($id);
        $comment->setState('publie');
        $app['dao.comment']->save($comment);
        $app['session']->getFlashBag()->add('success', 'Le commentaire à été réhabilité.');
        return $app->redirect('/admin');
    }
}

