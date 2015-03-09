<?php
/**
 * - ShortUrlInterface.php
 *
 * @author chris
 * @created 02/02/15 10:01
 */
namespace Kurl\Component\ShortUrl\Model;

use DateTime;

interface ShortUrlInterface
{
    /**
     * Gets the key for the short URL.
     *
     * @return string
     */
    public function getKey();

    /**
     * Gets the long URL.
     *
     * @return string
     */
    public function getUrl();

    /**
     * Sets the long URL.
     *
     * @param string $url
     */
    public function setUrl($url);

    /**
     * Gets the date time the URL was created.
     *
     * @return DateTime
     */
    public function getCreatedAt();

    /**
     * Sets the created at date time.
     *
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt);
}