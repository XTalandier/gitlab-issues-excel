<?php
ini_set('display_errors', 'on');
date_default_timezone_set('Europe/Paris');
require dirname(__FILE__) . '/vendor/autoload.php';
require dirname(__FILE__) . '/conf.php';

/**
 * Code
 */
$api = '/api/v3/projects/' . $projetID . '/issues?per_page=100&page=';
$continuer = true;
$issues = array();
$page = 1;
while ($continuer) {
    echo $page . "\n";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . $api . $page);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $headers = [
        'PRIVATE-TOKEN: ' . $token
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $server_output = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($server_output);
    $n = count($json);
    if ($n == 0 || ++$page > 50) {
        $continuer = false;
    }
    for ($i = 0; $i < $n; $i++) {
        $issues[] = $json[$i];
    }
}


/**
 * EXCEL
 */
//print_r($data);
$n = count($issues);
$excelArray = array();
for ($i = 0; $i < $n; $i++) {
    $excelArray[] = array(
        'State' => $issues[$i]->state,
        'Author' => $issues[$i]->author->username,
        'Title' => $issues[$i]->title,
        'Date' => $issues[$i]->created_at,
        'Update' => $issues[$i]->updated_at,
        'Labels' => implode(', ', $issues[$i]->labels)
    );
}

$n = count($excelArray);
$excelHeader = array();
$excelCells = array();
for ($i = 0; $i < $n; $i++) {
    $cur = array();
    foreach ($excelArray[$i] as $k => $v) {
        if (!in_array($k, $excelHeader)) {
            $excelHeader[] = $k;
        }
        $cur[] = $v;
    }
    $excelCells[] = $cur;
}

$objPHPExcel = new PHPExcel();
$objPHPExcelSheet = $objPHPExcel->getSheet(0);
$objPHPExcelSheet->fromArray($excelHeader, NULL);
$objPHPExcelSheet->fromArray($excelCells, NULL, 'A2');
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('issues' . ".xlsx");

echo "OK";
