<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Client for ArcGIS REST Services Directory
 * https://developers.arcgis.com/rest/
 */
class ArcGisClient
{
    /** @var string ArcGIS API URL */
    const BASE_URL = 'https://services7.arcgis.com/mOBPykOjAyBO2ZKk/arcgis/rest/services/RKI_Landkreisdaten/FeatureServer/0/query';

    /**
     * @var array Handled counties
     */
    const COUNTIES = [
        'ROTTAL_INN'       => '255',
        'REGENSBURG_STADT' => '259',
        'REGENSBURG_LK'    => '265',
        'LANDSHUT_LK'      => '252',
        'LANDSHUT_STADT'   => '246',
        'KELHEIM'          => '251',
        'SCHWANDORF'       => '266',
    ];

    /** @var Http HTTP request client */
    protected $client;

    /**
     * Class constructor.
     *
     * @param Client $client automatically injected by Laravel IoC
     */
    public function __construct(Http $client)
    {
        $this->client = $client;
    }

    /**
     * Returns the base URL.
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return static::BASE_URL;
    }

    /**
     * Returns the confirmed cases of given counties for all or one county.
     *
     * @param int $county Use ArcGisClient::COUNTIES array to filter
     * @return null|Collection
     */
    public function getConfirmedCases(int $county = null)
    {
        $counties = $this->client::acceptJson()
             ->get($this->getBaseUrl(), [
                 'objectIds'      => implode(',', static::COUNTIES),
                 'outFields'      => '*',
                 'f'              => 'pgeojson',
                 'returnGeometry' => 'false',
                 'returnCentroid' => 'false',])
             ->throw()
             ->collect('features.*.properties')
             ->keyBy('OBJECTID');

        return $counties ?? $counties->get($county);
    }
}
