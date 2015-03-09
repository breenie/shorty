<?php
/**
 * Exercises the ShortUrlManager.
 *
 * @author  chris
 * @created 03/02/15 18:18
 */

namespace Kurl\Component\ShortUrl\Tests;

use Kurl\Component\ShortUrl\Manager\ShortUrlManager;
use Kurl\Component\ShortUrl\Tests\Fixture\MockShortUrl;
use PHPUnit_Framework_TestCase;

class ShortUrlManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests getting a short URL.
     */
    public function testGetShortUrl()
    {
        $provider = $this->getMock('Kurl\Component\ShortUrl\Provider\ShortUrlProviderInterface');
        $provider->expects($this->once())->method('getShortUrl')->will($this->returnValue(new MockShortUrl()));

        /** @noinspection PhpParamsInspection */
        $manager = new ShortUrlManager($provider);

        $this->assertInstanceOf('Kurl\Component\ShortUrl\Model\ShortUrlInterface', $manager->getShortUrl('key'));
    }

    /**
     * Tests short URL creation.
     */
    public function testCreateUrl()
    {
        $url = 'http://example.org/example.html';

        $mock = new MockShortUrl();
        $mock->setUrl($url);

        $provider = $this->getMock('Kurl\Component\ShortUrl\Provider\ShortUrlProviderInterface');
        $provider->expects($this->once())->method('createNew')->will($this->returnValue($mock));

        /** @noinspection PhpParamsInspection */
        $manager = new ShortUrlManager($provider);

        $shortUrl = $manager->createShortUrl($url);

        $this->assertInstanceOf('Kurl\Component\ShortUrl\Model\ShortUrlInterface', $shortUrl);
        $this->assertEquals($url, $shortUrl->getUrl());
        $this->assertNotNull($shortUrl->getCreatedAt());
    }
}
