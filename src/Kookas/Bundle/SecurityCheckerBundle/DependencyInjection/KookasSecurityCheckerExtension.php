<?php

/**
 * This file is part of kookas/bundle/security-checker.
 *
 * (c) Ashleigh Udoh <mail@audoh.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kookas\Bundle\SecurityCheckerBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class KookasSecurityCheckerExtension extends Extension
{
	public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
		    $container,
		    new FileLocator(__DIR__.'/../Resources/config')
		);
		$loader->load('services.yml');
    }
}
