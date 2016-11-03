<?php

require_once 'option.php';

spl_autoload_register(function ($class_name) {
    require_once 'Class/' . $class_name . '.php';
});


$DatabaseDealer = new DatabaseDealer($TableName, $TablePassword, $TableUser);
$PostHandler = new PostHandler($_POST, $SecretProject,$Delimiter);


$ArayDayTrabsactions=$DatabaseDealer->GetCurrentDayTransactions();
if($TypeFormat)
    $PostHandler->SendDayMailJson($UserMailToSendOperationReport,$ArayDayTrabsactions);
else{
    $PostHandler->DayArrayToCsv($ArayDayTrabsactions);
    $PostHandler->SendMailCSV($UserMailToSendOperationReport,1);


}




