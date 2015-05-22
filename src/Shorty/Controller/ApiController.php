<?php
/**
 * Simple API for short URLs.
 *
 * @author chris
 * @created 16/04/15 13:05
 */

namespace Shorty\Controller;

use PDO;
use Shorty\Service\UrlShortener;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getLinksAction(Request $request)
    {
        /** @var UrlShortener $service */
        $service = $this->app['kurl.service.url_shortener'];
        $results = $service->paginate(
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
     * @return JsonResponse
     */
    public function getLinkAction($id)
    {
        /** @var \Doctrine\DBAL\Connection $db */
        $db = $this->app['db'];

        $statement = $db->prepare(
            <<<EOT
select u.id, u.url, u.created, count(v.shorty_url_id) as clicks
from shorty_url u
left join shorty_url_visit v on v.shorty_url_id = u.id
where u.id = :id
EOT
        );

        $statement->bindValue('id', $this->app['kurl.base62']->decode($id));
        $statement->execute();

        return new JsonResponse($this->formatResult($statement->fetch(PDO::FETCH_ASSOC)));
    }

    private function shortLink($id, $route)
    {
        return $this->app['url_generator']->generate(
            $route,
            ['id' => $this->app['kurl.base62']->encode($id)],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

    }

    /**
     * Formats a result.
     *
     * @param array $link
     *
     * @return mixed
     */
    private function formatResult($link)
    {
        if (null !== $link) {
            $link['details_link'] = $this->shortLink($link['id'], 'kurl_shorty_details');
            $link['short_link']   = $this->shortLink($link['id'], 'kurl_shorty_redirect');
            $link['clicks']       = (int)$link['clicks']; // TODO This will cause issues when counts get huge, they are unlikely to.
        }

        return $link;
    }
}