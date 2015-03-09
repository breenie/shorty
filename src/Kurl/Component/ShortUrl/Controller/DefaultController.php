<?php
/**
 * An example controller.
 *
 * @author chris
 * @created 19/11/14 14:38
 */

namespace Kurl\Component\ShortUrl\Controller;

use Exception;
use Kurl\Component\ShortUrl\Manager\ShortUrlManager;
use Kurl\Component\ShortUrl\Manager\ShortUrlMetaManager;
use Kurl\Component\View\View;
use Kurl\Component\View\ViewHandlerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class DefaultController
 *
 * @package App\Controller
 */
class DefaultController extends AbstractController
{
    /**
     * The short URL manager.
     * @var ShortUrlManager
     */
    protected $manager;

    /**
     * The short URL meta manager.
     *
     * @var ShortUrlMetaManager
     */
    protected $metaManager;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * The session for flash bags etc.
     *
     * @var Session
     */
    private $session;

    public function __construct(FormFactoryInterface $formFactory, ViewHandlerInterface $viewHandler, LoggerInterface $logger)
    {
        parent::__construct($viewHandler, $logger);
        $this->formFactory = $formFactory;
    }

    /**
     * A default action.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $form = $this->getForm();

        $view = new View();
        $view->setTemplate('default/index.html.twig');
        $view->setData(array('form' => $form->createView()));

        return $this->handleView($view, $request);
    }

    /**
     * Creates a new short URL.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $form = $this->getForm();

        $form->handleRequest($request);

        if (true === $form->isValid()) {
            $data = $form->getData();

            try {
                $url = $this->manager->createShortUrl($data['short_url']);
                $message = sprintf('Created %1$s/%2$s', 'http://hostname', $url->getKey());
            } catch (Exception $e) {
                $message = $e->getMessage();
            }

            $this->session->getFlashBag()->add('info', $message);

            return new RedirectResponse('/');
        }

        $view = new View();
        $view->setTemplate('default/index.html.twig');
        $view->setData(array('form' => $form->createView()));

        return $this->handleView($view, $request);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    private function getForm()
    {
        $form = $this->formFactory->create('kurl_short_url', array('method' => 'post'));

        return $form;
    }
}
