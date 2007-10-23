#!/usr/bin/perl

use CGI;
use DBI;

my $prefix = "/www/openwaygroup/";
my $connstr;
my $dbh;

#$connstr = 'DBI:mysql:openway_db:192.168.1.3';
#$dbh = DBI->connect($connstr, 'openway_db', 'TestDB!');

$connstr = 'DBI:mysql:openway_db:mysql.openwaygroup.com';
$dbh = DBI->connect($connstr, 'openway_db', 'TestDB!');



my $query = new CGI;

#1. Определить, какой файл и куда записать

my $filename = $query->param("filename-0");
$filename =~ s/.*[\/\\](.*)/$1/;

# if (filename кончается на .php) - добавить .html  - типа безопасность
# (ничего другого кроме php сервер как-то по-особенному исполнять не должен)
$filename =~ s/(.*\.php)/$1\.html/;


$description = $query->param("description-0");
$course = $query->param("course");
$upload_filehandle = $query->upload("filename-0");

$upload_dir = $prefix . "uploads/" . $course;

my $query_string = qq{ select id, shorttitle from courses where handle = '$course' };
my $dbquery = $dbh->prepare($query_string);
$dbquery->execute();
my ($courseid, $shorttitle) = $dbquery->fetchrow_array();

#1.5 Проверить существование каталога, если нет - создать

if (!opendir(DIR, "$upload_dir")) {
  if (!opendir(DIR2, $prefix . "courses/" . $course)) {
    if (!mkdir($prefix . "courses/" . $course, 0775)) {
      print "Content-type: text/html\n\n";
      print "error: $!";
      exit();
    }
    chmod 0775, $prefix . "courses/" . $course;
  }
  if (!mkdir("$upload_dir", 0775)) {
    print "Content-type: text/html\n\n";
    print "error: $!";
    exit();
  } else {
    chmod 0775, "$upload_dir";
#создать index.php
    $index_name = $prefix . "courses/$course/index.php";
    open INDEX, ">$index_name";
    $text = "<?php ob_start();?><!-- !!! auto generated file, delete this when you edit it !!! -->
<?php
  include_once(\"../../inc/connect.php\"); 
  include_once(\"../../inc/header.php\");  
?>
<div id='main'><h2>$shorttitle</h2><?php include \"$prefix/courses/show_ups.php\" ?></div>
<?php
  include_once(\"../../inc/footer.php\");  
?>";
    print INDEX $text;
    close INDEX;
    chmod 0664, $index_name;
 }
}

#2. Записать туда файл

open UPLOADFILE, ">$upload_dir/$filename";

binmode UPLOADFILE;
while ( <$upload_filehandle> )
{
  print UPLOADFILE;
}
close UPLOADFILE;
chmod 0664, "$upload_dir/$filename";

#3. сделать запись в базе и редирект на первую страницу
$query_string = "REPLACE INTO `uploads` (`course` , `filename` , `description` ) VALUES ('$courseid', '$filename', " . $dbh->quote($description) . ")";

$dbh->do($query_string);
$dbquery->finish();
$dbh->disconnect();

print "Location: http://" . $ENV{"HTTP_HOST"} . "/admin/upload/upload.php\n\n";
