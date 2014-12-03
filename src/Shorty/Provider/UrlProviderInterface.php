<?php
/**
 * - UrlProviderInterface.php
 *
 * @author chris
 * @created 02/12/14 17:23
 */
namespace Shorty\Provider;

use Shorty\Model\UrlInterface;

interface UrlProviderInterface
{
    /**
     * Gets a new url instance.
     *
     * @return UrlInterface
     */
    public function createNew();
}