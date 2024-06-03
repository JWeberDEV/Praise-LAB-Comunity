<?php
require_once(__DIR__ . "/_session.php");

if (empty($_SESSION['userAuth']["id"]) || empty($_SESSION['userAuth']["idProfile"])) {
  header("location: /landing_page.php");
  exit;
}

function logout()
{
  ob_clean();
  session_unset();
  session_destroy();
  $_SESSION = array();
  header("location: /login.php");
  exit;
}

if (isset($_GET['opt']) && $_GET['opt'] == "logout") {
  logout();
}

?>