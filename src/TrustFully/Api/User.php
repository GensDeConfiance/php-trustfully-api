<?php

namespace TrustFully\Api;

class User extends AbstractApi
{
    /**
     * @param array $params
     *
     * @return array
     */
    public function all(array $params = array())
    {
        return $this->client->get('/users', $params);
    }
}
