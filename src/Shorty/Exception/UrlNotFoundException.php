<?php
/**
 * URL entity not found exception.
 *
 * @author chris
 * @created 04/12/14 08:43
 */

namespace Shorty\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UrlNotFoundException
 *
 * @package Shorty\Exception
 */
class UrlNotFoundException extends NotFoundHttpException
{

}