<?php
# Start a new session for each connexion from a new browser
session_start();

# Defining constants
define('VIEWS', "views/");
define('MODELS', 'models/');
define('CONTROLLERS', 'controllers/');
define('IMAGES', 'views/images/');
define('TITLE', 'ClassNotFound');
define('SESSION_ID', session_id());

# Automated loading of classes from models
function loadClass($class)
{
    require_once(MODELS . $class . '.class.php');
}

spl_autoload_register('loadClass');

# Connexion to the database;
require_once(MODELS . 'Db.php');
$db = Db::getInstance();

# This array contains all the categories from the database as Category objects
$categories = $db->select_categories();

if (isset($_SESSION['login'])) {
    $member = unserialize($_SESSION['login']);
    $memberLogin = $member->login(); //TODO EXAM html_login();
    if ($member->admin() == 1)
        $isAdmin = true;
}

if(isset($_POST['search']))
    $search = htmlspecialchars($_POST['search']);

# Show header view
require_once(VIEWS . 'header.php');

# If action is empty, define action=homepage
if (empty($_GET['action'])) {
    $_GET['action'] = 'HomepageController';
}

# Create a $controller variable containing the correct controller according to the action
switch ($_GET['action']) {
    case 'login':
        require_once(CONTROLLERS . 'LoginController.php');
        $controller = new LoginController($db);
        break;
    case 'logout':
        require_once(CONTROLLERS . 'LogoutController.php');
        $controller = new LogoutController();
        break;
    case 'register':
        require_once(CONTROLLERS . 'RegisterController.php');
        $controller = new RegisterController($db);
        break;
    case 'question':
        require_once(CONTROLLERS . 'QuestionController.php');
        $controller = new QuestionController($db);
        break;
    case 'admin':
        require_once(CONTROLLERS . 'AdminController.php');
        $controller = new AdminController($db);
        break;
    case 'newAnswer':
        require_once(CONTROLLERS . 'NewAnswerController.php');
        $controller = new NewAnswerController($db);
        break;
    case 'profile':
        require_once(CONTROLLERS . 'ProfileController.php');
        $controller = new ProfileController($db);
        break;
    case 'editQuestion':
        require_once(CONTROLLERS . 'EditQuestionController.php');
        $controller = new EditQuestionController($db);
        break;
    default:
        require_once(CONTROLLERS . 'HomepageController.php');
        $controller = new HomepageController($db);
        break;
}

# Launch the controller defined by the previous switch statement
$controller->run();

$admins = $db->select_admins();
$nbAdmins = count($admins);

# Show footer view
require_once(VIEWS . 'footer.php');