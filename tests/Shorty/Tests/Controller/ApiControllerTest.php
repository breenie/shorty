<?php
/**
 * - ApiControllerTest.php
 *
 * @author  chris
 * @created 16/05/15 10:25
 */
namespace Shorty\Tests\Controller;

use Shorty\Model\ShortyUrl;
use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernel;

class ApiControllerTest extends WebTestCase
{
    /**
     * Stub URL data.
     *
     * @var array
     */
    private $input = [
        'id'      => 1,
        'url'     => 'https://example.com/',
        'created' => '2015-05-16T11:25:49+00:00',
        'clicks'  => 123456789,
    ];

    /**
     * The expected response.
     *
     * @var array
     */
    private $expected = [
        'hash'      => 1,
        'long_url'  => 'https://example.com/',
        'short_url' => 'http://localhost/1',
        'created'   => '2015-05-16T11:25:49+00:00',
        'clicks'    => 123456789,
    ];

    /**
     * Tests finding a single URL by Id.
     */
    public function testGetUrl()
    {
        $this->app['kurl.service.url_shortener'] = $this->getMockBuilder('Shorty\Service\UrlShortener')
            ->disableOriginalConstructor()->getMock();

        $this->app['kurl.service.url_shortener']
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue(new ShortyUrl($this->input)));

        $client  = $this->createClient();
        $client->request('GET', '/api/urls/1.json');

        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertEquals($this->expected, json_decode($client->getResponse()->getContent(), true));
        $this->assertTrue($response->headers->has('Content-Length'));
    }

    /**
     * Tests getting a set of URLs.
     */
    public function testGetUrls()
    {
        $this->app['kurl.service.url_shortener'] = $this->getMockBuilder('Shorty\Service\UrlShortener')
            ->disableOriginalConstructor()->getMock();

        $this->app['kurl.service.url_shortener']
            ->expects($this->once())
            ->method('paginate')
            ->will(
                $this->returnValue(
                    [
                        'total'   => 527,
                        'request' => [
                            'offset'    => 0,
                            'limit'     => 10,
                            'direction' => 'asc',
                        ],
                        'results' => array_map(
                            function ($data) {
                                return new ShortyUrl($data);
                            },
                            json_decode(file_get_contents(__DIR__ . '/Fixtures/get-urls.json'), true)
                        ),
                    ]
                )
            );

        $client  = $this->createClient();
        $client->request('GET', '/api/urls.json');

        $response = $client->getResponse();
        $decoded = json_decode($response->getContent(), true);

        $this->assertTrue($response->isOk());
        $this->assertTrue($response->headers->has('Content-Length'));
        $this->assertArrayHasKey('total', $decoded);
        $this->assertArrayHasKey('results', $decoded);
        $this->assertArrayHasKey('request', $decoded);
        $this->assertEquals(527, $decoded['total']);
        $this->assertEquals(10, count($decoded['results']));

        foreach ($decoded['results'] as $url) {
            $this->assertArrayHasKey('hash', $url);
            $this->assertArrayHasKey('long_url', $url);
            $this->assertArrayHasKey('short_url', $url);
            $this->assertArrayHasKey('clicks', $url);
            $this->assertArrayHasKey('created', $url);
        }
    }

    /**
     * Tests creating a URL.
     */
    public function testPostUrlJson()
    {
        $this->app['kurl.service.url_shortener'] = $this->getMockBuilder('Shorty\Service\UrlShortener')
            ->disableOriginalConstructor()->getMock();

        $this->app['kurl.service.url_shortener']
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue(new ShortyUrl($this->input)));

        $client  = $this->createClient();
        $client->request(
            'POST',
            '/api/urls.json',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'],
            json_encode(['form' => ['url' => $this->input['url']]])
        );

        $response = $client->getResponse();

        $decoded = json_decode($response->getContent(), true);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($this->expected, $decoded);
        $this->assertTrue($response->headers->has('Location'));
    }

    /**
     * Tests posting with missing parameters.
     *
     * TODO improve this test to take into account the form validation in errors.
     */
    public function testPostUrlValidationFailed()
    {
        $client  = $this->createClient();
        $client->request(
            'POST',
            '/api/urls.json',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['form' => []])
        );

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * Tests a bad JSON request is handled gracefully.
     */
    public function testBadJsonRequest()
    {
        $client  = $this->createClient();
        $client->request(
            'POST',
            '/api/urls.json',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"json-ish":'
        );

        $response = $client->getResponse();

        // TODO update this test once the exception handler plays nicely with JSON requests.
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertContains('Could not decode JSON body. Syntax error', $response->getContent());
    }

    /**
     * Creates the application.
     *
     * @return HttpKernel
     */
    public function createApplication()
    {
        return require __DIR__ . '/../../../bootstrap.php';
    }
}
