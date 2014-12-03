<?php
/**
 * - UrlInterface.php
 *
 * @author chris
 * @created 02/12/14 17:13
 */

namespace Shorty\Model;

interface UrlInterface
{
    /**
     * Gets the url's Id.
     *
     * @return int
     */
    public function getId();

    /**
     * Gets the url.
     *
     * @return string
     */
    public function getUrl();

    /**
     * Gets the url created datetime.
     *
     * @return \DateTime
     */
    public function getCreated();

    /**
     * Gets the number of times the url has been clicked.
     *
     * @return int
     */
    public function getClicked();
}