<?php

namespace TrustFully\Api;

use TrustFully\Client;
use TrustFully\ClientInterface;

/**
 * Abstract class for Api classes.
 */
abstract class AbstractApi implements ApiInterface
{
    const HYDRA_MEMBER = 'hydra:member';

    const HYDRA_TYPE = '@type';

    const HYDRA_ID = '@id';

    const HYDRA_COLLECTION = 'hydra:Collection';

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $endPoint;

    /**
     * @var array
     */
    protected $properties = [];

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client, array $properties = [])
    {
        $this->client = $client;
        $this->properties = $properties;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function all(array $params = [])
    {
        $data = $this->client->get(sprintf('/%s', $this->endPoint), $params);
        if (is_array($data) && isset($data[self::HYDRA_MEMBER]) && $data[self::HYDRA_TYPE] === self::HYDRA_COLLECTION) {
            $results = [];

            foreach ($data[self::HYDRA_MEMBER] as $item) {
                $class = get_class($this);
                $results[] = new $class($this->client, $item);
            }
        }

        return $results;
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function get($id)
    {
        $properties = $this->client->get(sprintf('/%s/%s', $this->endPoint, $id));
        $class = get_class($this);

        return new $class($this->client, $properties);
    }

    /**
     * @param string $method
     * @param mixed  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (true === preg_match('%get(.*)%', $method, $matches)) {
            $property = lcfirst($matches[1]);
            $value = isset($this->properties[$property]) ? $this->properties[$property] : null;

            if (true === preg_match('%(.*)At%', $property)) {
                $value = new \DateTime($value);
            }

            return $value;
        }

        if (true === preg_match('%set(.*)%', $method, $matches)) {
            $property = lcfirst($matches[1]);

            return $this->properties[$property] = $args;
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->properties;
    }

    /**
     * @return int
     */
    public function getId()
    {
        if (0 === count($this->properties) || !isset($this->properties[self::HYDRA_ID])) {
            return null;
        }

        preg_match('%(.*)/([0-9]+)%', $this->properties[self::HYDRA_ID], $matches);

        return (int) end($matches);
    }

    public function flush()
    {
        return $this->update($this->getId(), $this->properties);
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
