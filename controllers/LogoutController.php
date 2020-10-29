<?php

class LogoutController
{
    public function __construct() {

    }

    public function run(){

        # Reinitialize $_SESSION variable
        $_SESSION = array();

        # No logout.php (view) --> redirect homepage view
        header("Location: index.php");
        die();
    }
}