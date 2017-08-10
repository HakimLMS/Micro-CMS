<?php
namespace MicroCMS\Controler;
 
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use MicroCMS\Domain\Comment;
use MicroCMS\Form\Type\CommentType;
use MicroCMS\Form\Type\CommentUserType;

class FrontControler{
    
    public function IndexAction(Application $app){
         $articles = $app['dao.article']->findLasts();
         return $app['twig']->render('index.html.twig', array('articles'=>$articles));
    }
    
    public function AboutAction(Application $app){
        return $app['twig']->render('about.html.twig');
    }
    
    public function ArticleAction($id, Request $request, Application $app)
    {
    $article = $app['dao.article']->find($id);
    
    
    $comment = new Comment();
    $comment->setArticle($article);
    
    if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY') == false){
        $user = $app['user'];
        $comment->setAuthor($user);
        $comment->setState('publie');
    }
    else {
    $user = $app['user']->getUsername();
    $mail = $app['user']->getMail();
    $comment->setAuthor($user);
    $comment->setMail($mail);
    $comment->setState('publie');
    }
    
    if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY') == false){
    $commentForm = $app['form.factory']->create(CommentType::class, $comment);
    }
    else
    {
    $commentForm = $app['form.factory']->create(CommentUserType::class, $comment);//TODO->voir comment switcher de type de Form lorsque l'user est connecté <-TODO 
    }
    
    $commentForm->handleRequest($request);
    $comments = $app['dao.comment']->findAllByArticle($id); 
    
    $parent_id = 0;
    if(isset($_GET['replyto'])){
    $parent_id = $_GET['replyto'];}
    
    if ($commentForm->isSubmitted() && $commentForm->isValid()) {
        if($parent_id != 0){
            $comment->setParent($parent_id);
            $comment->setDate(date("Y-m-d H:i:s"));
            $parent = $comments[$parent_id];
            $parent->setChild($comment);
            $app['dao.comment']->save($comment);
            $app['dao.comment']->save($parent);
            $app['session']->getFlashBag()->add('success', 'Votre commentaire à bien été envoyé.');
            
        }
        else{
            $comment->setParent($parent_id);
            $app['dao.comment']->save($comment);
            $app['session']->getFlashBag()->add('success', 'Votre commentaire à bien été envoyé.');
        }
        return $app->redirect('/article/'.$id);
    }
    $commentFormView = $commentForm->createView();
    
    foreach ($comments as $comment)
        {
            if($comment->getParent() != 0)
            {
                $key = $comment->getId();
                unset($comments[$key]);
            }
        }
    
    
    return $app['twig']->render('article.html.twig', array('article' => $article, 'comments' => $comments, 'parent_id' => $parent_id, 'commentForm' => $commentFormView));
    }
    
    public function CGUAction(Application $app){
        return $app['twig']->render('CGU.html.twig');
    }
    
    public function BookAction(Application $app){
    $articles = $app['dao.article']->findAll();
    return $app['twig']->render('book.html.twig', array('articles' => $articles));    
    }   
}