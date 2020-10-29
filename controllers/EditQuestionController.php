<?php


class EditQuestionController
{

    private $_db;

    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function run()
    {
        # Selecting the question to edit if the author clicked on edit on the question's view
        if (isset($_POST['edit']) || isset($_POST['form_edit'])) {
            $question = $this->_db->select_question_for_edit($_POST['question_id']);
        } else {
            # If the user try to post a question without being connected --> redirect login with notification
            if (!isset($_SESSION['login'])) {
                $_SESSION['error'] = 'You must be logged to post a question';
                header('Location: index.php?action=login');
                die();
            }
            # Empty question to post
            $question = new Question(null, null, null, null, null, null, null, null, null);
        }

        # Clicked on submit and if there is an empty field in the form
        if (isset($_POST['question_title']) && isset($_POST['question_subject'])) {
            if (preg_match('/^\s*$/', $_POST['question_title']) || preg_match('/^\s*$/', $_POST['question_subject'])) {
                $notification = "Please fill in all fields";
            } elseif (isset($_POST['question_title'][80]))
                $notification = 'Please enter a shorter title';
            elseif (isset($_POST['question_subject'][65535]))
                $notification = 'Please enter a shorter answer';
            else {
                # If the member is editing his question
                if (isset($_POST['form_edit'])) {
                    # Updating his question
                    $this->_db->edit_question($_POST['question_id'], $_POST['question_title'], $_POST['question_subject'], $_POST['question_category_id']);
                    header("Location: index.php?action=question&id=" . $_POST['question_id']);
                    die();
                } # If the member is posting a question
                elseif (isset($_POST['form_question'])) {
                    # Insert question into database
                    $authorId = unserialize($_SESSION['login'])->memberId();
                    $publicationDate = date("Y-m-d");
                    $this->_db->insert_question($authorId, $_POST['question_category_id'], $_POST['question_title'], $_POST['question_subject'], $publicationDate);
                    $postedQuestionId = $this->_db->select_last_posted_question();
                    header("Location: index.php?action=question&id=" . $postedQuestionId);
                    die();
                }
            }
        }

        # If the question is duplicated and user clicked on 'Answer' Button
        if ($question->state() == 'duplicated') {
            $_SESSION['error'] = 'This question is marked as duplicated';
            header('Location: index.php?action=question&id=' . $question->questionId());
            die();
        }

        # Selecting all categories to display
        $categories = $this->_db->select_categories();

        if (isset($_POST['question_id'])) {
            $questionId = $_POST['question_id'];
            $pageTitle = 'Edit your question:';
        }else
            $pageTitle = 'New question:';

        require_once(VIEWS . 'editQuestion.php');
    }
}