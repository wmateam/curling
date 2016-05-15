<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 5/14/16
 * Time: 2:47 PM
 */

namespace wmateam\curling;


use Exception;

class CurlingException extends Exception
{
    const INVALID_TYPE = 0;
    const INVALID_URL = 0;

    public function __construct($objectName, $code = self::INVALID_TYPE, Exception $previous = null)
    {
        switch ($code) {
            case self::INVALID_TYPE:
                parent::__construct('Invalid type for ' . $objectName, $code);
                break;
            case self::INVALID_URL:
                parent::__construct('Invalid url for ' . $objectName, $code);
                break;
            default:
                parent::__construct($objectName, $code, $previous);
                break;
        }
    }

    public function toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function toArray()
    {
        return array(
            'Message' => $this->getMessage(),
            'File' => $this->getFile(),
            'Line' => $this->getLine(),
            'Trace' => $this->getTraceAsString()
        );
    }


    function __destruct()
    {
        // TODO: Implement __destruct() method.
    }


}