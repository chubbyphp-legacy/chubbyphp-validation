<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Provider;

use Chubbyphp\Validation\ServiceProvider\ValidationServiceProvider;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * @deprecated use \Chubbyphp\Validation\ServiceProvider\ValidationServiceProvider
 */
final class ValidationProvider implements ServiceProviderInterface
{
    /**
     * @var ValidationServiceProvider
     */
    private $serviceProvider;

    public function __construct()
    {
        @trigger_error(
            sprintf('Use "%s" instead.', ValidationServiceProvider::class),
            E_USER_DEPRECATED
        );

        $this->serviceProvider = new ValidationServiceProvider();
    }

    public function register(Container $container): void
    {
        $this->serviceProvider->register($container);
    }
}
