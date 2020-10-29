<?php

class RegisterController
{

    private $_db;

    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function run()
    {

        # User already connected
        if (isset($_SESSION['login'])) {
            header('Location: index.php');
            die();
        }

        # Attempting to register...

        # Display notification if any of the fields are empty
        if (!empty($_POST)) {
            if (preg_match('/^\s*$/', $_POST['lastname']) || preg_match('/^\s*$/', $_POST['firstname']) || preg_match('/^\s*$/', $_POST['mail']) || preg_match('/^\s*$/', $_POST['login']) || empty($_POST['password'])) {
                $notification = 'Please fill in all fields';
            } else {
                # All fields are completed, verification...
                if (isset($_POST['lastname'][25]))
                    $notification = 'Please enter a shorter lastname';
                elseif (isset($_POST['firstname'][25]))
                    $notification = 'Please enter a shorter firstname';
                elseif (isset($_POST['login'][25]))
                    $notification = 'Please enter a shorter login';
                # Invalid e-mail address
                elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $_POST['mail']) || isset($_POST['mail'][50]))
                    $notification = 'Please enter a valid mail address';
                elseif (isset($_POST['password'][60]))
                    $notification = 'Please enter a shorter password';
                # Successfully registered
                else {
                    # Catch error if login already exists
                    try {
                        # Register member to the database
                        $this->_db->insert_member($_POST['lastname'], $_POST['firstname'], $_POST['mail'], $_POST['login'], password_hash($_POST['password'], PASSWORD_BCRYPT));

                        # Redirection to login after being successfully registered
                        header('Location: index.php?action=login');
                        die();
                    } catch (PDOException $e) {
                        $notification = 'This username already exists';
                    }
                }
            }
        }
        require_once(VIEWS . 'register.php');
    }
}