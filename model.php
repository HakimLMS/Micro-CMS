<?php

function GetArticle()
{
            try{
            $bdd = new PDO('mysql:host=localhost;dbname=microcms;charset=utf8','microcms_user','secret');
        }
        catch ( Exception $ex)
        {
            echo $ex->GetMessage();
        }
        $articles = $bdd->query('SELECT * FROM t_articles ORDER BY art_id DESC');
        return $articles;
}
