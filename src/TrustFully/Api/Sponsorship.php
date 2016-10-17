<?php

namespace TrustFully\Api;

class Sponsorship extends AbstractApi
{
    protected $endPoint = 'sponsorships';

    /**
     * @param string $userToId
     * @param string $communityId
     * @param string $relationDesc
     * @param string $relationType
     * @param string $origin
     *
     * @return array
     */
    public function create($userToId, $communityId, $relationDesc, $relationType, $origin)
    {
        $params = array(
            'community' => sprintf('/api/communities/%s', $userToId),
            'userTo' => sprintf('/api/users/%s', $communityId),
            'relationDesc' => $relationDesc,
            'relationType' => $relationType,
            'origin' => $origin,
        );
        $json = $this->client->post(sprintf('/%s', $this->endPoint), $params);

        return $this->client->decode($json);
    }
}
