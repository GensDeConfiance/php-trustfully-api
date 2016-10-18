<?php

namespace TrustFully\Api;

interface ContactInterface
{
    /**
     * @param array $params
     *
     * @return array
     */
    public function create(array $params = []);

    /**
     * @param string $id
     * @param array  $params
     *
     * @return array
     */
    public function update($id, array $params = []);
}
