<?php
  header("Content-type: text/html; charset=UTF-8");
  print('<?xml version="1.0"?>');

  $months = array("января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");
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
      <div id="main"><?php
        include 'inc/connect.php';

        $page = intval($_GET["page"]);
        if($page <= 0) {
          $page = 1;
        }
        $perPage = 10;
        
        $res = mysql_query("select * from studentsforms4 order by date desc limit " . ($perPage * ($page - 1)) . "," . $perPage);

        print('<ol start="' . ($perPage * ($page - 1) + 1) . '">');
        while($row = mysql_fetch_assoc($res)) {
          list($month, $day) = explode('-', substr($row["date"], 5, 5));
          print('<li><p><strong>' . stripslashes($row['firstname']) . ' ' . stripslashes($row['patronymic']) . ' ' . stripslashes($row['lastname']) . '</strong> (' . intval($day) . ' ' . $months[$month-1] . ')</p>');
          print('<p><strong>Место учебы:</strong> ' . stripslashes($row["institute"]) . ', ' . stripslashes($row["faculty"]) . ', ' . stripslashes($row["department"]) . ', ' . stripslashes($row["year"]) . ' курс</p>');
          print('<p><strong>E-mail:</strong> <a href="mailto:' . $row["email"] . '">' . $row["email"] . '</a>, <strong>телефон:</strong> ' . stripslashes($row["phone"]) . '</p>');
          print('<p><strong>О себе:</strong> ' . stripslashes($row["about"]) . '</p>');
          print('</li>');
        }
        mysql_free_result($res);
        print('</ol>');
      ?>
      <p>Страницы: <?php
        list($count) = mysql_fetch_row(mysql_query("select count(*) from studentsforms4"));
        $pages = ceil($count / $perPage);
        for($i = 1; $i <= $pages; $i++) {
          if($i == $page) {
            print("<strong>$i</strong> ");
          } else {
            print("<a href=\"/admin/forms.php?page=$i\">$i</a> ");
          }
        }
        mysql_close();
      ?></p>
        <p>
                <a href="export_form.php" target="_blank">Download CSV sheet</a>
</p>
      </div>
    </div>
    <div id="copyright">
      Copyright © 2005–2007 Академия Современного Программирования. Все права защищены.
    </div>
  </body>
</html>
