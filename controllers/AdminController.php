<?php

class AdminController
{
    private $_db;

    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function run()
    {
        # If the user is not connected or is not an admin --> homepage
        if (!isset($_SESSION['login']) || unserialize($_SESSION['login'])->admin() == 0) {
            header('Location: index.php');
            die();
        }

        # If an admin clicked on a button
        if (!empty($_POST)) {
            # Suspend a member's account
            if (isset($_POST['suspend'])) {
                $this->_db->suspend_member($_POST['member_id']);
            } # Activate a member's account
            else if (isset($_POST['activate'])) {
                $this->_db->unsuspend_member($_POST['member_id']);
            } # Upgrade a member to admin grade
            else if (isset($_POST['upgrade'])) {
                $this->_db->upgrade_to_admin($_POST['member_id']);
            } # Demote an admin to basic member
            else {
                $this->_db->demote_admin($_POST['member_id']);
            }
        }

        # Selecting all members to display in admin view
        $members = $this->_db->select_all_members(unserialize($_SESSION['login'])->memberId());

        $nbMembers = count($members);

        if ($nbMembers == 0)
            $notification = 'No other user found.';

        require_once(VIEWS . 'admin.php');
    }
}