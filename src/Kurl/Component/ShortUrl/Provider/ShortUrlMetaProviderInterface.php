<?php
/**
 * Gets and sets meta information about short URLs.
 *
 * @author chris
 * @created 02/02/15 10:23
 */
namespace Kurl\Component\ShortUrl\Provider;

interface ShortUrlMetaProviderInterface
{
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

    public function getTotalVisits($keys);

    /**
     * Adds a visit to the visit count with optional visitor parameters.
     *
     * @param       $key
     * @param array $parameters
     */
    public function addVisit($key, $parameters = array());
}