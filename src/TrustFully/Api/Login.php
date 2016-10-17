<?php

namespace TrustFully\Api;

class Login extends AbstractApi
{
    /**
     * @param string $username
     * @param string $password
     *
     * @return string
     */
    public function getToken($username, $password)
    {
        $params = array(
            '_username' => $username,
            '_password' => $password,
        );

        return $this->client->post('/login_check', $params, $encode = false);
    }
}
