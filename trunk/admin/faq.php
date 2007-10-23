<?php
  header("Content-type: text/html; charset=UTF-8");
  print('<?xml version="1.0"?>');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
  <head>
    <title><?php if(!empty($title)) { print($title . ' - '); } print("Академия Современного Программирования"); ?></title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="/style/main.css" media="screen, tv" />
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />
  </head>
  <body>
    <div id="header">
      <ul id="menu">
      <?php include("menu.php"); ?>
      </ul>
    </div>
    <div id="content">
      <div id="main">
      <h2>Новые вопросы</h2>
      <?php
        include 'inc/connect.php';
        $res = mysql_query("select * from faq where visible = 'N'");
        if(mysql_num_rows($res) > 0) {
          print("<ul>");
          while($row = mysql_fetch_assoc($res)) {
            print('<li><strong>' . $row['question'] . '</strong><br />');
            print($row["answer"] . ' (<a href="faq.edit.php?id=' . $row["id"] . '">редактировать</a>) (<a href="faq.delete.php?id=' . $row["id"] . '" onclick="return confirm(\'Вы действительно хотите удалить этот вопрос?\');">удалить</a>)');
            print('</li>');
          }
          print("</ul>");
        } else {
          print("<p>Отсутствуют.</p><p></p>");
        }
        mysql_free_result($res);
      ?>
      <h2>Вопросы на сайте</h2>
      <?php
        $res = mysql_query("select * from faq where visible = 'Y' order by id desc");
        if(mysql_num_rows($res) > 0) {
          print("<ul>");
          while($row = mysql_fetch_assoc($res)) {
            print('<li><strong>' . $row['question'] . '</strong><br />');
            print($row["answer"] . ' (<a href="faq.edit.php?id=' . $row["id"] . '">редактировать</a>) (<a href="faq.delete.php?id=' . $row["id"] . '" onclick="return confirm(\'Вы действительно хотите удалить этот вопрос?\');">удалить</a>)');
            print('</li>');
          }
          print("</ul>");
        } else {
          print("<p>Отсутствуют.</p><p></p>");
        }
        mysql_free_result($res);
        mysql_close();
      ?>
      </div>
    </div>
    <div id="copyright">
      Copyright © 2005–2007 Академия Современного Программирования. Все права защищены.
    </div>
  </body>
</html>
