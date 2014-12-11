<?php
/**
 * - UrlEvent.php
 *
 * @author chris
 * @created 04/12/14 09:24
 */

namespace Shorty\Event;

use Shorty\Model\UrlInterface;
use Symfony\Component\EventDispatcher\Event;

class UrlEvent extends Event
{

    /**
     * @var UrlInterface
     */
    protected $url;

    /**
     * Creates a new URL event.
     *
     * @param UrlInterface $url
     */
    public function __construct(UrlInterface $url)
    {
        $this->url = $url;
    }

    /**
     * Gets the URL.
     *
     * @return UrlInterface
     */
    public function getUrl()
    {
        return $this->url;
    }
}