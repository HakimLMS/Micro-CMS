<?php

namespace MicroCMS\DAO;
use MicroCMS\Domain\Comment;

class CommentDAO extends DAO 
{
    /**
     * @var \MicroCMS\DAO\ArticleDAO
     */
    private $articleDAO;
    
    private $userDAO;

    public function setArticleDAO(ArticleDAO $articleDAO) {
        $this->articleDAO = $articleDAO;
    }

    public function setUserDAO(UserDAO $userDAO) {
        $this->userDAO = $userDAO;
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
        $sql = "select * from t_comment where art_id=? order by com_id";
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
        
        foreach ($comments as $comment){
            $parent_id = $comment->getParent();            
            if ($parent_id != 0)
            {
               $parent = $comments[$parent_id];
               $childrens[$comment->getId()] = $comment;
               $childrens_by_parent = $this->findChild($childrens,$parent->getId());
               $parent->setChild($childrens_by_parent);
            }  
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
        
        $comment = $this->buildUniqueComment($row);
        
        if (array_key_exists('art_id', $row)) {
            // Find and set the associated article
            $articleId = $row['art_id'];
            $article = $this->articleDAO->find($articleId);
            $comment->setArticle($article);
        }
        if (array_key_exists('usr_id', $row)) {
            // Find and set the associated author
            $userId = $row['usr_id'];
            $user = $this->userDAO->find($userId);
            
            $comment->setAuthor($user);
        }
        

        return $comment;   
    }
    
    /**
     * Creates an Comment object based on a DB row.
     *
     * @param array $row The DB row containing Comment data.
     * @return \MicroCMS\Domain\Comment
     */
    protected function buildUniqueComment(array $row)
    {
        $comment = new Comment();
        $comment->setId($row['com_id']);
        $comment->setContent($row['com_content']);
        $comment->setAuthor($row['com_author']);
        $comment->setMail($row['com_mail']);
        $comment->setParent($row['parent_id']);
        $comment->setDate($row['t_date']);
        $comment->setState($row['t_state']);
        return $comment;
    }

    /**
     * Finds every childs of a comment.
     *
     * @param array $comments TheDb array containing all objects comments.
     * @param $parent_id the id of the Comment object 'parent'
     * @return array \MicroCMS\Domain\Comment
     */
    public function findChild(array $comments, $parent_id)
    {
       $selected_comments = array();
       
       Foreach ($comments as $comment)
       {
           if ($parent_id == $comment->getParent())
           {              
              $selected_comments[] = $comment;
           }     
       }
    return $selected_comments;
       
    }
    
    
    /**
     * Saves or update an Comment object 
     *
     * @param object comment to save in DB
     */
    public function  save(Comment $comment)
    {
        $commentdata = array(
            'art_id' => $comment->getArticle()->getId(),
            'com_author' => $comment->getAuthor(),
            'com_content' => $comment->getContent(),
            'com_mail' => $comment->getMail(),
            'parent_id' => $comment->getParent(),
            't_date' => $comment->getDate(),
            't_state'=> $comment->getState()
        );    
        
        if($comment->getId())
        {
            var_dump($commentdata);
            $this->getDb()->update('t_comment',$commentdata,array('com_id' => $comment->getId()));
        }
        else
        {
            $this->getDb()->insert('t_comment', $commentdata);
            $id = $this->getDb()->lastinsertID();
            $comment->setId($id);
        }
    }
    
    
    /**
     * Returns a list of all comments, sorted by date (most recent first).
     *
     * @return array A list of all comments.
     */
    public function findAll() {
        $sql = "select * from t_comment order by com_id desc";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $comments = array();
        foreach ($result as $row) {
            $id = $row['com_id'];
            $comments[$id] = $this->buildDomainObject($row);
        }
        return $comments;
    }
    
    /**
     * Removes all comments for an article
     *
     * @param $articleId The id of the article
     */
    public function deleteAllByArticle($articleId) {
        $this->getDb()->delete('t_comment', array('art_id' => $articleId));
    }
    
       /**
     * Returns a comment matching the supplied id.
     *
     * @param integer $id The comment id
     *
     * @return \MicroCMS\Domain\Comment|throws an exception if no matching comment is found
     */
    public function find($id) {
        $sql = "select * from t_comment where com_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No comment matching id " . $id);
    }

    // ...

    /**
     * Removes a comment from the database.
     *
     * @param @param integer $id The comment id
     */
    public function delete($id) {
        // Delete the comment
        $this->getDb()->delete('t_comment', array('com_id' => $id));
    }

    public function findAlerts()
    {
        $sql = "select * from t_comment where t_state= 'signale'";
        $row = $this->getDb()->fetchAssoc($sql);

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No comment matching id " . $id); 
    }
    
}