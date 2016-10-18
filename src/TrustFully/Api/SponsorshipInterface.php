<?php

namespace TrustFully\Api;

interface SponsorshipInterface
{
    /**
     * @param string $userTo
     * @param string $communityId
     * @param string $relationDesc
     * @param string $relationType
     * @param string $origin
     * @param string $userFrom
     *
     * @return array
     */
    public function create($userTo, $communityId, $relationDesc, $relationType, $origin, $userFrom = 'me');

    /**
     * @param string $id
     * @param array  $params
     *
     * @return array
     */
    public function update($id, array $params = []);
}
