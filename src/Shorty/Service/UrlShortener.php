<?php
/**
 * - UrlShortener.php
 *
 * @author  chris
 * @created 09/03/15 13:46
 */

namespace Shorty\Service;

use Closure;
use Doctrine\DBAL\Connection;
use PDO;
use Shorty\Model\ShortyUrl;

class UrlShortener
{

    /**
     * The db connection.
     *
     * @var Connection
     */
    private $db;

    /**
     * Creates a new url shortener service.
     *
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Creates a new short URL entry and returns the string representation of the ID of the created entry.
     *
     * @param $url
     *
     * @return ShortyUrl
     */
    public function create($url)
    {
        $now = new \DateTime();

        $this->db->insert(
            'shorty_url',
            array(
                'url'     => $url,
                'created' => $now->format('Y-m-d H:i:s'),
            )
        );

        return $this->find($this->db->lastInsertId());
    }

    /**
     * Finds the URL data by Id.
     *
     * @param int $id
     *
     * @return null|ShortyUrl
     */
    public function find($id)
    {
        $query = <<<EOT
select u.id, u.url, u.created, count(v.shorty_url_id) as clicks
from shorty_url u
left join shorty_url_visit v on v.shorty_url_id = :id
where u.id = :id
group by u.id
EOT;

        $result = $this->db->fetchAssoc($query, array('id' => (int)$id));

        return true === empty($result) ? null : $this->hydrate($result);
    }

    /**
     * Paginates short urls, ordered by created date.
     *
     * @param int      $offset
     * @param int      $limit
     * @param int      $direction
     * @param int|null $userId
     *
     * @return array
     */
    public function paginate($offset = 0, $limit = 10, $direction = SORT_DESC, $userId = null)
    {
        $sort = SORT_ASC === $direction ? 'asc' : 'desc';

        /** @var \Doctrine\DBAL\Driver\Statement $statement */
        $statement = $this->db->prepare(
            <<<EOT
select
    SQL_CALC_FOUND_ROWS u.id, u.url, u.created, count(v.shorty_url_id) as clicks
from shorty_url u
left join shorty_url_visit v on v.shorty_url_id = u.id
group by u.id
order by u.id $sort
limit :limit offset :offset
EOT
        );

        $statement->bindValue('offset', $offset, PDO::PARAM_INT);
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return [
            'total'     => (int)$this->db->query('select found_rows()')->fetch(PDO::FETCH_COLUMN),
            // TODO consider removing the request node
            'request' => [
                'offset'    => (int)$offset,
                'limit'     => (int)$limit,
                'direction' => $sort,
            ],
            'results'   => array_map(array($this, 'hydrate'), $statement->fetchAll(PDO::FETCH_ASSOC)),
        ];
    }

    /**
     * Registers a click to a short URL.
     *
     * @param int    $id
     * @param string $userAgent
     *
     * @return ShortyUrl the URL clicked.
     */
    public function registerClick($id, $userAgent)
    {
        $now = new \DateTime();

        $this->db->insert(
            'shorty_url_visit',
            array(
                'shorty_url_id' => $id,
                'user_agent'    => $userAgent,
                'created'       => $now->format('Y-m-d H:i:s'),
            )
        );

        return $this->find($id);
    }

    /**
     * Hydrates a new URL model.
     *
     * @param array $data
     *
     * @return ShortyUrl
     */
    public function hydrate(array $data)
    {
        $model = new ShortyUrl($data);
        return $model;
    }
}