<?php

class Member
{
    private $_member_id;
    private $_login;
    private $_lastName;
    private $_firstName;
    private $_mail;
    private $_admin;
    private $_suspended;

    public function __construct($member_id, $login, $lastName, $firstName, $mail, $admin, $suspended)
    {
        $this->_member_id = $member_id;
        $this->_login = $login;
        $this->_lastName = $lastName;
        $this->_firstName = $firstName;
        $this->_mail = $mail;
        $this->_admin = $admin;
        $this->_suspended = $suspended;
    }


    public function memberId()
    {
        return $this->_member_id;
    }

    public function login()
    {
        return $this->_login;
    }

    public function html_login()
    {
        return htmlspecialchars($this->_login);
    }

    public function lastName()
    {
        return $this->_lastname;
    }

    public function html_lastName()
    {
        return htmlspecialchars($this->_lastname);
    }

    public function firstName()
    {
        return $this->_firstName;
    }

    public function html_firstName()
    {
        return htmlspecialchars($this->_firstName);
    }

    public function mail()
    {
        return $this->_mail;
    }

    public function html_mail()
    {
        return htmlspecialchars($this->_mail);
    }

    public function admin()
    {
        return $this->_admin;
    }

    public function suspended()
    {
        return $this->_suspended;
    }
}