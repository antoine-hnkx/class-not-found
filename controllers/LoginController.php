<?php

class LoginController
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

        # Redirected from another page
        if (isset($_SESSION['error']) && !is_null($_SESSION['error'])) {
            $notification = $_SESSION['error'];
            $_SESSION['error'] = null;
        }

        # Attempting to connect...

        # Display notification if any of the fields are empty
        if (!empty($_POST)) {
            if (empty($_POST['login']) and empty($_POST['password'])) {
                $notification = 'Enter your login and password';
            } else if (!empty($_POST['login']) and empty($_POST['password'])) {
                $notification = 'Enter a password';
            } else if (empty($_POST['login']) and !empty($_POST['password'])) {
                $notification = 'Enter a login';
            } else {
                # All fields are completed, verification...

                # Select the member from the provided login and password ($member == null if no such login or password incorrect)
                $member = $this->_db->verify_member($_POST['login'], $_POST['password']);

                # No such login found OR password incorrect
                if (is_null($member)) {
                    $notification = 'Your login/password is incorrect';
                } # Account suspended
                elseif ($member->suspended()) {
                    $notification = 'Your account is suspended';
                } # Authentication succeeded
                else {
                    $_SESSION['login'] = serialize($member);
                    # Redirection to homepage after being successfully connected
                    header('Location: index.php');
                    die();
                }
            }
        }
        # Show login view
        require_once(VIEWS . 'login.php');
    }
}