<?php

namespace TrustFully\Api;

class Sponsorship extends AbstractApi implements SponsorshipInterface
{
    protected $endPoint = 'sponsorships';

    /**
     * {@inheritdoc}
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

        return $this->generateClass($json);
    }

     /**
      * {@inheritdoc}
      */
     public function update($id, array $params = [])
     {
         $defaults = [
            'relationDesc' => null,
            'relationType' => null,
            'origin' => null,
        ];
         $params = $this->sanitizeParams($defaults, $params);
         $json = $this->client->put(sprintf('/%s/%s', $this->endPoint, $id), $params);

        return $this->generateClass($json);
     }


}
