<?php
/**
 * The link shorten event.
 *
 * @author chris
 * @created 04/12/14 09:19
 */

namespace Shorty\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class LinkEvent
 *
 * @package Shorty\Event
 */
class LinkEvent extends Event
{

    /**
     * The link to be shortened.
     *
     * @var string
     */
    protected $link;

    /**
     * Creates a new event.
     *
     * @param string $link
     */
    public function __construct($link)
    {
        $this->link = $link;
    }

    /**
     * Gets the long URL to be shortened.
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
}