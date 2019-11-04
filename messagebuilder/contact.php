<?php
require_once './config/config_pdo.php';
    function contacts_show()
    {
        try{
            global $db;
            $select_contact=$db->prepare("SELECT opf, emp_name, position_lpr, fio_lpr, mr_or_ms, email, fio FROM recipients");
            $select_contact->execute();
            $contacts=$select_contact->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (Exception $e) {
            echo 'Произошла ошибка запроса контактных данных: ',  $e->getMessage(), "\n";
        }
    return $contacts;
    }
