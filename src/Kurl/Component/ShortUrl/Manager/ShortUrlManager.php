<?php
/**
 * Manages short URLs.
 *
 * @author chris
 * @created 02/02/15 10:07
 */

namespace Kurl\Component\ShortUrl\Manager;

use Kurl\Component\ShortUrl\Model\ShortUrlInterface;
use Kurl\Component\ShortUrl\Provider\ShortUrlProviderInterface;
use \DateTime;

/**
 * Class ShortUrlManager
 *
 * @package Kurl\Component\ShortUrl\Manager
 */
class ShortUrlManager
{
    /**
     * The short URL provider.
     *
     * @var ShortUrlProviderInterface
     */
    protected $provider;

    public function __construct(ShortUrlProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Creates a new shorty URL.
     *
     * @param string $url
     *
     * @return ShortUrlInterface
     */
    public function createShortUrl($url)
    {
        $shorty = $this->provider->createNew();
        $shorty->setUrl($url);
        $shorty->setCreatedAt(new DateTime());

        return $shorty;
    }

    /**
     * Gets a short URL by key.
     *
     * @param string $key
     *
     * @return ShortUrlInterface
     */
    public function getShortUrl($key)
    {
        return $this->provider->getShortUrl($key);
    }
}