<?php

namespace TrustFully\Api;

class Community extends AbstractApi
{
    /**
     * @param array $params
     *
     * @return array
     */
    public function all(array $params = array())
    {
        return $this->client->get('/communities', $params);
    }
}
