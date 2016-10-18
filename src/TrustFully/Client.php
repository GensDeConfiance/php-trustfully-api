<?php

namespace TrustFully;

class Client implements ClientInterface
{
    /**
     * @var array
     */
    private $classMapping = [
        'community' => 'Community',
        'contact' => 'Contact',
        'login' => 'Login',
        'membership' => 'Membership',
        'sponsorship' => 'Sponsorship',
        'user' => 'User',
    ];

    /**
     * @var array
     */
    private static $defaultPorts = [
        'http' => 80,
        'https' => 443,
    ];

    /**
     * Error strings if json is invalid.
     *
     * @var array
     */
    private static $jsonErrors = [
        JSON_ERROR_NONE => 'No error has occurred',
        JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
        JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
        JSON_ERROR_SYNTAX => 'Syntax error',
    ];

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $apiToken = null;

    /**
     * @var bool
     */
    private $checkSslCertificate = false;

    /**
     * @var bool
     */
    private $checkSslHost = false;

    /**
     * @var array
     */
    private $apis = [];

    /**
     * @var int|null
     */
    private $responseCode = null;

    /**
     * @param string $url
     * @param string $xApiKey
     */
    public function __construct($url, $xApiKey)
    {
        $this->url = $url;
        $this->xApiKey = $xApiKey;
        $this->getPort();
    }

    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return Api\AbstractApi
     */
    public function __get($name)
    {
        if (!isset($this->classes[$name])) {
            throw new \InvalidArgumentException('Available api : '.implode(', ', array_keys($this->classes)));
        }
        if (isset($this->apis[$name])) {
            return $this->apis[$name];
        }
        $c = 'TrustFully\Api\\'.$this->classes[$name];
        $this->apis[$name] = new $c($this);

        return $this->apis[$name];
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Client
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get($path, array $params = [], $decode = true)
    {
        if (count($params) > 0) {
            $path = sprintf('%s?%s', $path, http_build_query($params));
        }
        if (false === $json = $this->runRequest($path, 'GET')) {
            return false;
        }

        if (!$decode) {
            return $json;
        }

        return $this->decode($json);
    }

    /**
     * {@inheritdoc}
     */
    public function post($path, $data = null, $encode = true)
    {
        if ($encode) {
            $data = $this->encodeData($data);
        }

        return $this->runRequest($path, 'POST', $data);
    }

    /**
     * {@inheritdoc}
     */
    public function put($path, $data = null, $encode = true)
    {
        if ($encode) {
            $data = $this->encodeData($data);
        }

        return $this->runRequest($path, 'PUT', $data);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($path, $data = null)
    {
        if (null === $data) {
            return $this->runRequest($path, 'DELETE');
        }
        $data = $this->encodeData($data);

        return $this->runRequest($path, 'DELETE', $data);
    }

    /**
     * {@inheritdoc}
     */
    public function decode($json)
    {
        if ('' === $json) {
            return '';
        }
        $decoded = json_decode($json, true);
        if (null !== $decoded) {
            return $decoded;
        }
        if (JSON_ERROR_NONE === json_last_error()) {
            return $json;
        }

        return self::$jsonErrors[json_last_error()];
    }

    /**
     * Turns on/off ssl certificate check.
     *
     * @param bool $check
     *
     * @return Client
     */
    public function setCheckSslCertificate($check = false)
    {
        $this->checkSslCertificate = $check;

        return $this;
    }

    /**
     * Turns on/off ssl host certificate check.
     *
     * @param bool $check
     *
     * @return Client
     */
    public function setCheckSslHost($check = false)
    {
        $this->checkSslHost = $check;

        return $this;
    }

    /**
     * Set the port of the connection.
     *
     * @param int $port
     *
     * @return Client
     */
    public function setPort($port = null)
    {
        if (null !== $port) {
            $this->port = (int) $port;
        }

        return $this;
    }

    /**
     * Returns the port of the current connection,
     * if not set, it will try to guess the port
     * from the url of the client.
     *
     * @return int the port number
     */
    public function getPort()
    {
        if (null !== $this->port) {
            return $this->port;
        }

        $tmp = parse_url($this->getUrl());
        if (isset($tmp['port'])) {
            $this->setPort($tmp['port']);
        } elseif (isset($tmp['scheme'])) {
            $this->setPort(self::$defaultPorts[$tmp['scheme']]);
        }

        return $this->port;
    }

    /**
     * Returns response code.
     *
     * @return int
     */
    public function getResponseCode()
    {
        return (int) $this->responseCode;
    }

    /**
     * @param string $apiToken
     *
     * @return Client
     */
    public function setApiToken($apiToken)
    {
        $this->apiToken = sprintf('%s', $apiToken);

        return $this;
    }

    /**
     * @return string
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * @param string $path
     * @param string $method
     * @param string $data
     *
     * @throws \Exception If anything goes wrong on curl request
     *
     * @return bool|string
     */
    protected function runRequest($path, $method = 'GET', $data = '')
    {
        $this->responseCode = null;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url.$path);
        curl_setopt($curl, CURLOPT_VERBOSE, false);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_PORT, $this->getPort());
        if (80 !== $this->getPort()) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->checkSslCertificate);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $this->checkSslHost);
        }

        $requestHeader = [
            'content-type: multipart/form-data',
            'cache-control: no-cache',
            // 'Expect:',
            sprintf('X-Api-Key: %s', $this->xApiKey),
            // 'Content-Type: application/json',
        ];
        if (null !== $this->apiToken) {
            $requestHeader[] = sprintf('Authorization: Bearer %s', $this->apiToken);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $requestHeader);
        switch ($method) {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, true);
                if (isset($data)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                if (isset($data)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case 'DELETE':
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (isset($data)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            default: // GET
                break;
        }
        $rawResponse = trim(curl_exec($curl));

        if (curl_errno($curl)) {
            $e = new \Exception(curl_error($curl), curl_errno($curl));
            curl_close($curl);
            throw $e;
        }

        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $response = $this->parseResponse($rawResponse, $headerSize);
        if ($this->isErrorCode($responseCode)) {
            $e = new \Exception($response['body']);
            curl_close($curl);
            throw $e;
        }
        curl_close($curl);

        if ($response['body']) {
            return $response['body'];
        }

        return true;
    }

    /**
     * @param int $code
     *
     * @return bool
     */
    private function isErrorCode($code)
    {
        return 400 <= (int) $code && (int) $code <= 599;
    }

    /**
     * @param mixed $data
     *
     * @return array|json
     */
    private function encodeData($data = null)
    {
        if (is_array($data)) {
            return json_encode($data);
        }

        return [];
    }

    /**
     * @param string $rawResponse
     * @param string $headerSize
     *
     * @return array
     */
    private function parseResponse($rawResponse, $headerSize)
    {
        $header = substr($rawResponse, 0, $headerSize);
        $body = substr($rawResponse, $headerSize);

        $headers = [];
        $rawHeader = substr($rawResponse, 0, strpos($rawResponse, "\r\n\r\n"));
        foreach (explode("\r\n", $rawHeader) as $i => $line) {
            if (0 === $i) {
                $headers['http_code'] = $line;
            } else {
                list($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }
        }

        return [
            'headers' => $headers,
            'body' => $body,
        ];
    }
}
