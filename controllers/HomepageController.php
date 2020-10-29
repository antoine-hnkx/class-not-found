<?php

class HomepageController
{

    private $_db;

    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function run()
    {
        # If the user is searching a question
        if (isset($_POST['form_search'])) {
            # Default value for $_POST['search'] is '' (if empty string)
            if (preg_match('/^\s*$/', $_POST['search'])) $_POST['search'] = '';

            # Select the question that contains a certain keyword (from the search bar)
            $questions = $this->_db->search_questions($_POST['search']);
            $pageTitle = 'Results:' ;
            $search = true ;
        } # If the user clicked on a category
        elseif (isset($_GET['category_id'])) {
            # Selecting the category
            $category = $this->_db->select_category($_GET['category_id']);
            # If the category doesn't exist redirecting to homepage
            if ($category == null) {
                header('Location: index.php');
                die();
            }
            $pageTitle = $category->name() . " Questions:";
            # Select all questions + their respective author from the category with the specified id
            $questions = $this->_db->select_questions_for_category($_GET['category_id']);


        } # No action displaying newest questions
        else {
            $pageTitle = 'Questions:';
            # Select the newest questions + their respective author and category from the database
            $questions = $this->_db->select_newest_questions_for_homepage();

        }

        $nbQuestions = count($questions);

        if($nbQuestions == 0) {
            if (isset($category))
                $notification = 'No questions have been posted in this category yet.';
            elseif (isset($search))
                $notification = 'No results';
        }
        require_once(VIEWS . 'homepage.php');
    }
}