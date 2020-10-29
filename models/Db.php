<?php

class Db
{
    private static $instance = null;
    private $_db;

    private function __construct()
    {
        $url = getenv('JAWSDB_URL');
        $dbparts = parse_url($url);
        
        $hostname = $dbparts['host'];
        $username = $dbparts['user'];
        $password = $dbparts['pass'];
        $database = ltrim($dbparts['path'],'/');

        try {
            $this->_db = new PDO('mysql:host=' . $hostname . ';dbname=' . $database . ';charset=utf8', $username, $password);
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die('Error: connexion to database failed : ' . $e->getMessage());
        }
    }

    # Pattern Singleton
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Db();
        }
        return self::$instance;
    }

    # -------------------------------------------------------------------------------- #

    # ***** DATABASE SCRIPTS ***** #

    # -------------------------------------------------------------------------------- #

    # GENERAL / ALL SITE
    # Select all categories
    public function select_categories()
    {
        $query = 'SELECT * FROM categories ORDER BY category_id ASC';
        $ps = $this->_db->prepare($query);
        $ps->execute();
        $categories = array();
        while ($row = $ps->fetch()) {
            $categories[] = new Category($row->category_id, $row->name);
        }
        return $categories;
    }

    # -------------------------------------------------------------------------------- #

    # QUESTIONS CONTROLLER
    # Select the newest questions
    public function select_newest_questions_for_homepage()
    {
        $query = 'SELECT Q.*, M.*, C.* FROM questions Q, members M, categories C WHERE Q.author_id = M.member_id AND Q.category_id = C.category_id  AND Q.state is null ORDER BY Q.question_id DESC';
        $ps = $this->_db->prepare($query);
        $ps->execute();

        $questions = array();
        while ($row = $ps->fetch()) {
            $author = new Member($row->member_id, $row->login, null, null, null, null, null);
            $category = new Category($row->category_id, $row->name);
            $questions[] = new Question($row->question_id, $author, $category, null, $row->title, null, null, $row->publication_date, null);
        }
        return $questions;
    }

    # Select all questions from a category
    public function select_questions_for_category($idCategory)
    {
        $query = 'SELECT Q.*, M.* FROM questions Q, members M WHERE Q.author_id = M.member_id AND Q.category_id = :id ORDER BY Q.question_id DESC';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id', $idCategory);
        $ps->execute();

        $questions = array();

        while ($row = $ps->fetch()) {
            $author = new Member($row->member_id, $row->login, null, null, null, null, null);
            $questions[] = new Question($row->question_id, $author, null, null, $row->title, null, null, $row->publication_date, null);
        }
        return $questions;
    }

    # Select the category corresponding to the 'id' parameter
    public function select_category($category_id)
    {
        $query = 'SELECT * FROM categories WHERE category_id=:id';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id', $category_id);
        $ps->execute();
        if($ps->rowCount() == 0){
            return null;
        }
        $row = $ps->fetch();
        $category = new Category($row->category_id, $row->name);
        return $category;
    }

    # Select the questions that contains a certain keyword
    public function search_questions($keyword)
    {
        $keyword = strtolower($keyword);
        $query = 'SELECT Q.*, M.*, C.* FROM questions Q, members M, categories C WHERE Q.author_id = M.member_id AND Q.category_id = C.category_id AND (LOWER(Q.title) LIKE :keyword OR LOWER(Q.subject) LIKE :keyword) ORDER BY Q.question_id DESC';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':keyword', "%$keyword%");
        $ps->execute();

        $questions = array();
        while ($row = $ps->fetch()) {
            $author = new Member($row->member_id, $row->login, null, null, null, null, null);
            $category = new Category($row->category_id, $row->name);
            $questions[] = new Question($row->question_id, $author, $category, null, $row->title, null, null, $row->publication_date, null);
        }
        return $questions;
    }

    # -------------------------------------------------------------------------------- #

    # REGISTER CONTROLLER

    # Insert a new member in the database
    public function insert_member($lastname, $firstname, $mail, $login, $password)
    {
        $query = 'INSERT INTO members (lastname,firstname,mail,login,password,admin,suspended) VALUES (:lastname,:firstname,:mail,:login,:password,:admin,:suspended)';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':lastname', $lastname);
        $ps->bindValue(':firstname', $firstname);
        $ps->bindValue(':mail', $mail);
        $ps->bindValue(':login', $login);
        $ps->bindValue(':password', $password);
        $ps->bindValue(':admin', 0);
        $ps->bindValue(':suspended', 0);
        $ps->execute();
    }

    # -------------------------------------------------------------------------------- #

    # LOGIN CONTROLLER
    # Verify the password of a login (return null if no such login or incorrect password)
    public function verify_member($login, $password)
    {
        $query = 'SELECT * FROM members WHERE login=:login';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':login', $login);
        $ps->execute();
        $row = $ps->fetch();
        $member = null;
        if (!empty($row)) {
            $member = new Member($row->member_id, $row->login, null, null, null, $row->admin, $row->suspended);
            if (!password_verify($password, $row->password)) {
                $member = null;
            }
        }
        return $member;
    }

    # -------------------------------------------------------------------------------- #

    # EDIT CONTROLLER
    # Select question from id
    public function select_question_for_edit($idQuestion)
    {
        $query = 'SELECT Q.*, M.*, C.* FROM questions Q, members M ,categories C WHERE Q.author_id = M.member_id AND Q.question_id=:id AND Q.category_id=C.category_id';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id', $idQuestion);
        $ps->execute();
        $row = $ps->fetch();
        $author = new Member($row->member_id, $row->login, null, null, null, null, null);
        $category = new Category($row->category_id, $row->name);
        return new Question($row->question_id, $author, $category, null, $row->title, $row->subject, $row->state, null, null);
    }

    # Update the question corresponding to the 'id' parameter
    public function edit_question($question_id, $title, $subject, $category)
    {
        $query = 'UPDATE questions SET title=:title,subject=:subject,category_id=:cat WHERE question_id=:id';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':title', $title);
        $ps->bindValue(':subject', $subject);
        $ps->bindValue(':cat', $category);
        $ps->bindValue(':id', $question_id);
        $ps->execute();
    }

    #Select the last posted question
    public function select_last_posted_question()
    {
        $query = 'SELECT MAX(question_id) as \'id\' FROM questions';
        $ps = $this->_db->prepare($query);
        $ps->execute();
        $row = $ps->fetch();
        return $row->id;
    }

    # Insert a new question
    public function insert_question($author_id, $category_id, $title, $subject, $publication_date)
    {
        $query = 'INSERT INTO questions (author_id,category_id,title,subject,publication_date) VALUES (:author_id,:category_id,:title,:subject,:publication_date)';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':author_id', $author_id);
        $ps->bindValue(':category_id', $category_id);
        $ps->bindValue(':title', $title);
        $ps->bindValue(':subject', $subject);
        $ps->bindValue(':publication_date', $publication_date);
        $ps->execute();
    }

    # -------------------------------------------------------------------------------- #

    # NEW ANSWER CONTROLLER
    # Select question from id
    public function select_question_for_new_answer($idQuestion)
    {
        $query = 'SELECT Q.*, M.*, C.* FROM questions Q, members M, categories C WHERE Q.question_id=:id AND Q.author_id = M.member_id AND Q.category_id = C.category_id';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id', $idQuestion);
        $ps->execute();
        $row = $ps->fetch();
        $author = new Member($row->member_id, $row->login, null, null, null, null, null);
        $category = new Category($row->category_id, $row->name);
        return new Question($row->question_id, $author, $category, null, $row->title, $row->subject, $row->state, $row->publication_date, null);
    }

    # Select id of last posted answer
    public function select_newest_answer($authorId)
    {
        $query = 'SELECT MAX(A.answer_id) as \'max\' FROM answers A WHERE author_id=:id';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id', $authorId);
        $ps->execute();
        $id = $ps->fetch()->max;
        return $id;
    }

    # Insert a new answer with the specified question_id
    public function insert_answer($author_id, $question_id, $subject, $publication_date)
    {
        $query = 'INSERT INTO answers (author_id,question_id,subject,publication_date) VALUES (:author_id,:question_id,:subject,:publication_date)';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':author_id', $author_id);
        $ps->bindValue(':question_id', $question_id);
        $ps->bindValue(':subject', $subject);
        $ps->bindValue(':publication_date', $publication_date);
        $ps->execute();
    }

    # -------------------------------------------------------------------------------- #


    # ADMIN CONTROLLER
    # Select all members except the current user from the database for the admin view
    public function select_all_members($memberId)
    {
        $query = 'SELECT * FROM members WHERE member_id NOT IN (SELECT member_id FROM members WHERE member_id=:id) ORDER BY login';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id',$memberId);
        $ps->execute();
        $members = array();
        while ($row = $ps->fetch()) {
            $members[] = new Member($row->member_id, $row->login, $row->lastname, $row->firstname, $row->mail, $row->admin, $row->suspended);
        }
        return $members;
    }

    # Changing the state of the member to suspended
    public function suspend_member($memberid)
    {
        $query = 'UPDATE members SET suspended=1 WHERE member_id=:id';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id', $memberid);
        $ps->execute();
    }

    # Changing the state of the member to unsuspended
    public function unsuspend_member($memberid)
    {
        $query = 'UPDATE members SET suspended=0 WHERE member_id=:id';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id', $memberid);
        $ps->execute();
    }

    # Upgrading member to admin
    public function upgrade_to_admin($memberid)
    {
        $query = 'UPDATE members SET admin=1 WHERE member_id=:id';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id', $memberid);
        $ps->execute();
    }

    # Downgrading admin to basic member
    public function demote_admin($memberid)
    {
        $query = 'UPDATE members SET admin=0 WHERE member_id=:id';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id', $memberid);
        $ps->execute();
    }

    # -------------------------------------------------------------------------------- #

    # PROFILE CONTROLLER
    # Select a member's questions via his id
    public function select_questions_for_profile($memberId)
    {
        $query = 'SELECT Q.*, C.* FROM questions Q, categories C WHERE Q.author_id = :id AND Q.category_id = C.category_id ORDER BY Q.question_id DESC';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id', $memberId);
        $ps->execute();

        $questions = array();
        while ($row = $ps->fetch()) {
            $category = new Category($row->category_id, $row->name);
            $questions[] = new Question($row->question_id, null, $category, null, $row->title, null, null, $row->publication_date, null);
        }
        return $questions;
    }

    # -------------------------------------------------------------------------------- #

    # QUESTION CONTROLLER

    # Select question from id
    public function select_question($question_id)
    {
        $query = 'SELECT Q.*, M.* FROM questions Q, members M  WHERE Q.question_id=:id AND M.member_id=Q.author_id';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id', $question_id);
        $ps->execute();
        if($ps->rowCount() == 0)
            return null;
        $row = $ps->fetch();
        $author = new Member($row->member_id, $row->login, null, null, null, null, null);
        $question = new Question($row->question_id, $author, null, null, $row->title, $row->subject, $row->state, $row->publication_date, null);

        $answers = $this->select_answers_authors_votes($question, $row->best_answer_id);
        $answers[0] = $question->bestAnswer();
        $question->setAnswers($answers);
        return $question;
    }

    # Select all questions + their respective author from the category with the specified id + votes
    private function select_answers_authors_votes($question, $bestAnswerId)
    {
        $query = 'SELECT A.answer_id,sum(V.liked) as \'likes\',count(V.answer_id) as \'votes\' FROM answers A , votes V WHERE A.question_id=:id AND V.answer_id=A.answer_id GROUP BY A.answer_id';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id', $question->questionId());
        $ps->execute();
        $votes = array();
        while ($row = $ps->fetch()) {
            $votes[$row->answer_id][0] = $row->likes;
            $votes[$row->answer_id][1] = $row->votes - $row->likes;
        }

        $query = 'SELECT A.*, M.*  FROM answers A, members M   WHERE  A.author_id= M.member_id AND A.question_id = :id ORDER BY A.answer_id';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id', $question->questionId());
        $ps->execute();
        $answers = array();
        $answers[0] = null;
        while ($row = $ps->fetch()) {
            $member = new Member($row->member_id, $row->login, $row->lastname, $row->firstname, $row->mail, $row->admin, $row->suspended);
            $likes = 0;
            $dislikes = 0;
            if (array_key_exists($row->answer_id, $votes)) {
                $likes = $votes[$row->answer_id][0];
                $dislikes = $votes[$row->answer_id][1];
            }
            $answer = new Answer($row->answer_id, $member, $row->subject, $row->publication_date, $likes, $dislikes);
            if($row->answer_id == $bestAnswerId) {
                $question->setBestAnswer($answer);
            }
            $answers[] = $answer;
        }
        return $answers;
    }

    # Set the question's best answer to null
    public function remove_best_answer($questionId)
    {
        $query = "UPDATE questions SET best_answer_id=null,state=null WHERE question_id=:id";
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id', $questionId);
        $ps->execute();
    }

    # Set a question's state to open
    public function open_question($questionid)
    {
        $query = "UPDATE questions SET state=null WHERE question_id=:id";
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id', $questionid);
        $ps->execute();
    }

    public function duplicate_question($questionid)
    {
        $query = "UPDATE questions SET best_answer_id=null,state='duplicated' WHERE question_id=:id";
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id', $questionid);
        $ps->execute();
    }

    public function delete_question($questionid)
    {
        $query = 'DELETE FROM questions WHERE question_id=:id';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id', $questionid);
        $ps->execute();
    }

    # Select question from id
    public function select_question_for_best_answer($idQuestion)
    {
        $query = 'SELECT Q.* FROM questions Q WHERE Q.question_id=:id';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id', $idQuestion);
        $ps->execute();
        $row = $ps->fetch();
        return new Question($row->question_id, null, null, null, null, null, $row->state, null, null);
    }

    # Add the best answer at the question passed by parameters
    public function set_as_best_answer($questionId, $answerId)
    {
        $query = 'UPDATE questions SET best_answer_id=:answerid, state=:solved WHERE question_id=:id';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':answerid', $answerId);
        $ps->bindValue(':id', $questionId);
        $ps->bindValue(':solved', 'solved');
        $ps->execute();
    }

    # Delete the best answer
    public function delete_best_answer($questionId)
    {
        $query = 'UPDATE questions SET best_answer_id=null,state=null WHERE question_id=:id';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id', $questionId);
        $ps->execute();
    }

    # Insert a vote in the database
    public function insert_vote($memberId, $answerId, $vote)
    {
        $query = "INSERT INTO votes  VALUES ($memberId,$answerId,$vote)";
        $ps = $this->_db->prepare($query);
        $ps->execute();
    }

    public function select_admins()
    {
        $query = "SELECT * FROM members WHERE admin=1 ORDER BY login";
        $ps = $this->_db->prepare($query);
        $ps->execute();
        $admins = array();
        while($row = $ps->fetch()) {
            $admins[] = new Member(null, $row->login, null, null, null, 0, 0);
        }
        return $admins;
    }
}