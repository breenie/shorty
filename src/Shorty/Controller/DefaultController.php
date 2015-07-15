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
}