<?php

namespace TrustFully\Api;

class User extends AbstractApi implements UserInterface
{
    protected $endPoint = 'users';

    /**
     * {@inheritdoc}
     */
    public function login($username, $password)
    {
        $params = [
            '_username' => $username,
            '_password' => $password,
        ];
        $response = $this->client->post('/login_check', $params, $encode = false);
        $me = $this->client->decode($response);
        $this->client->setApiToken($me['token']);

        return $me;
    }

    /**
     * {@inheritdoc}
     */
    public function create($email, $plainPassword, array $extraData = [])
    {
        $defaults = [
            'email' => $email,
            'plainPassword' => $plainPassword,
            'gender' => null,
            'greeting' => null,
            'firstname' => null,
            'lastname' => null,
            'city' => null,
            'state' => null,
            'countryCode' => null,
        ];
        $params = $this->sanitizeParams($defaults, $extraData);
        $json = $this->client->post(sprintf('/%s', $this->endPoint), $params);

        return $this->client->decode($json);
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $params = [])
    {
        $defaults = [
            'email' => null,
            'plainPassword' => null,
            'gender' => null,
            'greeting' => null,
            'firstname' => null,
            'lastname' => null,
            'city' => null,
            'state' => null,
            'countryCode' => null,
        ];
        $params = $this->sanitizeParams($defaults, $params);
        $json = $this->client->put(sprintf('/%s/%s', $this->endPoint, $id), $params);

        return $this->client->decode($json);
    }
}
