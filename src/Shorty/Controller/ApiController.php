<?php
/**
 * Simple API for short URLs.
 *
 * @author chris
 * @created 16/04/15 13:05
 */

namespace Shorty\Controller;

use Shorty\Service\UrlShortener;
use Silex\Application;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController
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
     * Gets a list of URLs.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getLinksAction(Request $request)
    {
        $results = $this->getService()->paginate(
            (int)$request->get('offset') ?: 0,
            (int)$request->get('limit') ?: 10,
            0 === strcasecmp('asc', $request->get('direction')) ? SORT_ASC : SORT_DESC
        );

        return new JsonResponse($results);
    }

    /**
     * Gets the details for a single short URL.
     *
     * @param string $id
     *
     * TODO add 404 for not found links
     *
     * @return JsonResponse
     */
    public function getLinkAction($id)
    {
        return new JsonResponse($this->getService()->find($this->app['kurl.base62']->decode($id)));
    }

    /**
     * Adds a click to the short link and returns the short URL.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return JsonResponse
     */
    public function patchLinkClicksAction(Request $request, $id)
    {
        return new JsonResponse(
            $this->getService()->registerClick(
                $this->app['kurl.base62']->decode($id),
                $request->headers->get('User-Agent')
            )
        );
    }

    /**
     * Creates a new short URL.
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function postLinkAction(Request $request)
    {
        $response = new JsonResponse();

        /** @var Form $form */
        $form = $this->app['shorty.form.create_url'];
        $form->handleRequest($request);

        if (true === $form->isValid()) {
            $data = $form->getData();

            $created = $this->getService()->create($data['url']);

            $readable = $created->jsonSerialize();

            $response->setStatusCode(Response::HTTP_CREATED);
            $response->setData($created);
            $response->headers->set(
                'Location',
                $this->app['url_generator']->generate('kurl_shorty_details', ['id' => $readable['id']]),
                true
            );
        } else {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $response->setData($form->getErrors());
        }

        return $response;
    }

    /**
     * Gets the URL shortener service.
     *
     * @return UrlShortener
     */
    private function getService()
    {
        return $this->app['kurl.service.url_shortener'];
    }
}