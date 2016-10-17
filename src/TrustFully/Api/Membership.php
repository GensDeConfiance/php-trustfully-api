<?php

namespace TrustFully\Api;

class Membership extends AbstractApi
{
    /**
     * @param array $params
     *
     * @return array
     */
    public function all(array $params = array())
    {
        return $this->client->get('/memberships', $params);
    }
}
