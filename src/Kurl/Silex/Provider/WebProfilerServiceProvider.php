<?php
/**
 * - WebProfilerServiceProvider.php
 *
 * @author chris
 * @created 13/04/15 11:56
 */

namespace Kurl\Silex\Provider;

use Silex\Application;
use Silex\Provider\WebProfilerServiceProvider as SymfonyWebProfilerServiceProvider;
use Symfony\Bridge\Twig\Extension\YamlExtension;
use Symfony\Bundle\SecurityBundle\DataCollector\SecurityDataCollector;
use Symfony\Bundle\WebProfilerBundle\Controller\ProfilerController;

class WebProfilerServiceProvider extends SymfonyWebProfilerServiceProvider
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app
     */
    public function register(Application $app)
    {
        parent::register($app);

        $app['security_profiler.templates_path'] = function() {
            $r = new \ReflectionClass('Symfony\Bundle\SecurityBundle\SecurityBundle');

            return dirname($r->getFileName()) . '/Resources/views';
        };

        $app['data_collectors'] = $app->share($app->extend('data_collectors', function ($collectors, $app) {
            $collectors['security'] = $app->share(
                function ($app) {
                    return new SecurityDataCollector($app['security']);
                }
            );

            return $collectors;
        }));

        $app['web_profiler.controller.profiler'] = $app->share(
            $app->extend(
                'web_profiler.controller.profiler',
                function ($profiler, $app) {
                    /** @var ProfilerController $profiler */

                    // Due to some bug in PHP you cannot modify an array but only override it directly.
                    $app['data_collector.templates'] = array_merge(
                        $app['data_collector.templates'],
                        array(array('security', '@Security/Collector/security.html.twig'))
                    );

                    return new ProfilerController(
                        $app['url_generator'],
                        $app['profiler'],
                        $app['twig'],
                        $app['data_collector.templates'],
                        $app['web_profiler.debug_toolbar.position']
                    );
                }
            )
        );

        $app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
            if (class_exists('Symfony\Bridge\Twig\Extension\YamlExtension')) {
                $twig->addExtension(new YamlExtension());
            }

            return $twig;
        }));

        $app['twig.loader.filesystem'] = $app->share($app->extend('twig.loader.filesystem', function ($loader, $app) {
            $loader->addPath($app['security_profiler.templates_path'], 'Security');

            return $loader;
        }));
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     *
     * @param Application $app
     */
    public function boot(Application $app)
    {
        parent::boot($app);
    }
}