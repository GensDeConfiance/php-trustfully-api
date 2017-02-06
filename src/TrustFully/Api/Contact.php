<?php

namespace TrustFully\Api;

class Contact extends AbstractApi implements ContactInterface
{
    protected $endPoint = 'contacts';

    /**
     * {@inheritdoc}
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

        return $this->generateClass($json);
    }

    /**
     * {@inheritdoc}
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

        return $this->generateClass($json);
    }
}
