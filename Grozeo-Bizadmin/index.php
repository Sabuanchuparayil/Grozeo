<?php
	
/**
 * Created on 21-Jul-09
 * @author : Ratheesh Kumar CK <ratheesh@saturn.in>
 *
 * Main file of the Project. It is the handler of each request
 * Every request is routed through this file.
 */
require_once(__DIR__ . '/security_bootstrap.php');
session_start();
session_regenerate_id();



$GLOBALS['NotCarego'] = true;
// set the session cookie parameters
if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params(0, dirname($_SERVER['PHP_SELF']), $_SERVER['SERVER_NAME']);
} elseif (function_exists('ini_set')) {
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.cookie_path', dirname($_SERVER['PHP_SELF']));
    ini_set('session.cookie_domain', $_SERVER['SERVER_NAME']);
}

/** Set Include Path * */
set_include_path(get_include_path() . PATH_SEPARATOR . "./includes");
/** EOF Include Path * */
//Set Document Root
define('ROOT', dirname(__FILE__));

//Define Includes Path for further Use
define('INCLUDE_PATH', ROOT . "/includes");

//Include Main Libraries
require(INCLUDE_PATH . '/config.php');

include INCLUDE_PATH . "/phpmyprofiler.php";

require(INCLUDE_PATH . '/lib.php');
require(INCLUDE_PATH . '/lang/lang-en.php');
require(INCLUDE_PATH . '/functions.php');
require(ROOT . '/finascop_config/config.php');
require(ROOT . '/includes/config.php');
//require_once(ROOT . '/finascop_config/lib.php');
//require_once(EXTERNAL_LIBRARY_PATH);

include(ROOT . '/class.smtpSend.php');
//Create DB Object
$db = new sqlDb(DSN);

$profileLog = new phpMyProfiler(false,false,false,ROOT . CACHE_PATH);
$profileLog->setLink($db->link);

$supportdb = new sqlDb(SUPPORTDSN);
//$sqlservdb = new SqlSrvDB(SQLSERVDSN);

//Initial OP variable. $op controls, which operation of the
//requested module needs to be executed
$op = false;
//Initialize $module, which module needs to be executed
$module = 'ui';

//Import GET/POST/Cookie variables safely
$module = $_GET['module'] ?? ($_POST['module'] ?? 'ui');
$op = $_GET['op'] ?? ($_POST['op'] ?? false);

$mf = $_GET['mf'] ?? ($_POST['mf'] ?? false);
$ak = false;
if (isset($_GET['ak']))
    $ak = $_GET['ak'];
if (isset($_POST['ak']))
    $ak = $_POST['ak'];

// CSRF protection: validate token on all POST requests except login/auth
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $module !== 'auth') {
    if (!verify_csrf_token()) {
        header('Content-Type: application/json');
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'CSRF token validation failed. Please refresh the page and try again.']);
        exit;
    }
}
// Generate CSRF token for the session (available via csrf_field() in forms)
generate_csrf_token();

require_once(INCLUDE_PATH . "/finascop_common_functions.php");

if (isset($_SESSION['admin'])) {
    if ($_SESSION['admin']->typId == 3 || $_SESSION['admin']->typId == 4) {
        $db->query('set @entryBr=' . $_SESSION['admin']->typdetsid);
    } else {
        $db->query('set @entryBr=1');
    }
    $db->query('set @gAppuserID="' . $_SESSION['admin']->UserId . '"');
    $db->query('set @ComputerName="' . Activity::getIpAddr() . '"');

	if ($module != 'auth' && $module != 'ui' && $op != 'logout' && $module != 'access'&& $module != 'vlsl_upload'&& $module != 'mypha_prescription'&& $op != 'mapPrescriptionMedicine'&& $module != 'vlsl_upload'&& $module != 'finascop_stock_upload'&& $op != 'buildStockUploadCsv') {
		if (isset($_GET['apikey']))
			$api_key = $_GET['apikey'];
		if (isset($_GET['tstamp']))
			$tstamp = $_GET['tstamp'];	
		if (isset($_POST['apikey']))
			$api_key = $_POST['apikey'];
		if (isset($_POST['tstamp']))
			$tstamp = $_POST['tstamp'];			
//        if (empty($api_key) || empty($tstamp)) {
//            echo "{'invalid_api1':true}";
//            exit;
//        } else if (!empty($api_key)) {
//            if (IsValidapikeyLocal($api_key) == false) {
//                echo "{'invalid_api2':true}";
//                session_destroy();
//                setcookie("remember_uidnr_admin", false, (time() - 1));
//                exit;
//            }
//        }
    }


    if (!user_access($module, $op)) {
        access_denied($module, $op);
    } else {
        //Include/Iniate the Module files if the user is Authenticated
        include(ROOT . "/init_modules.php");
    }
} else {
    //If Session Expired, then redirect the screen.	
    if ($module == 'email_action' or $module == 'vblogin') {
        include("./init_modules.php");
        exit;
    }
    if ($module != 'auth' && $module != 'ui') {
        echo "{session_expired:true}";
        exit;
    }
    $module = 'auth';
    //Include/Iniate the Module files since this module not required authentication
    include(ROOT . "/init_modules.php");
}

