<?php
namespace MicroCMS\Controler;
use Silex\Application;
use MicroCMS\Domain\User;
use Symfony\Component\HttpFoundation\Request;

class AdminControler {
    
    public function LoginAction(Request $request, Application $app){
         return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));   
    }
    
    public function AdminAction(Application $app){
        $articles = $app['dao.article']->findAll();
        $comments = $app['dao.comment']->findAll();
        $users = $app['dao.user']->findAll();
        return $app['twig']->render('admin.html.twig', array(
        'articles' => $articles,
        'comments' => $comments,
        'users' => $users));
    }
    
}
