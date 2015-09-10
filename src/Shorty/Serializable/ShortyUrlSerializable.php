<?php
/**
 * - ShortyUrlSerializable.php
 *
 * @author  chris
 * @created 10/09/15 12:02
 */

namespace Shorty\Serializable;

use Kurl\Maths\Encode\Base62;
use Shorty\Model\ShortyUrl;
use Symfony\Component\Routing\Generator\UrlGenerator;

class ShortyUrlSerializable implements \JsonSerializable
{
    /**
     * The shorty url obejct to serialize.
     *
     * @var ShortyUrl
     */
    private $shortyUrl;

    /**
     * The int encoder.
     *
     * @var Base62
     */
    private $encoder;

    /**
     * The URL generator.
     * @var UrlGenerator
     */
    private $generator;

    /**
     * ShortyUrlSerializable constructor.
     *
     * @param ShortyUrl    $shortyUrl
     * @param UrlGenerator $generator
     */
    public function __construct(ShortyUrl $shortyUrl, UrlGenerator $generator = null)
    {
        $this->encoder   = new Base62();
        $this->shortyUrl = $shortyUrl;
        $this->generator = $generator;
    }

    /**
     * Generates a shorty URL.
     *
     * @return string
     */
    public function generateUrl()
    {
        $id = $this->encoder->encode($this->shortyUrl->getId());
        return null === $this->generator ?
            sprintf('http://localhost/%1$s', $id) :
            $this->generator->generate('kurl_shorty_redirect', ['id' => $id], UrlGenerator::ABSOLUTE_URL);
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *        which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'hash'      => $this->encoder->encode($this->shortyUrl->getId()),
            'long_url'  => $this->shortyUrl->getUrl(),
            'short_url' => $this->generateUrl(),
            'clicks'    => $this->shortyUrl->getClicks(),
            'created'   => $this->shortyUrl->getCreated()->format('c')
        ];
    }
}