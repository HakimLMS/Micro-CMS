<?php

// Home page
$app->get('/', "MicroCMS\Controler\FrontControler::IndexAction")->bind('home');
//About page
$app->get('/about', "MicroCMS\Controler\FrontControler::AboutAction")->bind('about');

//CGU page
$app->get('/CGU',"MicroCMS\Controler\FrontControler::CGUAction" )->bind('CGU');

//ArticleS page
$app->get('/articles', "MicroCMS\Controler\FrontControler::BookAction") ->bind('articles');

//Article page
$app->match('/article/{id}', "MicroCMS\Controler\FrontControler::ArticleAction")->bind('article');

//report a comment
$app->get('comment/{comment_id}/report',  "MicroCMS\Controler\APIControler::ReportAction")->bind('comment_report');

// Login form
$app->get('/login', "MicroCMS\Controler\AdminControler::LoginAction")->bind('login');

// Admin home page
$app->get('/admin',"MicroCMS\Controler\AdminControler::AdminAction")->bind('admin');

// Add a new article
$app->match('/admin/article/add',"MicroCMS\Controler\APIControler::AddArticleAction" )->bind('admin_article_add');

// Edit an existing article
$app->match('/admin/article/{id}/edit',"MicroCMS\Controler\APIControler::EditArticleAction")->bind('admin_article_edit');

// Remove an article
$app->get('/admin/article/{id}/delete',"MicroCMS\Controler\APIControler::DeleteArticleAction")->bind('admin_article_delete');

// Edit an existing comment
$app->match('/admin/comment/{id}/edit',"MicroCMS\Controler\APIControler::EditCommentAction")->bind('admin_comment_edit');

// Remove a comment
$app->get('/admin/comment/{id}/delete', "MicroCMS\Controler\APIControler::DeleteCommentAction")->bind('admin_comment_delete');

// Moderate a comment
$app->get('/admin/comment/{id}/moderate',"MicroCMS\Controler\APIControler::ModerateCommentAction")->bind('moderate');

// rehabilitate a comment
$app->get('/admin/comment/{id}/rehab', "MicroCMS\Controler\APIControler::RehabCommentAction")->bind('rehab');