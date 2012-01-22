<?php

if (!isset($filename)) {
	$filename = 'Report.xls';
}

header ('Last-Modified: ' . gmdate('D,d M YH:i:s') . ' GMT');
header ('Cache-Control: no-cache, must-revalidate');
header ('Pragma: no-cache');
header ('Content-type: application/vnd.ms-excel; charset=UTF-8');
header ('Content-Disposition: attachment; filename="' . $filename . '.xls"' );
?>
<?php echo $content_for_layout ?> 
