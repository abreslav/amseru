<?php
  include 'inc/connect.php';
  mysql_query("delete from news where id = " . intval($_GET["id"]));
  mysql_close();
  header("Location: http://" . addslashes($_SERVER["HTTP_HOST"]) . "/admin/news.php");
?>
