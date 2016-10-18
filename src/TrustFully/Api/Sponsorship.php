<?php

namespace TrustFully\Api;

class Sponsorship extends AbstractApi
{
    protected $endPoint = 'sponsorships';

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
    public function create($userTo, $communityId, $relationDesc, $relationType, $origin, $userFrom = 'me')
    {
        $params = [
            'userFrom' => !is_array($userFrom) ? sprintf('/api/users/%s', $userFrom) : $userFrom,
            'userTo' => !is_array($userTo) ? sprintf('/api/users/%s', $userTo) : $userTo,
            'community' => sprintf('/api/communities/%s', $communityId),
            'relationDesc' => $relationDesc,
            'relationType' => $relationType,
            'origin' => $origin,
        ];
        $json = $this->client->post(sprintf('/%s', $this->endPoint), $params);

        return $this->client->decode($json);
    }

    public function update($id, $params)
    {
        $defaults = [
            'relationDesc' => null,
            'relationType' => null,
            'origin' => null,
        ];
        $params = $this->sanitizeParams($defaults, $params);
        $json = $this->client->put(sprintf('/%s/%s', $this->endPoint, $id), $params);

        return $this->client->decode($json);
    }
}
