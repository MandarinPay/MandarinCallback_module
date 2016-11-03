<?php
$StartDate = $_GET['from'];
$EndDate = $_GET['to'];

if (!isset($StartDate) || !isset($EndDate)) {
    echo "Не указаны даты!";
    exit(0);
}


require_once 'option.php';
spl_autoload_register(function ($class_name) {
    require_once 'Class/' . $class_name . '.php';
});
$DatabaseDealer = new DatabaseDealer($TableName, $TablePassword, $TableUser);
$PostHandler = new PostHandler($_POST, $SecretProject, $Delimiter);
$PeriodArrayTransaction = $DatabaseDealer->GetPeriodTransactions($StartDate, $EndDate);
$PeriodArrayToJson = empty($PeriodArrayTransaction) ? 'No Transaction' : json_encode($PeriodArrayTransaction);
print_r($PeriodArrayToJson);



