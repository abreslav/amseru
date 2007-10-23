<?php
  $myRoot = "\\www\\openwaygroup\\";
    $user = "qwe";
  if (isset($_SERVER['HTTP_AUTH_USER'])) {
    $user = $_SERVER['HTTP_AUTH_USER'];
  } else if (isset($_SERVER['REMOTE_USER'])) {
    $user = $_SERVER['REMOTE_USER'];
  } else if (isset($_SERVER['REDIRECT_REMOTE_USER'])) {
    $user = $_SERVER['REDIRECT_REMOTE_USER'];
  }
?>
