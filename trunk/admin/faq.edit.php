<?php
  if(count($_POST)) {
    include 'inc/connect.php';
    mysql_query("update faq set question = '" . addslashes($_POST["question"]) . "', answer = '" . addslashes($_POST["answer"]) . "', visible = '" . ($_POST["visible"] == 'Y' ? 'Y' : 'N') . "' where id = " . intval($_POST["id"]));

    header("Location: http://" . $_SERVER["HTTP_HOST"] . "/admin/faq.php");
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
  </head>
  <body>
    <div id="header">
      <ul id="menu">
      <?php include("menu.php"); ?>
      </ul>
    </div>
    <div id="content">
      <div id="main">
      <h2>Редактирование вопроса</h2>
      <?php
        mysql_connect("localhost", "academy", "eishe5Ae");
        mysql_select_db("academy");
        $res = mysql_query("select * from faq where id = " . intval($_GET["id"]));
        if(mysql_num_rows($res) > 0) {
          $row = mysql_fetch_assoc($res);
?>
  <form action="faq.edit.php" method="post">
  <input type="hidden" name="id" value="<?php print(intval($_GET["id"])); ?>" />
    <table>
      <col width="20%" />
      <col width="80%" />
      <tr><td><label for="input-question">Вопрос</label></td><td><textarea id="input-question" name="question"><?php print($row["question"]); ?></textarea></td></tr>
      <tr><td><label for="input-answer">Ответ</label></td><td><textarea id="input-answer" name="answer"><?php print($row["answer"]); ?></textarea></td></tr>
      <tr><td></td><td class="languages">
        <label for="input-visible" style="width: auto;"><input type="checkbox" id="input-visible" name="visible" value="Y" <?php if($row["visible"] == 'Y') { print('checked="checked" '); } ?>/>Показывать этот вопрос на сайте</label>
      </td></tr>
      <tr><td></td><td class="submit"><input type="submit" id="form-submit" value="Сохранить" /></td></tr>
    </table>
  </form>
<?php
        } else {
          print("<p>Вопрос не найден.</p>");
        }
        mysql_free_result($res);
      ?>
      </div>
    </div>
    <div id="copyright">
      Copyright © 2005–2007 Академия Современного Программирования. Все права защищены.
    </div>
  </body>
</html>
