<?php
/**
 * Shorty event names.
 *
 * @author  chris
 * @created 04/12/14 09:28
 */

namespace Shorty\Event;

/**
 * Class Events
 *
 * @package Shorty\Event
 */
class Events
{
    /**
     * The pre shorten event name.
     *
     * @var string
     */
    const PRE_SHORTEN = 'shorty.shorten.pre';

    /**
     * The post shorten event name.
     *
     * @var string
     */
    const POST_SHORTEN = 'shorty.shorten.post';

    /**
     * The pre expand event name.
     *
     * @var string
     */
    const PRE_EXPAND = 'shorty.expand.pre';

    /**
     * The post expand event name.
     *
     * @var string
     */
    const POST_EXPAND = 'shorty.expand.post';
}