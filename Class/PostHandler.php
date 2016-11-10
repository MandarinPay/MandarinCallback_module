<?php


Class PostHandler
{
    public function __construct(array $CallbackArray, $SecrerProject = '123321', $Delimiter)
    {
        $this->CallbackArray = $CallbackArray;
        $this->SecrerProject = $SecrerProject;
        $this->delimentr = $Delimiter;
    }

    public function SearchOperation()
    {
        if (!empty($this->CallbackArray['transaction']))
            return "transaction";
        if (!empty($this->CallbackArray['card_binding']))
            return "card_binding";
        else
            return "Error Operation";
    }

    public function check_sign()
    {
        $sign = $this->CallbackArray['sign'];
        unset($this->CallbackArray['sign']);
        $to_hash = '';
        if (!is_null($this->CallbackArray) && is_array($this->CallbackArray)) {
            ksort($this->CallbackArray);
            $to_hash = implode('-', $this->CallbackArray);
        }

        $to_hash = $to_hash . '-' . $this->SecrerProject;
        $calculated_sign = hash('sha256', $to_hash);
        return $calculated_sign == $sign;
    }


    public function SendMailJson($UserMail)
    {
        $to = $UserMail;
        $subject = 'operation';
        $headers = 'From: mandarin' . "\n";
        $body = $this->ArrayToJson($this->CallbackArray);
        mail($to, $subject, $body, $headers);
    }

    public function SendMailCSV($UserMail, $Day)
    {
        $subject = "тема письма";
        $message = $Day ? "Transactions on the day" : " One Transaction";
        $filename = "csvarray.csv";
        $filepath = $filename;
        $boundary = "--" . md5(uniqid(time()));
        $mailheaders = "MIME-Version: 1.0;\r\n";
        $mailheaders .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
        $mailheaders .= "From: mandarin \r\n";
        $mailheaders .= "Reply-To: $UserMail \r\n";

        $multipart = "--$boundary\r\n";
        $multipart .= "Content-Type: text/html; charset=windows-1251\r\n";
        $multipart .= "Content-Transfer-Encoding: base64\r\n";
        $multipart .= "\r\n";
        $multipart .= chunk_split(base64_encode(iconv("utf8", "windows-1251", $message)));

        $fp = fopen($filepath, "r");
        if (!$fp) {
            print "Не удается открыть файл22";
            exit();
        }
        $file = fread($fp, filesize($filepath));
        fclose($fp);
        $message_part = "\r\n--$boundary\r\n";
        $message_part .= "Content-Type: application/octet-stream; name=\"$filename\"\r\n";
        $message_part .= "Content-Transfer-Encoding: base64\r\n";
        $message_part .= "Content-Disposition: attachment; filename=\"$filename\"\r\n";
        $message_part .= "\r\n";
        $message_part .= chunk_split(base64_encode($file));
        $message_part .= "\r\n--$boundary--\r\n";
        $multipart .= $message_part;
        mail($UserMail, $subject, $multipart, $mailheaders);
// отправляем письмо
        unlink($filepath);

    }

    public function SendDayMailJson($UserMail, $CurrentDayArray)
    {
        $to = $UserMail;
        $subject = 'operation';
        $headers = 'From: mandarin' . "\n";
        $body = $this->ArrayToJson($CurrentDayArray);
        mail($to, $subject, $body, $headers);
    }

    public function DayArrayToCsv($ArrayDayTrabsactions, $ConvertToCp1251)
    {
        $File = fopen('csvarray.csv', 'w+');
        $i = 0;
        foreach ($ArrayDayTrabsactions as $arrayTransaction) {
            if ($i == 0) {
                foreach ($arrayTransaction as $key => $value)
                    $RowTitle[] = $ConvertToCp1251 ? iconv('UTF-8', 'cp1251', $key) : $key;
                fputcsv($File, $RowTitle, $this->delimentr);
                $i++;
            }
            $arrayTransaction = $ConvertToCp1251 ? iconv('UTF-8', 'cp1251', $arrayTransaction) : $arrayTransaction;
            fputcsv($File, $arrayTransaction, $this->delimentr);
        }

    }

    private function ArrayToJson($Array)
    {
        $ArrayTransactions = json_encode($Array);
        return $ArrayTransactions;
    }

    public function OneOperationCSV($ConvertToCp1251)
    {
        foreach ($this->CallbackArray as $NameRow => $ValueRow) {
            $NameTableCol[] = $ConvertToCp1251 ? iconv('UTF-8', 'cp1251', $NameRow) : $NameRow;;
            $ValueRowtable[] = $ConvertToCp1251 ? iconv('UTF-8', 'cp1251', $ValueRow) : $ValueRow;
        }
        $File = fopen('csvarray.csv', 'w+');
        fputcsv($File, $NameTableCol, $this->delimentr);
        fputcsv($File, $ValueRowtable, $this->delimentr);
        fclose($File);
    }

    public function CostumExtraValue()
    {
        $ExtraArray = array();
        foreach ($this->CallbackArray as $NameRow => $ValueRow) {
            if (preg_match("/^extra_/", $NameRow)) {
                $ExtraArray[] = array(0 => $NameRow, 1 => $ValueRow);
            }

        }
        //11 because  Database have 10 CoStumVaLueRow
        for ($i = count($ExtraArray); $i < 11; $i++) {
            $ExtraArray[] = array(0 => '--NO VALUE--', 1 => '--NO VALUE--');
        }
        return $ExtraArray;


    }
}
