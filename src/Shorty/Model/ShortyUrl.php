<?php
/**
 * Class representation of a URL.
 *
 * @author chris
 * @created 16/05/15 10:27
 */

namespace Shorty\Model;

use InvalidArgumentException;
use Kurl\Maths\Encode\Base62;

class ShortyUrl implements \JsonSerializable
{
    /**
     * The id.
     *
     * @var int
     */
    protected $id;

    /**
     * The long URL.
     *
     * @var string
     */
    protected $url;

    /**
     * The total number of visits the URL has had.
     *
     * @var int
     */
    protected $clicks;

    /**
     * The created timestamp.
     *
     * @var \DateTime
     */
    protected $created;

    /**
     * The int encoder.
     *
     * @var Base62
     */
    protected $encoder;

    /**
     * Creates a new one.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        $required = ['id', 'url', 'clicks', 'created'];

        if ($missing = array_diff($required, array_keys($values))) {
            throw new InvalidArgumentException('Config is missing the following keys: ' . implode(', ', $missing));
        }

        $this->encoder = new Base62();
        $this->id      = (int)$values['id'];
        $this->url     = $values['url'];
        $this->clicks  = (int)$values['clicks'];
        $this->created = new \DateTime($values['created']);
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *       which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        return [
            'id'      => $this->encoder->encode($this->id), // Reusing "id" may seem confusing but the int Id should not be exposed really so it's all good.
            'url'     => $this->url,
            'clicks'  => $this->clicks,
            'created' => $this->created->format('c')
        ];
    }
}