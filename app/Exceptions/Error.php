<?php

namespace App\Exceptions;

/**
 * This class lists down all errors Roshni Rides API can throw to the client
 * @package App\Exceptions
 */
class Error
{
    public static $INTERNAL_FAILURE;
    public static $VALIDATION_FAILED;
    public static $NOT_FOUND;
    public static $INVALID_METHOD_REQUEST;
  
    public static $BAD_METHOD;
    public static $NOT_LOGABLE;
    public static $TOKEN_EXPIRED;

    public static function init()
    {
        self::$INTERNAL_FAILURE = new Error('ER-500', 'Something went wrong and we have been notified about the problem');
        self::$BAD_METHOD = new Error('ER-500', 'Bad method call exception.');
      
        self::$NOT_LOGABLE = new Error('ER-403', 'User does not have login permission.');
        self::$NOT_FOUND = new Error('ER-404', 'Not found!');
        self::$INVALID_METHOD_REQUEST = new Error('ER-405', 'Request method is not allowed, HTTP Exception Error');
        self::$VALIDATION_FAILED = new Error('ER-422', 'Request validation has been failed, please verify the parameter not appropriate with the request');
        self::$TOKEN_EXPIRED = new Error('ER-401', 'The access token provided is expired.');
    }


    public $code = null;
    public $message = null;

    /**
     * Errors constructor.
     * @param $code
     * @param $message
     */
    public function __construct($code, $message)
    {
        $this->code = $code;
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getCode()
    {
        return $this->code;
    }

    public static function validationErrors($error)
    {
        return new Error('ER-422', $error);
    }
}
