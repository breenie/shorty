<?php
/**
 * Boilerplate abstract controller.
 *
 * @author chris
 * @created 02/02/15 10:33
 */

namespace Kurl\Component\ShortUrl\Controller;

use Kurl\Component\View\ViewHandlerInterface;
use Kurl\Component\View\View;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * The view handler.
     *
     * @var ViewHandlerInterface
     */
    protected $viewHandler;

    /**
     * Creates a new controller.
     *
     * @param ViewHandlerInterface $viewHandler
     * @param LoggerInterface      $logger
     */
    public function __construct(ViewHandlerInterface $viewHandler, LoggerInterface $logger)
    {
        $this->viewHandler = $viewHandler;
        $this->logger = $logger;
    }

    /**
     * Handles the view.
     *
     * @param View    $view
     * @param Request $request
     *
     * @return Response
     */
    protected function handleView(View $view, Request $request)
    {
        return $this->viewHandler->handle($view, $request);
    }
}