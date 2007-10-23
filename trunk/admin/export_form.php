<?

include ("inc/connect.php");

$txt = '';

$query='show columns from studentsforms3';
$data = mysql_query($query) or die($query);

while ($row = mysql_fetch_array($data)) {
	$txt .='"'.$row[0].'";';

}
$txt .="\n";

$query = 'select * from studentsforms4';
$data = mysql_query($query) or die($query);
while($row = mysql_fetch_assoc($data)) {
//var_dump($row);
	foreach ($row as $index => $data1) {
		$txt .='"'.iconv('UTF-8','WINDOWS-1251',$data1).'";';
	}
$txt .="\n";
}



// Tell the web client to expect a CSV file
header('Content-Type: text/csv');
// Tell the web client to view the CSV file in a seprate program
header('Content-Disposition: attachment; filename="report.csv"');

echo $txt;

?>
