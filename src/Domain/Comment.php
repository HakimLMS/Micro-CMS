<?php

namespace MicroCMS\Domain;

class Comment 
{
    /**
     * Comment id.
     *
     * @var integer
     */
    private $id;

    /**
     * Comment Parent.
     *
     * @var integer
     */
    private $parent;
    
    /**
     * Comment author.
     *
     * @var int
     */
    private $author;

    /**
     * Comment content.
     *
     * @var integer
     */
    private $content;

    /**
     * Associated article.
     *
     * @var \MicroCMS\Domain\Article
     */
    private $article;
    
    /**
     * Depth of commentary
     *
     * @var integer
     */
    private $depth;
    
     /**
     * Children Comment 
     *
     * @var \MicroCMS\Domain\Comment
     */
    private $children;
    
    
    

    public function getId() {
        return $this->id;
    }
    
    public function getParent() {
        return $this->parent;
    }
    
    public function getChildren(){
        return $this->children;
    }
    
     public function getDepth() {
        return $this->depth;
    }

    public function getContent() {
        return $this->content;
    }
    
    public function getAuthor() {
        return $this->author;
    }

    public function getArticle() {
        return $this->article;
    }
    
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function setDepth($depth) {
        $this->depth = $depth;
        return $this;
    }
   
    public function setAuthor($author) {
        $this->author = $author;
        return $this;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }
    
    public function setParent($comment)
    {
        $this->parent= $comment;
    }
    
    public function setChild($Childrens)
    {
        $childrens = array();
        $childrens[] = $Childrens;
        $this->children = $childrens;
    }


    public function setArticle(Article $article) {
        $this->article = $article;
        return $this;
    }
    
    
        
}