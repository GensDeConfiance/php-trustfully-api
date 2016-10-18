<?php

namespace TrustFully\Api;

class Contact extends AbstractApi
{
    protected $endPoint = 'contacts';

    /**
     * @param array $params
     *
     * @return array
     */
    public function create(array $params = [])
    {
        $defaults = [
            'email' => null,
            'firstname' => null,
            'lastname' => null,
            'fullname' => null,
        ];
        $params = $this->sanitizeParams($defaults, $params);
        $json = $this->client->post(sprintf('/%s', $this->endPoint), $params);

        return $this->client->decode($json);
    }

    /**
     * @param string $id
     * @param array  $params
     *
     * @return array
     */
    public function update($id, array $params = [])
    {
        $defaults = [
            'email' => null,
            'firstname' => null,
            'lastname' => null,
            'fullname' => null,
        ];
        $params = $this->sanitizeParams($defaults, $params);
        $json = $this->client->put(sprintf('/%s/%s', $this->endPoint, $id), $params);

        return $this->client->decode($json);
    }
}
