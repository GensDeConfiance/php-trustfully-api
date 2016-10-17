<?php

namespace TrustFully\Api;

class Sponsorship extends AbstractApi
{
    /**
     * @param array $params
     *
     * @return array
     */
    public function all(array $params = array())
    {
        return $this->client->get('/sponsorships', $params);
    }
}
