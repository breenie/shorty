<?php
/**
 * Manages short URL meta information.
 *
 * @author chris
 * @created 02/02/15 10:22
 */

namespace Kurl\Component\ShortUrl\Manager;

use Kurl\Component\ShortUrl\Provider\ShortUrlMetaProviderInterface;

class ShortUrlMetaManager
{
    /**
     * The meta provider.
     *
     * @var ShortUrlMetaProviderInterface
     */
    protected $provider;

    /**
     * Creates a new short URL meta manager.
     *
     * @param ShortUrlMetaProviderInterface $provider
     */
    public function __construct(ShortUrlMetaProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Gets the total visits to a set of short URLs.
     * Expected return value:
     *
     * [
     *     "url_key" => [
     *         "visits" => 666,
     *         "meta" => [
     *             "user_agent" => 'blah'
     *             ...
     *         ]
     *     ],
     *     ...
     * ]
     *
     * @param string $keys
     *
     * @return array
     */
    public function getTotalVisits($keys)
    {
        return $this->provider->getTotalVisits($keys);
    }

    /**
     * Adds a visit to the visit count with optional visitor parameters.
     *
     * @param       $key
     * @param array $parameters
     */
    public function addVisit($key, $parameters = array())
    {
        $this->provider->addVisit($key, $parameters);
    }
}