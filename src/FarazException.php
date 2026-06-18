<?php
// FarazSMS · IranPayamak (فراز اس ام اس · ایران پیامک)
// https://farazsms.com · https://iranpayamak.com

namespace FarazSMS;

/** Thrown when the API returns an envelope with `status: "error"`. */
class FarazException extends \RuntimeException
{
    /** @var int HTTP status code */
    public $statusCode;

    /** @var mixed Raw error message/body from the API */
    public $body;

    public function __construct($statusCode, $body)
    {
        $this->statusCode = $statusCode;
        $this->body = $body;
        $msg = is_string($body) ? $body : json_encode($body, JSON_UNESCAPED_UNICODE);
        parent::__construct("[$statusCode] $msg");
    }
}
