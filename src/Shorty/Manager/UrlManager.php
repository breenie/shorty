<?php
/**
 * The URL manager.
 *
 * @author  chris
 * @created 04/12/14 08:32
 */

namespace Shorty\Manager;

use DateTime;
use Shorty\Event as ShortyEvents;
use Shorty\Exception\UrlNotFoundException;
use Shorty\Model\UrlInterface;
use Shorty\Provider\UrlProviderInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class UrlManager
 *
 * @package Shorty\Manager
 */
class UrlManager
{

    /**
     * The url provider.
     *
     * @var UrlProviderInterface
     */
    protected $provider;

    /**
     * The event dispatcher.
     *
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * Creates a new url manager.
     *
     * @param UrlProviderInterface $provider
     * @param EventDispatcher      $dispatcher
     */
    public function __construct(UrlProviderInterface $provider, EventDispatcher $dispatcher = null)
    {
        $this->provider   = $provider;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Expands a short URL and returns the long URL.
     *
     * @param string $fragment
     *
     * @throws UrlNotFoundException
     * @return string
     */
    public function expand($fragment)
    {
        $this->dispatch(ShortyEvents\Events::PRE_EXPAND, new ShortyEvents\FragmentEvent($fragment));

        if (null === $url = $this->provider->expand($fragment)) {
            throw new UrlNotFoundException(sprintf('Cannot find a URL for %1$s.', $fragment));
        }

        $this->dispatch(ShortyEvents\Events::POST_EXPAND, new ShortyEvents\UrlEvent($url));

        return $url->getUrl();
    }

    /**
     * Shortens a long URL and returns the created short name.
     *
     * @param string $link
     *
     * @return string
     */
    public function shorten($link)
    {
        $this->dispatch(ShortyEvents\Events::PRE_SHORTEN, new ShortyEvents\LinkEvent($link));
        $shortened = $this->provider->shorten($link);

        $shortened->setCreated(new DateTime());
        $shortened->setVisits(0);

        $url = $this->provider->persist($shortened);
        $this->dispatch(ShortyEvents\Events::POST_SHORTEN, new ShortyEvents\UrlEvent($url));

        return $url->getFragment();
    }

    /**
     * Increments the visit count on a url and returns the long URL.
     *
     * @param string $fragment
     *
     * @return string
     */
    public function click($fragment)
    {
        $url = $this->expand($fragment);
        $url->getVisits($url->getVisits() + 1);
        $url->setLastVisit(new DateTime());

        return $this->provider->persist($url)->getUrl();
    }

    /**
     * Gets an array of URL details.
     *
     * @param int $limit
     * @param int $offset
     * @param int $userId
     *
     * @return array
     */
    public function query($limit = null, $offset = null, $userId = null)
    {
        return array_map(array($this, 'urlToArray'), $this->provider->query($limit, $offset, $userId));
    }

    /**
     * Gets an array interpretation of a url.
     *
     * @param UrlInterface $url
     *
     * @return array
     */
    protected function urlToArray(UrlInterface $url)
    {
        return array(
            'fragment'    => $url->getFragment(),
            'long_url'    => $url->getUrl(),
            'created'     => $url->getCreated(),
            'visits'      => $url->getVisits(),
            'visit_count' => $url->getVisits()
        );
    }

    /**
     * Dispatches an event.
     *
     * @param string $eventName
     * @param Event  $event
     */
    protected function dispatch($eventName, Event $event)
    {
        if (null !== $this->dispatcher) {
            $this->dispatcher->dispatch($eventName, $event);
        }
    }
}