<?php
session_start();

function clearSession($id)
{
    $_SESSION[$id] = array();
    unset($_SESSION[$id]);
}

?>