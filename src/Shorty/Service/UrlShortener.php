<?php
/**
 * - UrlShortener.php
 *
 * @author  chris
 * @created 09/03/15 13:46
 */

namespace Shorty\Service;

use Doctrine\DBAL\Connection;
use Silex\Application;

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
     * @return string
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

        return $this->db->lastInsertId();
    }

    /**
     * Finds the URL data by Id.
     *
     * @param int $id
     *
     * @return null|array
     */
    public function find($id)
    {
        $query = 'select id, url, created from shorty_url where id = :name';
        return $this->db->fetchAssoc($query, array('name' => (int)$id)) ?: null;
    }

    /**
     * Registers a click to a short URL.
     *
     * @param int    $id
     * @param string $userAgent
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
    }
}