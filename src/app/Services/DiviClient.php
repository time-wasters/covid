<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Client for Robert Koch Insitut's https://www.intensivregister.de/
 */
class DiviClient
{
    const STATE_BADEN_WUERTTEMBERG            = 'BADEN_WUERTTEMBERG';
    const STATE_BAVARIA                       = 'BAYERN';
    const STATE_BERLIN                        = 'BERLIN';
    const STATE_BRANDENBURG                   = 'BRANDENBURG';
    const STATE_BREMEN                        = 'BREMEN';
    const STATE_HAMBURG                       = 'HAMBURG';
    const STATE_HESSE                         = 'HESSEN';
    const STATE_LOWER_SAXONY                  = 'NIEDERSACHSEN';
    const STATE_MECKLENBURG_WESTERN_POMERANIA = 'MECKLENBURG_VORPOMMERN';
    const STATE_NORTH_RHINE_WESTPHALIA        = 'NORDRHEIN_WESTFALEN';
    const STATE_RHINELAND_PALATINATE          = 'RHEINLAND_PFALZ';
    const STATE_SAARLAND                      = 'SAARLAND';
    const STATE_SAXONY                        = 'SACHSEN';
    const STATE_SAXONY_ANHALT                 = 'SACHSEN_ANHALT';
    const STATE_SCHLESWIG_HOLSTEIN            = 'SCHLESWIG_HOLSTEIN';
    const STATE_THURINGIA                     = 'THUERINGEN';

    /**
     * Http request client
     *
     * @var Http
     */
    protected $client;

    /**
     * Request client
     *
     * @param Client $client automatically injected by Laravel IoC
     */
    public function __construct(Http $client)
    {
        $this->client = $client;
    }

    /**
     * Returns the hospital info of the requested state
     *
     * @param string $state The state, use constants of this model
     * @return null|array
     */
    public function getHospitalInfo(string $state)
    {
        $states = collect(
            $this->client::acceptJson()
                 ->get(config('covid.hospital_info.url'))
                 ->throw()
                 ->json('data')
        )->keyBy('bundesland');

        return $states->get($state);
    }

}