<?php

namespace App\Http;




use App\Engine\Base\CallerApi;
use Illuminate\Support\Facades\Log;

class LogService
{
    private $_loggerLevel;
    private $_loggerChanel;

    function __construct()
    {
        $this->_loggerLevel = 'info';
        $this->_loggerChanel = 'file';
    }

    public function log($logMessage){
        Log::log($this->_loggerLevel,$logMessage);
    }
}
