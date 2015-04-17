<?php
/**
 * Simple API for short URLs.
 *
 * @author chris
 * @created 16/04/15 13:05
 */

namespace Shorty\Controller;

use PDO;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
     * @return JsonResponse
     */
    public function getLinksAction()
    {
        /** @var \Doctrine\DBAL\Connection $db */
        $db = $this->app['db'];

        $links = $db->query('select * from shorty_url order by id desc limit 10')->fetchAll(PDO::FETCH_ASSOC);

        foreach ($links as $key => $url) {
            $links[$key]['details_link'] = $this->shortLink($url['id'], 'kurl_shorty_details');
            $links[$key]['clicks'] = rand(0, 100000);
            $links[$key]['short_link'] = $this->shortLink($url['id'], 'kurl_shorty_redirect');
        }

        return new JsonResponse($links);
    }

    private function shortLink($id, $route)
    {
        return $this->app['url_generator']->generate(
            $route,
            ['id' => $this->app['kurl.base62']->encode($id)],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

    }
}