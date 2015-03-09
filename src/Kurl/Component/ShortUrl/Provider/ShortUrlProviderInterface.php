<?php
/**
 * - ShortUrlProviderInterface.php
 *
 * @author chris
 * @created 02/02/15 10:08
 */
namespace Kurl\Component\ShortUrl\Provider;

use Kurl\Component\ShortUrl\Model\ShortUrlInterface;

interface ShortUrlProviderInterface
{
    /**
     * Gets a new vanilla short URL object.
     *
     * @return ShortUrlInterface
     */
    public function createNew();

    /**
     * Gets a short URL by key.
     *
     * @param string $key
     *
     * @return ShortUrlInterface
     */
    public function getShortUrl($key);
}