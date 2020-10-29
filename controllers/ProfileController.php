<?php


class ProfileController
{

    private $_db;

    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function run()
    {

        # User not connected --> redirect homepage
        if (!isset($_SESSION['login'])) {
            header('Location: index.php');
            die();
        }
        $memberId = unserialize($_SESSION['login'])->memberId();
        # Selecting all questions related to the memberId
        $memberQuestions = $this->_db->select_questions_for_profile($memberId);

        $nbQuestions = count($memberQuestions);
        if($nbQuestions == 0)
            $notification =  'You haven\'t posted anything yet.';

        require_once(VIEWS . 'profile.php');
    }
}