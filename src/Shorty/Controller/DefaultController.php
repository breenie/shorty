<?php
/**
 * An example controller.
 *
 * @author chris
 * @created 19/11/14 14:38
 */

namespace Shorty\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

/**
 * Class DefaultController
 *
 * @package App\Controller
 */
class DefaultController
{
    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Creates a new controller.
     *
     * @param Twig_Environment $twig
     * @param LoggerInterface  $logger
     */
    public function __construct(Twig_Environment $twig, LoggerInterface $logger)
    {
        $this->twig = $twig;
        $this->logger = $logger;
    }

    /**
     * A default action.
     *
     * @return string
     */
    public function indexAction()
    {
        $this->logger->debug(sprintf('Dispatching: %1$s::%2$s', __CLASS__, __METHOD__));

        return $this->twig->render('default/index.html.twig', array('title' => 'Boom!'));
    }
}
