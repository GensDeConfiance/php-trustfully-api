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
        $data = array(
            '_username' => $username,
            '_password' => $password,
        );

        return $this->client->post('/login_check', $data, $encode = false);
    }
}
