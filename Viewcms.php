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
         <?php foreach($articles as $article): ?>
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
