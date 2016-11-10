<?php

require_once 'option.php';

spl_autoload_register(function ($class_name) {
    require_once 'Class/' . $class_name . '.php';
});


//$file=fopen('file.txt','w+');
//fwrite($file,json_encode($_POST));
//fclose($file);

$DatabaseDealer = new DatabaseDealer($TableName, $TablePassword, $TableUser);
$PostHandler = new PostHandler($_POST, $SecretProject,$Delimiter);
$calc_sigh = $PostHandler->check_sign();
if ($calc_sigh == true) {
    $operation = $PostHandler->SearchOperation();
    if ($operation == 'transaction') {
        $DatabaseDealer->CreateTransaction($PostHandler);
        if($TypeFormat && $SendOneTransactionMail)
            $PostHandler->SendMailJson($UserMailToSendOperationReport);
        else{
            $PostHandler->OneOperationCSV($ConvertToCp1251);
            if($SendOneTransactionMail)
                $PostHandler->SendMailCSV($UserMailToSendOperationReport,0);

        }

    }
    if ($operation == 'card_binding') {
        $DatabaseDealer->CreateCardBinding($PostHandler);
    } else {
        echo $operation;
    }
} else {
    echo "Несоответсвует Хэш";
}

