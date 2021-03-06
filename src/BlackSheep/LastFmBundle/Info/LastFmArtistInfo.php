<?php

namespace BlackSheep\LastFmBundle\Info;

use LastFmApi\Api\ArtistApi;

class LastFmArtistInfo extends AbstractLastFmInfo implements LastFmInfo
{
    /**
     * @return ArtistApi
     */
    protected function getApi()
    {
        return new ArtistApi($this->getAuth());
    }
}
