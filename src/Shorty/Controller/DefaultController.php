<?php
/**
 * - DefaultController.php
 *
 * @author  chris
 * @created 09/03/15 12:45
 */

namespace Shorty\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class DefaultController
{

    /**
     * The silex app.
     *
     * @var Application
     */
    private $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * A default action.
     *
     * @return Response
     */
    public function indexAction()
    {
        return new Response(
            $this->app['twig']->render(
                'default/index.html.twig',
                ['form' => $this->app['shorty.form.create_url']->createView()]
            ),
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
        $subRequest = Request::create(
            sprintf('/api/urls/%1$s/clicks.json', $id),
            'PATCH',
            $request->query->all(),
            [],
            [],
            $request->server->all()
        );

        /** @var JsonResponse $response */
        $response = $this->app->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);

        // TODO remove this hack by exposing some of the parts of the ShortyUrl
        $shortened = json_decode($response->getContent(), true);

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
        return new Response($this->app['twig']->render('default/details.html.twig', ['id' => $id]), 200);
    }

    /**
     * Renders the stats page.
     *
     * @return Response
     */
    public function statisticsAction()
    {
        return new Response($this->app['twig']->render('default/statistics.html.twig'), 200);
    }
}