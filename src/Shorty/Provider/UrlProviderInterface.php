<?php
/**
 * The URL provider interface.
 *
 * @author chris
 * @created 02/12/14 17:23
 */
namespace Shorty\Provider;

use ArrayAccess;
use Shorty\Model\UrlInterface;

/**
 * Interface UrlProviderInterface
 *
 * @package Shorty\Provider
 */
interface UrlProviderInterface
{
    /**
     * Generates a new url instance.
     *
     * @param string $link
     *
     * @return UrlInterface
     */
    public function shorten($link);

    /**
     * Stores a URL.
     *
     * @param UrlInterface $url
     *
     * @return UrlInterface
     */
    public function persist(UrlInterface $url);

    /**
     * Finds a URL by fragment.
     *
     * @param string $fragment
     *
     * @return UrlInterface|null
     */
    public function expand($fragment);

    /**
     * Increases the visit count of a url.
     *
     * @param UrlInterface $url
     *
     * @return UrlInterface
     */
    public function visit(UrlInterface $url);

    /**
     * Gets an array of URLs.
     *
     * @param int $limit
     * @param int $offset
     * @param int $userId
     *
     * @return array
     */
    public function query($limit = null, $offset = null, $userId = null);
}