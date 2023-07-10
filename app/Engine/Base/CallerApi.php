<?php

// التوابع المتعلقة بطريقة استخدام الـ APIs
// /call api method type
namespace App\Engine\Base;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\Facades\Log;

class CallerApi
{
    public $client;

    public function __construct()
    {
        $this->client = new Client();
    }
    //request post
    public function post($url, $params = [], $header = [])
    {
        $client = $this->client;
        $res = $client->post($url, [
            'form_params' => $params,
            'headers' => $header,
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_SSL_VERIFYHOST => 0,
            ],
        ]);

        $data = array("status" => $res->getStatusCode(), "data" => json_decode($res->getBody()));
        return $data;
    }

    //request get
    public function get($url, $params = [], $header = [])
    {
        try {
            try {
                $options = [];
                $header['timeout'] = 60 * 60 * 5;
                $options['headers'] = $header;
                if ($params) {
                    $options['form_params'] = $params;
                }
                $options['curl'] = [
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_SSL_VERIFYHOST => 0,
                ];
                $client = $this->client;
                $res = $client->request('GET', $url, $options);
                $body = $res->getBody()->getContents();
                $data = array("status" => $res->getStatusCode(), "data" => json_decode($body), "header" => $res->getHeaders());
                return $data;
            } catch (BadResponseException $ex) {
                $response = $ex->getResponse()->getBody();
                $jsonBody = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response);
                $data = array("status" => $ex->getCode(), "data" => json_decode($jsonBody));
                return $data;
            }
        } catch (Exception $e) {
            Log::log('error', "Exception on get url $url Details $e");
            return array("status" => 500, "data" => $e);
        }
    }

    //request get stream
    public function getStream($url, $header = [])
    {
        try {
            try {
                $client = $this->client;
                $res = $client->get($url, [
                    'headers' => $header,
                    'stream' => true,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_SSL_VERIFYHOST => 0,
                    ],
                ]);
                $headers = $res->getHeaders();
                $data = array("headers" => $headers, "status" => $res->getStatusCode());
                return $data;
            } catch (BadResponseException $ex) {
                $response = $ex->getResponse()->getBody();
                $jsonBody = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response);
                $data = array("status" => $ex->getCode(), "data" => json_decode($jsonBody));
                return $data;
            }
        } catch (Exception $e) {
            Log::log('error', "Exception on getStream url $url Details $e");
            return array("status" => 500, "data" => $e);
        }
    }

    public function delete($url, $params = [], $header = [])
    {
        try {
            try {
                $client = $this->client;
                if (count($params) == 0) {
                    $res = $client->request('DELETE', $url, [
                        'headers' => $header,
                        'curl' => [
                            CURLOPT_SSL_VERIFYPEER => 0,
                            CURLOPT_SSL_VERIFYHOST => 0,
                        ],
                    ]);
                } else {
                    $res = $client->request('DELETE', $url, [
                        'headers' => $header,
                        'form_params' => $params,
                        'curl' => [
                            CURLOPT_SSL_VERIFYPEER => 0,
                            CURLOPT_SSL_VERIFYHOST => 0,
                        ],
                    ]);
                }
                $body = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $res->getBody()->getContents());
                $data = array("status" => $res->getStatusCode(), "data" => json_decode($body));
                return $data;
            } catch (BadResponseException $ex) {
                $response = $ex->getResponse()->getBody();
                $jsonBody = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response);
                $data = array("status" => $ex->getCode(), "data" => json_decode($jsonBody));
                return $data;
            }
        } catch (Exception $e) {
            Log::log('error', "Exception on delete url $url Details $e");
            return array("status" => 500, "data" => $e);
        }
    }

    public function head($url, $params = [], $header = [])
    {
        try {
            try {
                $client = $this->client;

                $res = $client->request('HEAD', $url, [
                    'headers' => $header,
                    'form_params' => $params,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_SSL_VERIFYHOST => 0,
                    ],
                ]);
                $body = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $res->getBody()->getContents());
                $data = array("status" => $res->getStatusCode(), "data" => json_decode($body));

                return $data;
            } catch (BadResponseException $ex) {
                $response = $ex->getResponse()->getBody();
                $jsonBody = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response);
                $data = array("status" => $ex->getCode(), "data" => json_decode($jsonBody));
                return $data;
            }
        } catch (Exception $e) {
            Log::log('error', "Exception on head url $url Details $e");
            return array("status" => 500, "data" => $e);
        }
    }

    public function post_($url, $params = [], $header = [], $isJson = true)
    {
        try {
            try {
                $client = $this->client;
                $res = $client->post($url, [
                    'body' => $params,
                    'headers' => $header,
                    'timeout' => 60 * 60 * 60 * 24,
                    'read_timeout' => 60 * 60 * 60 * 24,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_SSL_VERIFYHOST => 0,
                    ],
                ]);
                $body = $res->getBody()->getContents();
                if ($isJson) {
                    $body = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $body);
                    $data = array("status" => $res->getStatusCode(), "data" => json_decode($body), "header" => $res->getHeaders());
                } else {
                    $data = array("status" => $res->getStatusCode(), "data" => $body, "header" => $res->getHeaders());
                }

                return $data;
            } catch (BadResponseException $ex) {
                $response = $ex->getResponse()->getBody();
                $jsonBody = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response);
                $data = array("status" => $ex->getCode(), "data" => json_decode($jsonBody));
                return $data;
            }
        } catch (Exception $e) {
            Log::log('error', "Exception on _post url $url Details $e");
            return array("status" => 500, "data" => $e);
        }
    }

    public function postAlive($url, $params = [], $header = [], $isJson = true)
    {
        try {
            try {
                $client = new Client();
                $res = $client->request("POST", $url, [
                    'body' => $params,
                    'headers' => $header,
                    "version" => 2.0,
                    'curl' => [
                        CURLOPT_TCP_KEEPALIVE => 1,
                        CURLOPT_TCP_KEEPIDLE => 120,
                        CURLOPT_TCP_KEEPINTVL => 60,
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_SSL_VERIFYHOST => 0,
                    ],
                ]);
                $body = $res->getBody()->getContents();
                if ($isJson) {
                    $body = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $body);
                    $data = array("status" => $res->getStatusCode(), "data" => json_decode($body), "header" => $res->getHeaders());
                } else {
                    $data = array("status" => $res->getStatusCode(), "data" => $body, "header" => $res->getHeaders());
                }

                return $data;
            } catch (BadResponseException $ex) {
                $response = $ex->getResponse()->getBody();
                $jsonBody = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response);
                $data = array("status" => $ex->getCode(), "data" => json_decode($jsonBody));
                return $data;
            }
        } catch (Exception $e) {
            Log::log('error', "Exception on _post url $url Details $e");
            return array("status" => 500, "data" => $e);
        }
    }

    public function patch($url, $params = [], $header = [])
    {
        try {
            try {
                $client = $this->client;
                $res = $client->patch($url, [
                    'body' => $params,
                    'headers' => $header,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_SSL_VERIFYHOST => 0,
                    ],
                ]);
                $body = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $res->getBody()->getContents());
                $data = array("status" => $res->getStatusCode(), "data" => json_decode($body));
                return $data;
            } catch (BadResponseException $ex) {
                $response = $ex->getResponse()->getBody();
                $jsonBody = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response);
                $data = array("status" => $ex->getCode(), "data" => json_decode($jsonBody));
                return $data;
            }
        } catch (Exception $e) {
            Log::log('error', "Exception on _patch url $url Details $e");
        }
    }

    public function postStream($url, $params = [], $header = [], $callback)
    {
        $error = "";
        try {
            try {
                $headers = [];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_HEADER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_TCP_KEEPALIVE, 1);
                curl_setopt($ch, CURLOPT_TCP_KEEPIDLE, 120);
                curl_setopt($ch, CURLOPT_TCP_KEEPINTVL, 60);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
                curl_setopt($ch, CURLOPT_WRITEFUNCTION, $callback);
                curl_setopt(
                    $ch,
                    CURLOPT_HEADERFUNCTION,
                    function ($curl, $header) use (&$headers) {
                        $len = strlen($header);
                        $header = explode(':', $header, 2);
                        if (count($header) < 2) // ignore invalid headers
                        {
                            return $len;
                        }

                        $headers[strtolower(trim($header[0]))][] = trim($header[1]);
                        return $len;
                    }
                );
                $result = curl_exec($ch);
                if ($result === false) {
                    $error = curl_error($ch);
                }

                $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                return ['headers' => $headers, 'status' => $statusCode, "error" => $error];
            } catch (BadResponseException $ex) {
                Log::log('error', "Exception on postStream url $url Details $ex");
                $response = $ex->getResponse()->getBody();
                $jsonBody = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response);
                $data = array("status" => $ex->getCode(), "data" => json_decode($jsonBody));
                return $data;
            }
        } catch (Exception $e) {
            Log::log('error', "Exception on postStream url $url Error $error exception $e");
            return array("status" => 500, "data" => $error);
        }
    }

    public function postStreamMulti($handlersData, $header = [])
    {
        $handlers = [];
        foreach ($handlersData as $handlerData) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $handlerData["url"]);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_TCP_KEEPALIVE, 1);
            curl_setopt($ch, CURLOPT_TCP_KEEPIDLE, 120);
            curl_setopt($ch, CURLOPT_TCP_KEEPINTVL, 60);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $handlerData["body"]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
            curl_setopt($ch, CURLOPT_WRITEFUNCTION, $handlerData["callback"]);
            array_push($handlers, $ch);
        }

        $mh = curl_multi_init();
        foreach ($handlers as $handler) {
            curl_multi_add_handle($mh, $handler);
        }
        do {
            $status = curl_multi_exec($mh, $active);
            if ($active) {
                curl_multi_select($mh);
            }
        } while ($active && $status == CURLM_OK);
        //close the handles
        foreach ($handlers as $handler) {
            curl_multi_remove_handle($mh, $handler);
        }
        $result = curl_multi_info_read($mh);
        curl_multi_close($mh);
        return $result;
    }

    public function postStreamDirect($url, $params = [], $header = [])
    {
        $error = "";
        try {
            try {
                $client = new Client();
                //-----------------------//
                $response = $client->request('POST', $url, [
                    'headers' => $header,
                    'body' => $params,
                    'read_timeout' => 60 * 60 * 24,
                    'connect_timeout' => 60 * 60 * 24,
                    'timeout' => 60 * 60 * 24,
                    'curl' => [
                        CURLOPT_TCP_KEEPALIVE => 1,
                        CURLOPT_TCP_KEEPIDLE => 120,
                        CURLOPT_TCP_KEEPINTVL => 60,
                        CURLOPT_CONNECTTIMEOUT => 60 * 60 * 24,
                        CURLOPT_TIMEOUT => 60 * 60 * 24,
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_SSL_VERIFYHOST => 0,
                    ],
                ]);
                return ['headers' => $response->getHeaders(), 'body' => $response->getBody(), 'status' => $response->getStatusCode(), "client" => $client];
            } catch (BadResponseException $ex) {
                Log::log('error', "Exception on postStream url $url Details $ex");
                $response = $ex->getResponse()->getBody();
                $jsonBody = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response);
                $data = array("status" => $ex->getCode(), "data" => json_decode($jsonBody));
                return $data;
            }
        } catch (Exception $e) {
            Log::log('error', "Exception on postStream url $url Error $error exception $e");
            return array("status" => 500, "data" => $error);
        }
    }

    public function put($url, $params = [], $header = [])
    {
        try {
            try {
                $client = $this->client;
                $res = $client->put($url, [
                    'body' => $params,
                    'headers' => $header,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_SSL_VERIFYHOST => 0,
                    ],
                ]);
                // $body = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $res->getBody()->getContents());
                $body = $res->getBody()->getContents();
                $data = array("status" => $res->getStatusCode(), "headers" => $res->getHeaders(), "data" => json_decode($body));

                return $data;
            } catch (BadResponseException $ex) {
                $response = $ex->getResponse()->getBody();
                $jsonBody = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response);
                $data = array("status" => $ex->getCode(), "data" => json_decode($jsonBody));
                return $data;
            }
        } catch (Exception $e) {
            Log::log('error', "Exception on _put url $url Details $e");
            return array("status" => 500, "data" => $e);
        }
    }
}
