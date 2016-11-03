<?php


Class DatabaseDealer
{
    public $TableName;
    public $PasswordTable;
    public $UserTable;
    public $hostTable;

    public function __construct($TableName, $PasswordTable, $UserTable, $hostTable = 'localhost')
    {
        $this->TableName = $TableName;
        $this->PasswordTable = $PasswordTable;
        $this->UserTable = $UserTable;
        $this->hostTable = $hostTable;
        $this->options = array(PDO:: MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
        $this->db = new PDO("mysql:dbname={$this->TableName};host={$this->hostTable}", $this->UserTable, $this->PasswordTable);
        $this->db->exec("set names utf8");
        $this->CreateAndOpenTableTransaction();
        $this->CreateAndOpenTableCardBinding();

    }


    private function CreateAndOpenTableTransaction()
    {

        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->exec("CREATE TABLE IF NOT EXISTS TransactionUser(
                               id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                              /* id автоматически станет автоинкрементным */ 
                               TransactionId TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               Status TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               ActionUser TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               MerchantId INT,
                               Price DOUBLE ,
                               OrderId TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CustomerEmail TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CustomerPhone INT,
                               CostumName0 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue0 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumName1 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue1 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumName2 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue2 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumName3 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue3 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumName4 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue4 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumName5 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue5 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumName6 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue6 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumName7 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue7 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumName8 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue8 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumName9 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue9 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               operation_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                               )");
    }

    private function CreateAndOpenTableCardBinding()
    {
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->exec("CREATE TABLE IF NOT EXISTS CardBinding(
                               id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                              /* id автоматически станет автоинкрементным */ 
                               CardBinding TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CardHolder TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CardNumber TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CardExpirationYear INT,
                               CardExpirationMonth INT ,
                               Status TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumName0 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue0 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumName1 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue1 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumName2 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue2 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumName3 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue3 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumName4 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue4 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumName5 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue5 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumName6 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue6 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumName7 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue7 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumName8 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue8 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumName9 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               CostumValue9 TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
                               operation_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                               )");

    }

    public function CreateTransaction(PostHandler $PostHendler)
    {
        $ArrayPost = $PostHendler->CallbackArray;
        $ExtraValue = $PostHendler->CostumExtraValue();
        $insert = "INSERT INTO TransactionUser(TransactionId, Status, ActionUser,
                                                MerchantId,Price,OrderId,CustomerEmail,
                                                CustomerPhone,
                                                CostumName0,CostumValue0,CostumName1,CostumValue1,CostumName2,CostumValue2,CostumName3,CostumValue3,CostumName4,CostumValue4,
                                                CostumName5,CostumValue5,CostumName6,CostumValue6,CostumName7,CostumValue7,CostumName8,CostumValue8,CostumName9,CostumValue9
                                              )
                         VALUES
                                              (:TransactionId, :Status, :ActionUser,
                                              :MerchantId,:Price,:OrderId,:CustomerEmail,
                                              :CustomerPhone,
                                                :CostumName0,:CostumValue0,:CostumName1,:CostumValue1,:CostumName2,:CostumValue2,:CostumName3,:CostumValue3,:CostumName4,:CostumValue4,
                                                :CostumName5,:CostumValue5,:CostumName6,:CostumValue6,:CostumName7,:CostumValue7,:CostumName8,:CostumValue8,:CostumName9,:CostumValue9)";

        $stmt = $this->db->prepare($insert);
        $stmt->bindParam(':TransactionId', $ArrayPost['transaction']);
        $stmt->bindParam(':Status', $ArrayPost['status']);
        $stmt->bindParam(':ActionUser', $ArrayPost['action']);
        $stmt->bindParam(':MerchantId', $ArrayPost['merchantId']);
        $stmt->bindParam(':Price', $ArrayPost['price']);
        $stmt->bindParam(':OrderId', $ArrayPost['orderId']);
        $stmt->bindParam(':CustomerEmail', $ArrayPost['customer_email']);
        $stmt->bindParam(':CustomerPhone', $ArrayPost['customer_phone']);
        for ($i = 0; $i < 10; $i++) {
            $stmt->bindParam(":CostumName{$i}", $ExtraValue[$i][0]);
            $stmt->bindParam(":CostumValue{$i}", $ExtraValue[$i][1]);
        }
        $stmt->execute();
    }


    public
    function CreateCardBinding(PostHandler $PostHendler)
    {
        $ArrayPost = $PostHendler->CallbackArray;
        $ExtraValue = $PostHendler->CostumExtraValue();
        $insert = "INSERT INTO CardBinding(CardBinding, CardHolder, CardNumber,
                                                        CardExpirationYear,CardExpirationMonth,Status,CostumValue0,CostumValue1,CostumValue2,CostumValue3,CostumValue4,CostumValue5,CostumValue6,CostumValue7,CostumValue8,CostumValue9,
                                                        
                                                CostumName0,CostumName1,CostumName2,CostumName3,CostumName4,CostumName5,CostumName6,CostumName7,CostumName8,CostumName9) 
 ) 
                                 VALUES
                                                      (:CardBinding, :CardHolder, :CardNumber,
                                                      :CardExpirationYear,:CardExpirationMonth,:Status,:CostumValue0,:CostumValue1,:CostumValue2,:CostumValue3,:CostumValue4,:CostumValue5,:CostumValue6,:CostumValue7,:CostumValue8,:CostumValue9,
                                              :CostumName0,:CostumName1,:CostumName2,:CostumName3,:CostumName4,:CostumName5,:CostumName6,:CostumName7,:CostumName8,:CostumName9)";
        $stmt = $this->db->prepare($insert);
        $stmt->bindParam(':CardBinding', $ArrayPost['card_binding']);
        $stmt->bindParam(':CardHolder', $ArrayPost['card_holder']);
        $stmt->bindParam(':CardNumber', $ArrayPost['card_number']);
        $stmt->bindParam(':CardExpirationYear', $ArrayPost['card_expiration_year']);
        $stmt->bindParam(':CardExpirationMonth', $ArrayPost['card_expiration_month']);
        $stmt->bindParam(':Status', $ArrayPost['status']);
        for ($i = 0; $i < 10; $i++) {
            $stmt->bindParam(":CostumName{$i}", $ExtraValue[$i][0]);
            $stmt->bindParam(":CostumValue{$i}", $ExtraValue[$i][1]);
        }
        $stmt->execute();
        $stmt->execute();

    }

    public
    function GetCurrentDayTransactions()
    {
        $ArrayCurrentDate = $this->db->prepare("SELECT * FROM TransactionUser
                                                WHERE operation_time >= CURDATE()");
        $ArrayCurrentDate->execute();
        $ArrayTransactions = $ArrayCurrentDate->fetchAll(PDO::FETCH_ASSOC);
        ksort($ArrayTransactions);
        return $ArrayTransactions;

    }

    public
    function GetPeriodTransactions($StartPoint, $EndPoint)
    {
        $PeriodArray = $this->db->prepare("SELECT * FROM TransactionUser 
                                            WHERE :StartPoint <= operation_time <= :EndPoint");
        $PeriodArray->bindParam(':StartPoint', $StartPoint);
        $PeriodArray->bindParam(':EndPoint', $EndPoint);
        $PeriodArray->execute();
        $PeriodArrayTransaction = $PeriodArray->fetchAll(PDO::FETCH_ASSOC);
        ksort($PeriodArrayTransaction);
        return $PeriodArrayTransaction;

    }

}


