<?php
/**
 * The fragment shorten event.
 *
 * @author chris
 * @created 04/12/14 09:19
 */

namespace Shorty\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class FragmentEvent
 *
 * @package Shorty\Event
 */
class FragmentEvent extends Event
{

    /**
     * The fragment to be shortened.
     *
     * @var string
     */
    protected $fragment;

    /**
     * Creates a new event.
     *
     * @param string $fragment
     */
    public function __construct($fragment)
    {
        $this->fragment = $fragment;
    }

    /**
     * Gets the short URL fragment to be expanded.
     *
     * @return string
     */
    public function getFragment()
    {
        return $this->fragment;
    }
}