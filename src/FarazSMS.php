<?php
// FarazSMS · IranPayamak (فراز اس ام اس · ایران پیامک)
// Official client — https://farazsms.com · https://iranpayamak.com

namespace FarazSMS;

use GuzzleHttp\Client;

/**
 * Lightweight official client for the FarazSMS / IranPayamak web services.
 *
 * @see https://farazsms.com
 */
class FarazSMS
{
    const BASE = "https://api.iranpayamak.com";

    /** @var Client */
    private $http;

    public function __construct($apiKey, $baseUrl = self::BASE)
    {
        if (!$apiKey) {
            throw new \InvalidArgumentException("FarazSMS: apiKey is required");
        }
        $this->http = new Client([
            "base_uri"    => rtrim($baseUrl, "/") . "/",
            "headers"     => ["Api-Key" => $apiKey, "Accept" => "application/json"],
            "http_errors" => false,
        ]);
    }

    /**
     * Low-level call — reaches every one of the 63 endpoints.
     * Throws FarazException on a `status: "error"` envelope.
     */
    public function request($method, $path, $body = null, array $query = [])
    {
        $opt = ["query" => $query];
        if ($body !== null) {
            $opt["json"] = $body;
        }
        $res  = $this->http->request($method, ltrim($path, "/"), $opt);
        $data = json_decode((string) $res->getBody(), true);
        if (is_array($data) && isset($data["status"]) && $data["status"] === "error") {
            throw new FarazException($res->getStatusCode(), isset($data["message"]) ? $data["message"] : $data);
        }
        return $data;
    }

    // account
    public function balance() { return $this->request("GET", "/ws/v1/account/balance"); }
    public function profile() { return $this->request("GET", "/ws/v1/account/profile"); }
    public function lines()   { return $this->request("GET", "/ws/v1/lines/accessible"); }

    // send
    public function sendPattern($code, $recipient, array $attributes, $line = "90008361")
    {
        return $this->request("POST", "/ws/v1/sms/pattern", [
            "code" => $code, "recipient" => $recipient, "attributes" => $attributes,
            "line_number" => $line, "number_format" => "english",
        ]);
    }
    public function sendSimple($text, array $recipients, $line = "90008361")
    {
        return $this->request("POST", "/ws/v1/sms/simple", [
            "text" => $text, "recipients" => $recipients,
            "line_number" => $line, "number_format" => "english",
        ]);
    }
    public function sendVariable($text, array $recipients, $line = "90008361")
    {
        return $this->request("POST", "/ws/v1/sms/keywords", [
            "text" => $text, "recipients" => $recipients,
            "line_number" => $line, "number_format" => "english",
        ]);
    }

    // patterns
    public function createPattern(array $payload)  { return $this->request("POST", "/ws/v1/patterns", $payload); }
    public function patterns(array $query = [])    { return $this->request("GET", "/ws/v1/patterns", null, $query); }

    // reports
    public function inbox($page = 1, $limit = 20)  { return $this->request("GET", "/ws/v1/inbox", null, ["page" => $page, "limit" => $limit]); }
    public function sendRequests(array $query = []) { return $this->request("GET", "/ws/v1/send_request", null, $query); }
    public function sendRequestItems($id, array $query = []) { return $this->request("GET", "/ws/v1/send_request/" . $id . "/items", null, $query); }

    // phonebook
    public function phonebooks()              { return $this->request("GET", "/ws/v1/phone_book"); }
    public function addContact(array $payload) { return $this->request("POST", "/ws/v1/phone_book_data", $payload); }

    // reference
    public function provinces(array $query = [])   { return $this->request("GET", "/provinces", null, $query); }
    public function numberBanks(array $query = []) { return $this->request("GET", "/ws/v1/number_bank", null, $query); }
}
