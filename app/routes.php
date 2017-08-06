<?php
use Symfony\Component\HttpFoundation\Request;
use MicroCMS\Domain\Comment;
use MicroCMS\Form\Type\CommentType;
use MicroCMS\Form\Type\CommentUserType;
use MicroCMS\Domain\Article;
use MicroCMS\Form\Type\ArticleType;
// Home page
$app->get('/', function () use ($app) {
    $articles = $app['dao.article']->findAll();
return $app['twig']->render('index.html.twig', array('articles'=>$articles));
})->bind('home');

$app->get('/about', function () use ($app) {
return $app['twig']->render('about.html.twig');
})->bind('about');

$app->get('/articles', function () use ($app) {
    $articles = $app['dao.article']->findAll();
return $app['twig']->render('book.html.twig', array('articles' => $articles));
})->bind('articles');


//Article page
$app->match('/article/{id}', function ($id, Request $request) use ($app) {
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
            $app['dao.comment']->SaveComment($comment);
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
})->bind('article');

//report a comment
$app->get('comment/{comment_id}/report', function($comment_id) use ($app) {
    $comment = $app['dao.comment']->find($comment_id);
    $article = $comment->getArticle();
    $id = $article->getId();
    $comment->setState('signale');
    $app['dao.comment']->save($comment);
    $app['session']->getFlashBag()->add('success', "L'article à bien été signalé.");
    return $app->redirect($app['url_generator']->generate('article', array ('id' => $id)));
})->bind('comment_report');

// Login form
$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');

// Admin home page
$app->get('/admin', function() use ($app) {
    $articles = $app['dao.article']->findAll();
    $comments = $app['dao.comment']->findAll();
    $users = $app['dao.user']->findAll();
    return $app['twig']->render('admin.html.twig', array(
        'articles' => $articles,
        'comments' => $comments,
        'users' => $users));
})->bind('admin');

// Add a new article
$app->match('/admin/article/add', function(Request $request) use ($app) {
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
})->bind('admin_article_add');

// Edit an existing article
$app->match('/admin/article/{id}/edit', function($id, Request $request) use ($app) {
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
})->bind('admin_article_edit');

// Remove an article
$app->get('/admin/article/{id}/delete', function($id, Request $request) use ($app) {
    // Delete all associated comments
    $app['dao.comment']->deleteAllByArticle($id);
    // Delete the article
    $app['dao.article']->delete($id);
    $app['session']->getFlashBag()->add('success', 'L\'artcile a été supprimé.');
    // Redirect to admin home page
    return $app->redirect($app['url_generator']->generate('admin'));
})->bind('admin_article_delete');

// Edit an existing comment
$app->match('/admin/comment/{id}/edit', function($id, Request $request) use ($app) {
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
})->bind('admin_comment_edit');

// Remove a comment
$app->get('/admin/comment/{id}/delete', function($id, Request $request) use ($app) {
    $app['dao.comment']->delete($id);
    $app['session']->getFlashBag()->add('success', 'Le commentaire à été supprimé.');
    return $app->redirect('/admin');
    // Redirect to admin home page
    return $app->redirect($app['url_generator']->generate('admin'));
})->bind('admin_comment_delete');

