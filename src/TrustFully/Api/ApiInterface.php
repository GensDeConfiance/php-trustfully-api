<?php

namespace TrustFully\Api;

interface ApiInterface
{
    /**
     * @param array $params
     *
     * @return array
     */
    public function all(array $params = []);

    /**
     * @param int $id
     *
     * @return array
     */
    public function get($id);
}
