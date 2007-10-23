<?php
  include 'inc/connect.php';
  mysql_query("delete from faq where id = " . intval($_GET["id"]));
  header("Location: http://" . addslashes($_SERVER["HTTP_HOST"]) . "/admin/faq.php");
?>
