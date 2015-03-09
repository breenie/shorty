<?php
/**
 * - DefaultController.php
 *
 * @author  chris
 * @created 09/03/15 12:45
 */

namespace Shorty\Controller;

use Kurl\Maths\Encode\Base62;
use Silex\Application;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController
{

    /**
     * The silex app.
     *
     * @var Application
     */
    private $app;

    /**
     * The URL hash creator/decoder.
     *
     * @var Base62
     */
    private $encoder;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app     = $app;
        $this->encoder = new Base62();
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

        $form->handleRequest($request);

        if (true === $form->isValid()) {
            $data = $form->getData();

            $id = $this->app['kurl.service.url_shortener']->create($data['short_url']);

            $this->app['session']->getFlashBag()->add(
                'success',
                sprintf(
                    'Created new short URL: <a href="%1$s" class="alert-link">%1$s</a>.',
                    $this->generateShortenedUrl($id)
                )
            );

            return $this->app->redirect('/');
        }

        return new Response(
            $this->app['twig']->render('default/index.html.twig', array('form' => $form->createView())),
            200
        );
    }

    /**
     * Redirects a user to the long URL.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function redirectAction(Request $request, $id)
    {
        $shortened = $this->findUrl($id);

        $this->app['kurl.service.url_shortener']->registerClick($id, $request->headers->get('User-Agent'));

        return $this->app->redirect($shortened['url']);
    }

    /**
     * Renders link details.
     *
     * @param $id
     *
     * @return Response
     */
    public function detailsAction($id)
    {
        return new Response(
            $this->app['twig']->render(
                'default/details.html.twig',
                array(
                    'details'   => $this->findUrl($id),
                    'id'        => $id,
                    'short_url' => $this->generateShortenedUrl($this->encoder->decode($id)),
                )
            ),
            200
        );
    }

    /**
     * Gets the create URL form.
     *
     * @return Form
     */
    private function getForm()
    {
        $form = $this->app['form.factory']->createBuilder('form')
            ->setMethod('POST')
            ->add('short_url', 'url', array('required' => true))
            ->getForm();

        return $form;
    }

    /**
     * Generates the full short URL for an ID.
     *
     * @param string $id
     *
     * @return string
     */
    private function generateShortenedUrl($id)
    {
        /** @var Request $request */
        $request = $this->app['request'];

        /** @noinspection PhpParamsInspection */

        return sprintf(
            '%1$s://%2$s%3$s%4$s',
            $request->getScheme(),
            $request->getHost(),
            80 === (int)$request->getPort() ? '' : ':' . $request->getPort(),
            $this->app['url_generator']->generate(
                'kurl_shorty_redirect',
                array('id' => $this->encoder->encode($id))
            )
        );
    }

    /**
     * Finds a URL by its hash looking identifier.
     *
     * @param string $id
     *
     * @return array|null
     */
    private function findUrl($id)
    {
        if (null === $shortened = $this->app['kurl.service.url_shortener']->find($this->encoder->decode($id))) {
            $this->app->abort(404, sprintf('URL "%1$s" cannot be found, boo :(.', $id));
        }

        return $shortened;
    }
}