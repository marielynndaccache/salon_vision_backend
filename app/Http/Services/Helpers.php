<?php
namespace App\Http\Services;

use NumberFormatter;

class Helpers
{
    public function __construct()
    {
    }

    public function addQuota($arr, $prefix = "", $suffex = "")
    {
        $text = "";
        foreach ($arr as $value) {
            if ($value != "") {
                $text .= "'$prefix$value$suffex',";
            }
        }
        if ($text) {
            return substr($text, 0, -1);
        }
        return $text;
    }

    public function addQuotaParams($paramKey, $arr, $prefix = "", $suffex = "")
    {
        $text = "";
        $paramsArr = [];
        foreach ($arr as $key => $value) {
            if ($value != "") {
                $text .= ":$paramKey" . "_$key,";
                $paramsArr["$paramKey" . "_$key"] = "$prefix$value$suffex";
            }
        }
        if ($text) {
            $text = substr($text, 0, -1);
        }
        return ["text" => $text, "paramsArr" => $paramsArr];
    }

    public function addQuotaParamsToFilter($colName, &$filter, &$filterParams, $paramKey, $arr, $prefix = "", $suffex = "")
    {
        $filterQuotaParams = $this->addQuotaParams($paramKey, $arr, $prefix, $suffex);
        $filterText = $filterQuotaParams["text"];
        $paramsArr = $filterQuotaParams["paramsArr"];
        if ($filterText) {
            $filter .= " AND $colName IN ($filterText)";
        }
        foreach ($paramsArr as $key => $value) {
            $filterParams[$key] = $value;
        }
    }

    public function addQuotaParamsToManyFilter($colName, &$filter, &$filterParams, $paramKey, $arr, $prefix = "", $suffex = "")
    {
        $filterQuotaParams = $this->addQuotaParams($paramKey, $arr, $prefix, $suffex);
        $paramsArr = $filterQuotaParams["paramsArr"];
        $temp_filter = "";
        foreach ($paramsArr as $key => $value) {
            $temp_filter .= " OR FIND_IN_SET(:$key,$colName)";
            $filterParams[$key] = $value;
        }
        if ($temp_filter and $temp_filter != "") {
            $temp_filter = substr($temp_filter, 3);
            $filter .= " AND ($temp_filter)";
        }
    }

    public function addQuotaParamsToMultiColumnFilter(array $colNames, &$filter, &$filterParams, $paramKey, $arr)
    {
        $temp_filter = "";
        foreach ($colNames as $index => $colName) {
            $filterQuotaParams = $this->addQuotaParams($paramKey . "_" . $index, $arr);
            if (sizeof($filterQuotaParams["paramsArr"]) == 0) {
                return;
            }
            $filterText = $filterQuotaParams["text"];
            $paramsArr = $filterQuotaParams["paramsArr"];
            $temp_filter .= " OR $colName IN ($filterText)";
            foreach ($paramsArr as $key => $value) {
                $filterParams[$key] = $value;
            }
        }
        if ($temp_filter) {
            $temp_filter = substr($temp_filter, 3);
            $filter .= " AND ($temp_filter)";
        }
    }

    public function readCSV($csvFile, $delimiter = ',')
    {
        $file_handle = fopen($csvFile, 'r');
        while ($csvRow = fgetcsv($file_handle, null, $delimiter)) {
            $line_of_text[] = $csvRow;
        }
        fclose($file_handle);
        return $line_of_text;
    }

    function spellNumber($number, $locale = 'en')
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::SPELLOUT);

        // Separate integer and decimal parts
        $parts = explode('.', (string) $number);
        $integerPart = (int) $parts[0];
        $decimalPart = isset($parts[1]) ? $parts[1] : null;

        // Convert integer part
        $integerWords = $formatter->format($integerPart);

        if ($decimalPart !== null) {
            // Convert decimal part as a whole number (not digit-by-digit)
            $decimalNumber = (int) $decimalPart;
            $decimalWords = $formatter->format($decimalNumber);

            return "$integerWords point $decimalWords";
        }

        return $integerWords;
    }




    function generateRandomString($length = 10, $type = 'number', $frfix = "")
    {
        if ($type == 'number') {
            $characters = '0123456789';
        } else {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        if (!empty($frefix)) {
            $randomString = $frefix . $randomString;
        }
        return $randomString;
    }

    function matchCurrency($currency = "")
    {
        $fraction_unit = match (strtoupper($currency)) {
            'USD' => 'Cents',
            'SAR' => 'Halalas',
            default => null
        };
        return $fraction_unit;
    }
    function returnMultiCurrency($currency = "", $code = '')
    {
        $fraction_unit = match (strtoupper($code)) {
            'USD' => 'US Dollars',
            'SAR' => 'Saudi Riyals',
            default => $currency
        };
        return $fraction_unit;
    }
}
