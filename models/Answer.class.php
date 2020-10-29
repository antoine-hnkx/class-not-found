<?php

class Answer
{
    private $_answer_id;
    private $_author;
    private $_subject;
    private $_publication_date;
    private $_nbLikes;
    private $_nbDislikes;

    public function __construct($answer_id, $author, $subject, $publication_date, $nbLikes, $nbDislikes)
    {
        $this->_answer_id = $answer_id;
        $this->_author = $author;
        $this->_subject = $subject;
        $this->_publication_date = $publication_date;
        $this->_nbLikes = $nbLikes;
        $this->_nbDislikes = $nbDislikes;
    }

    public function answerId()
    {
        return $this->_answer_id;
    }

    public function author()
    {
        return $this->_author;
    }

    public function subject()
    {
        return $this->_subject;
    }

    public function html_subject()
    {
        return htmlspecialchars($this->_subject);
    }

    public function publicationDate()
    {
        return $this->_publication_date;
    }

    public function nbLikes(){
        return $this->_nbLikes;
    }

    public function nbDislikes(){
        return $this->_nbDislikes;
    }
    
    public function setLikes($nbLikes){
        $this->_nbLikes = $nbLikes;
    }

    public function setDislikes($nbDislikes){
        $this->_nbDislikes = $nbDislikes;
    }
}