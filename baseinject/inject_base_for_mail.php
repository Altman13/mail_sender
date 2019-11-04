<?php

require_once '../config/config_pdo.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$file_name = $_FILES["userfile"]["name"];
$file_name_dir = $_SERVER['DOCUMENT_ROOT'].'/mail_sender/uploads/';
var_dump($file_name_dir);

if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $file_name_dir.$file_name)) {
    echo "Файл ". basename( $_FILES["userfile"]["name"])." загружен.";
    $input_file_type = IOFactory::identify($file_name_dir.$file_name);
    $obj_reader = IOFactory::createReader($input_file_type);
    if ($input_file_type == 'OOCalc') {
            $obj_reader->setLoadSheetsOnly('Лист1');
        }          
        $spreadsheet = $obj_reader->load($file_name_dir.$file_name);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);
        try {
        foreach ($sheetData as $row) {
                $insert=$db->prepare("INSERT INTO `mail_sender`.`recipients` (`opf`, `emp_name`, `fio_lpr`, 
                                                        `position_lpr`, `phone`, `email`, `mr_or_ms`, `fio`) 
                                                                    VALUES (:opf, :emp_name, :fio_lpr, 
                                                        :position_lpr, :phone, :email, :mr_or_ms, :fio);");
                $insert->bindParam(':opf',          $row['A'], PDO::PARAM_STR);
                $insert->bindParam(':emp_name',     $row['B'], PDO::PARAM_STR);
                $insert->bindParam(':fio_lpr',      $row['C'], PDO::PARAM_STR);
                $insert->bindParam(':position_lpr', $row['D'], PDO::PARAM_STR);
                $insert->bindParam(':phone',        $row['E'], PDO::PARAM_STR);
                $insert->bindParam(':email',        $row['F'], PDO::PARAM_STR);
                $insert->bindParam(':mr_or_ms',     $row['H'], PDO::PARAM_STR);
                $insert->bindParam(':fio',          $row['G'], PDO::PARAM_STR);
                $insert->execute();
            } 
            echo 'Инофрмация в базе обновлена';
        }
        catch (Exception $e) {
            die("Произошла ошибка при выполнении запроса: ".$e);
        }
} else {
    echo "Произошла ошибка при выполнении загрузки файла.";
}
