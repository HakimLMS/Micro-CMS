<!DOCTYPE html>
<html>
    <head>
        <meta charset="Utf-8"/>
        <link href="microcms.css" rel="stylesheet" />
        <title> Mon micro CMS !</title>        
    </head>
    <body>
        <header>
            <h1>MicroCMS</h1>
        </header>
        <?php
        try{
            $bdd = new PDO('mysql:host=localhost;dbname=microcms;charset=utf8','microcms_user','secret');
        }
        catch ( Exception $ex)
        {
            echo $ex->GetMessage();
        }
        $articles = $bdd->query('SELECT * FROM t_articles ORDER BY art_id DESC');
        foreach($articles as $article): ?>
        <article>
            <h2><?php echo $article['art_title']; ?></h2>
            <p><?php echo $article['art_content']; ?></p>
        </article>
        <?php endforeach;?>
        <footer>
            Blablabla
        </footer>
    </body>
</html>
