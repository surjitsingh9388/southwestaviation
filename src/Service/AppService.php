<?php
namespace App\Service;

class AppService
{
    //Change date format 'm-d-Y' to 'Y-m-d'
    public function dateFormatBeforeSave($dateString) 
    {
        $dateString = str_replace('-', '/', $dateString);
        return date('Y-m-d H:i:s', strtotime($dateString));
    }

    public function timeFormatBeforeSave($dateString) 
    {
        $dateString = str_replace('-', '/', $dateString);
        return date('H:i', strtotime($dateString));
    }
}


?>