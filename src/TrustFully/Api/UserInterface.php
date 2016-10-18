<?php

namespace TrustFully\Api;

interface UserInterface
{
    /**
     * @param string $username
     * @param string $password
     *
     * @return array
     */
    public function login($username, $password);

    /**
     * @param string $email
     * @param string $plainPassword
     * @param array  $extraData
     *
     * @return array
     */
    public function create($email, $plainPassword, array $extraData = []);

    /**
     * @param string $id
     * @param array  $params
     *
     * @return array
     */
    public function update($id, array $params = []);
}
