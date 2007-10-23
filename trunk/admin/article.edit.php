<?php
  include "inc/forum.php";

  include 'inc/connect.php';

  $months = array("января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");

  if(array_key_exists("id", $_POST)) {
    if($_POST["id"] == 0) {
      $sql = "insert into news(title, date, text, visible, created) values('" . addslashes($_POST["title"]) . "', '" . $_POST["year"] . '-' . $_POST["month"] . '-' . $_POST["day"] . "', '" . addslashes($_POST["text"]) . "', '" . ($_POST["visible"] == 'Y' ? 'Y' : 'N') . "', now())";
    } else {
      $sql = "update news set title = '" . addslashes($_POST["title"]) . "', date = '" . $_POST["year"] . '-' . $_POST["month"] . '-' . $_POST["day"] . "', text = '" . addslashes($_POST["text"]) . "', visible = '" . ($_POST["visible"] == 'Y' ? 'Y' : 'N') . "' where id = " . intval($_POST["id"]);
    }
    mysql_query($sql);

    if(array_key_exists("addlink", $_POST)) {
      mysql_select_db("academyforum");
      $thread = forumNewThread(2, 'Borland Academy', 9, $_POST["title"], $_POST["text"]);
      mysql_select_db("academy");
      $sql = "update news set discusslink = '/forum/showthread.php?t=" . $thread["thread"] . "' where id = " . $_POST["id"];
      mysql_query($sql);
    }
    if(array_key_exists("removelink", $_POST)) {
      $sql = "update news set discusslink = null where id = " . $_POST["id"];
      mysql_query($sql);
    }

    header("Location: http://" . $_SERVER["HTTP_HOST"] . "/admin/news.php");
    exit;
  }

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
    <style type="text/css">
      option { padding-right: 1em; }
      #main, #main table { width: 100%; }
      input.textbox, textarea { width: 100%; }
    </style>
  </head>
  <body>
    <div id="header">
      <ul id="menu">
      <?php include("menu.php"); ?>
      </ul>
    </div>
    <div id="content">
      <div id="main">
      <?php
	if(array_key_exists("id", $_REQUEST)) {
	  $res = mysql_query("select * from news where id = " . intval($_REQUEST["id"]));
	  $row = mysql_fetch_assoc($res);
	  list($year, $month, $day) = explode('-', $row["date"]);
	  $row["date"] = mktime(0, 0, 0, $month, $day, $year);
  	  mysql_free_result($res);
	} else {
	  $row = array(
	    "title" => "",
	    "date" => time(),
	    "text" => ""
	  );
	}
	mysql_close();
      ?>
  <form action="article.edit.php" method="post">
  <input type="hidden" name="id" value="<?php print(intval($_REQUEST["id"])); ?>" />
    <table>
      <col width="20%" />
      <col width="80%" />
      <tr><td><label for="input-title">Заголовок</label></td><td><input type="text" id="input-title" name="title" value="<?php print(htmlspecialchars(stripslashes($row["title"]))); ?>" title="Заголовок" class="textbox" /></td></tr>
      <tr><td><label for="input-date">Дата</label></td><td>
      <select name="day"><?php
        for($i = 1; $i <= 31; $i++) {
	  print('<option value="' . $i . '"' . ($i == date('j', $row["date"]) ? ' selected="selected"' : '') . '>' . $i . '</option>');
	}
      ?></select>
      <select name="month"><?php
        for($i = 1; $i <= 12; $i++) {
	  print('<option value="' . $i . '"' . ($i == date('n', $row["date"]) ? ' selected="selected"' : '') . '>' . $months[$i-1] . '</option>');
	}
      ?></select>
      <select name="year"><?php
        for($i = 2004; $i <= 2007; $i++) {
	  print('<option value="' . $i . '"' . ($i == date('Y', $row["date"]) ? ' selected="selected"' : '') . '>' . $i . '</option>');
	}
      ?></select>
      </td></tr>
      <tr><td><label for="input-text">Текст</label></td><td><textarea id="input-text" name="text"><?php print(stripslashes($row["text"])); ?></textarea></td></tr>
      <tr><td></td><td class="languages">
        <label for="input-visible" style="width: auto;"><input type="checkbox" id="input-visible" name="visible" value="Y" <?php if($row["visible"] == 'Y') { print('checked="checked" '); } ?>/>Показывать эту новость на сайте</label>
      </td></tr>
      <?php if(array_key_exists("id", $_REQUEST)) { ?>
      <tr><td></td><td class="submit"><?php if(!$row["discusslink"]) { print('<input type="submit" id="form-addlink" name="addlink" value="Добавить в форум" />'); } else { print('<a href="' . $row["discusslink"] . '">Обсуждение в форуме</a> <input type="submit" id="form-removelink" name="removelink" value="Удалить ссылку" />'); } ?></td></tr>
      <?php } ?>
      <tr><td></td><td class="submit"><input type="submit" id="form-submit" name="edit" value="<?php if(array_key_exists("id", $_REQUEST)) { print('Сохранить'); } else { print('Добавить'); } ?>" /></td></tr>
    </table>
  </form>
      </div>
    </div>
    <div id="copyright">
      Copyright © 2005–2007 Академия Современного Программирования. Все права защищены.
    </div>
  </body>
</html>
