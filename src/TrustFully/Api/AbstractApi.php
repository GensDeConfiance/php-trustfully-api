<?php

namespace TrustFully\Api;

use TrustFully\Client;
use TrustFully\ClientInterface;

/**
 * Abstract class for Api classes.
 */
abstract class AbstractApi implements ApiInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $endPoint;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function all(array $params = [])
    {
        return $this->client->get(sprintf('/%s', $this->endPoint), $params);
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function get($id)
    {
        return $this->client->get(sprintf('/%s/%s', $this->endPoint, $id));
    }

    /**
     * Checks if the variable passed is not null.
     *
     * @param mixed $var Variable to be checked
     *
     * @return bool
     */
    protected function isNotNull($var)
    {
        return
            false !== $var &&
            null !== $var &&
            '' !== $var &&
            !((is_array($var) || is_object($var)) && empty($var));
    }

    /**
     * @param array $defaults
     * @param array $params
     *
     * @return array
     */
    protected function sanitizeParams(array $defaults, array $params)
    {
        return array_filter(
            array_merge($defaults, $params),
            [$this, 'isNotNull']
        );
    }
}
