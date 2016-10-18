<?php

namespace TrustFully;

interface ClientInterface
{
    /**
     * @param string $path
     * @param array  $params
     * @param bool   $decode
     *
     * @return array
     */
    public function get($path, array $params = [], $decode = true);

     /**
      * @param string $path
      * @param mixed  $data
      * @param bool   $encode
      *
      * @return mixed
      */
     public function post($path, $data = null, $encode = true);

     /**
      * @param string $path
      * @param mixed  $data
      * @param bool   $encode
      *
      * @return array
      */
     public function put($path, $data = null, $encode = true);

    /**
     * @param string $path
     * @param mixed  $data
     *
     * @return array
     */
    public function delete($path, $data = null);

    /**
     * Decodes json response.
     *
     * Returns $json if no error occured during decoding but decoded value is
     * null.
     *
     * @param string $json
     *
     * @return array|string
     */
    public function decode($json);
}
