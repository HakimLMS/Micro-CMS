<?php
use Symfony\Component\HttpFoundation\Request;
use MicroCMS\Domain\Comment;
use MicroCMS\Form\Type\CommentType;
// Home page
$app->get('/', function () use ($app) {
    $articles = $app['dao.article']->findAll();
return $app['twig']->render('index.html.twig', array('articles'=>$articles));
})->bind('home');


//Article page
$app->match('/article/{id}', function ($id, Request $request) use ($app) {
    $article = $app['dao.article']->find($id);
    
    
    $comment = new Comment();
    $comment->setArticle($article);
    
    if (isset($user)):
    $user = $app['user'];
    $comment->setAuthor($user);
    endif;
    
    $commentForm = $app['form.factory']->create(CommentType::class, $comment);
    $commentForm->handleRequest($request);
    $comments = $app['dao.comment']->findAllByArticle($id); 
    
    $parent_id = 0;
    if(isset($_GET['replyto'])){
    $parent_id = $_GET['replyto'];}
    
    if ($commentForm->isSubmitted() && $commentForm->isValid()) {
        if($parent_id != 0){
            $comment->setParent($parent_id);
            $parent = $comments[$parent_id];
            $parent->setChild($comment);
            var_dump($parent);
            $app['dao.comment']->SaveComment($comment);
            $app['dao.comment']->SaveComment($parent);
            
        }
        else{
            $comment->setParent($parent_id);
            $app['dao.comment']->SaveComment($comment);
            $app['session']->getFlashBag()->add('success', 'Your comment was successfully added.');
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
})->bind('article');

// Login form
$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');