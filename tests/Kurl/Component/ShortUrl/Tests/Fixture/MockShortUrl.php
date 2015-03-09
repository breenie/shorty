<?php
/**
 * - MockShortUrl.php
 *
 * @author chris
 * @created 03/02/15 18:22
 */

namespace Kurl\Component\ShortUrl\Tests\Fixture;

use DateTime;
use Kurl\Component\ShortUrl\Model\ShortUrlInterface;

class MockShortUrl implements ShortUrlInterface
{
    private $key;
    private $url;
    private $created;
    /**
     * Gets the key for the short URL.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Gets the long URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Gets the date time the URL was created.
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->created;
    }

    /**
     * Sets the created at date time.
     *
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->created = $createdAt;
    }

    /**
     * Sets the long URL.
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
}