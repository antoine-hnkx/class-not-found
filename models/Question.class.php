<?php

class Question
{
    private $_question_id;
    private $_author;
    private $_category;
    private $_best_answer;
    private $_title;
    private $_subject;
    private $_state;
    private $_publication_date;
    private $_answers;

    public function __construct($question_id, $author, $category, $best_answer, $title, $subject, $state, $publication_date, $answers)
    {
        $this->_question_id = $question_id;
        $this->_author = $author;
        $this->_category = $category;
        $this->_best_answer = $best_answer;
        $this->_title = $title;
        $this->_subject = $subject;
        $this->_state = $state;
        $this->_publication_date = $publication_date;
        $this->_answers = $answers;
    }

    public function questionId()
    {
        return $this->_question_id;
    }

    public function author()
    {
        return $this->_author;
    }

    public function category()
    {
        return $this->_category;
    }

    public function bestAnswer()
    {
        return $this->_best_answer;
    }

    public function setBestAnswer($bestAnswer){
        $this->_best_answer = $bestAnswer;
    }

    public function title()
    {
        return $this->_title;
    }

    public function html_title()
    {
        return htmlspecialchars($this->_title);
    }

    public function subject()
    {
        return $this->_subject;
    }

    public function html_subject()
    {
        return htmlspecialchars($this->_subject);
    }

    public function state()
    {
        return $this->_state;
    }

    public function publicationDate()
    {
        return $this->_publication_date;
    }

    public function answers(){
        return $this->_answers;
    }

    public function setAnswers($answers){
        $this->_answers = $answers;
    }

}