<?php

class NewAnswerController
{
    private $_db;

    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function run()
    {

        # Redirection to login if not logged
        if (!isset($_SESSION['login'])) {
            $_SESSION['error'] = 'You must be logged to post an answer';
            header('Location: index.php?action=login');
            die();
        }

        # Redirection to homepage if user tried to enter url action without clicking answer button
        if (!isset($_POST['id'])) {
            header("Location: index.php");
            die();
        }

        # Answer form
        if (isset($_POST['form_answer'])) {
            # Check if user is trying to post an empty answer
            if (preg_match('/^\s*$/', $_POST['answer_text']))
                $notification = "Please fill in all fields";
            elseif (isset($_POST['answer_text'][65535]))
                $notification = 'Please enter a shorter answer';
            else {
                # Insert answer into database
                $authorId = unserialize($_SESSION['login'])->memberId();
                $publicationDate = date("Y-m-d");
                $this->_db->insert_answer($authorId, $_POST['id'], $_POST['answer_text'], $publicationDate);
                $idOfAddedAnswer = $this->_db->select_newest_answer($authorId);
                header("Location: index.php?action=question&id=" . $_POST['id'] . '#' . $idOfAddedAnswer);
                die();
            }

        }

        # Select the question from the id in $_POST['id'] (hidden input)
        $question = $this->_db->select_question_for_new_answer($_POST['id']);

        # If the question is duplicated and user clicked on Answer Button
        if ($question->state() == 'duplicated') {
            $_SESSION['error'] = 'This question is marked as duplicated';
            header('Location: index.php?action=question&id=' . $question->questionId());
            die();
        }

        require_once(VIEWS . 'newAnswer.php');
    }
}