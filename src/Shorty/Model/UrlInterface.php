<?php
/**
 * A URL interface.
 *
 * @author chris
 * @created 02/12/14 17:13
 */

namespace Shorty\Model;

use DateTime;

/**
 * Interface UrlInterface
 *
 * @package Shorty\Model
 */
interface UrlInterface
{
    /**
     * Gets the short part of the URL.
     *
     * @return int
     */
    public function getFragment();

    /**
     * Gets the url.
     *
     * @return string
     */
    public function getUrl();

    /**
     * Gets the url created datetime.
     *
     * @return DateTime
     */
    public function getCreated();

    /**
     * Sets the created date time.
     *
     * @param DateTime $created
     */
    public function setCreated(DateTime $created);

    /**
     * Gets the number of times the url has been clicked.
     *
     * @return int
     */
    public function getVisits();

    /**
     * Sets the visit count.
     *
     * @param int $count
     */
    public function setVisits($count);

    /**
     * Gets the date time the URL was last visited.
     *
     * @return DateTime
     */
    public function getLastVisit();

    /**
     * Sets the last visited date time.
     *
     * @param DateTime $dateTime
     */
    public function setLastVisit(DateTime $dateTime);
}