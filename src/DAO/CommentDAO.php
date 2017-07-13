<?php

namespace MicroCMS\DAO;
use MicroCMS\Domain\Comment;

class CommentDAO extends DAO 
{
    /**
     * @var \MicroCMS\DAO\ArticleDAO
     */
    private $articleDAO;

    public function setArticleDAO(ArticleDAO $articleDAO) {
        $this->articleDAO = $articleDAO;
    }

    /**
     * Return a list of all comments for an article, sorted by date (most recent last).
     *
     * @param integer $articleId The article id.
     *
     * @return array A list of all comments for the article.
     */
    public function findAllByArticle($articleId) {
        // The associated article is retrieved only once
        $article = $this->articleDAO->find($articleId);

        // art_id is not selected by the SQL query
        // The article won't be retrieved during domain objet construction
        $sql = "select com_id, com_content, com_author, parent_id, depth from t_comment where art_id=? order by com_id";
        $result = $this->getDb()->fetchAll($sql, array($articleId));
        // Convert query result to an array of domain objects
        $comments = array();
        foreach ($result as $row) {
            
            $comId = $row['com_id'];
            $comment = $this->buildDomainObject($row);
            // The associated article is defined for the constructed comment
            $comment->setArticle($article);
            $comments[$comId] = $comment;
            
        }
        
        return $comments;
    }

    /**
     * Creates an Comment object based on a DB row.
     *
     * @param array $row The DB row containing Comment data.
     * @return \MicroCMS\Domain\Comment
     */
    protected function buildDomainObject(array $row) {
        
        $comment = $this->builduniqueComment($row);     

        if (array_key_exists('art_id', $row)) {
            // Find and set the associated article
            $articleId = $row['art_id'];
            $article = $this->articleDAO->find($articleId);
            $comment->setArticle($article);
        }
        return $comment;   
    }
    
    protected function buildUniqueComment(array $row)
    {
        $comment = new Comment();
        $comment->setId($row['com_id']);
        $comment->setContent($row['com_content']);
        $comment->setAuthor($row['com_author']);
        $comment->setParent($row['parent_id']);
        $comment->setDepth($row['depth']);
        
        $childrens = $this->findChildrens($comment->getId());
        foreach ($childrens as $row)
        {             
            $unique_child = $this->buildUniqueComment($row);
            $comment->setChild($unique_child);
        }
  
        return $comment;
    }

    protected function findChildrens($comment_id)
    {
     $sql = "SELECT * FROM t_comment WHERE parent_id=?";
     $result = $this->getDb()->fetchAll($sql, array($comment_id));     
     return $result;
    }
    
}