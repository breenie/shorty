<?php
/**
 * - ShortUrl.php
 *
 * @author chris
 * @created 02/02/15 10:57
 */

namespace Shorty\Model;

use DateTime;
use Kurl\Component\ShortUrl\Model\ShortUrlInterface;

class ShortUrl implements ShortUrlInterface
{

    /**
     * Gets the key for the short URL.
     *
     * @return string
     */
    public function getKey()
    {
        // TODO: Implement getKey() method.
    }

    /**
     * Gets the long URL.
     *
     * @return string
     */
    public function getUrl()
    {
        // TODO: Implement getUrl() method.
    }

    /**
     * Gets the date time the URL was created.
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        // TODO: Implement getCreatedAt() method.
    }

    /**
     * Sets the created at date time.
     *
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        // TODO: Implement setCreatedAt() method.
    }
}