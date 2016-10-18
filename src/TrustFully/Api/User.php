<?php

namespace TrustFully\Api;

class User extends AbstractApi
{
    protected $endPoint = 'users';

    /**
     * @param string $email
     * @param string $plainPassword
     * @param array  $extraData
     *
     * @return array
     */
    public function create($email, $plainPassword, array $extraData = array())
    {
        $defaults = array(
            'email' => $email,
            'plainPassword' => $plainPassword,
            'gender' => null,
            'greeting' => null,
            'firstname' => null,
            'lastname' => null,
            'city' => null,
            'state' => null,
            'countryCode' => null,
        );
        $params = $this->sanitizeParams($defaults, $extraData);
        $json = $this->client->post(sprintf('/%s', $this->endPoint), $params);

        return $this->client->decode($json);
    }

    /**
     * @param string $id
     * @param array  $params
     *
     * @return array
     */
    public function update($id, array $params = array())
    {
        $defaults = array(
            'email' => null,
            'plainPassword' => null,
            'gender' => null,
            'greeting' => null,
            'firstname' => null,
            'lastname' => null,
            'city' => null,
            'state' => null,
            'countryCode' => null,
        );
        $params = $this->sanitizeParams($defaults, $params);
        $json = $this->client->put(sprintf('/%s/%s', $this->endPoint, $id), $params);

        return $this->client->decode($json);
    }
}
