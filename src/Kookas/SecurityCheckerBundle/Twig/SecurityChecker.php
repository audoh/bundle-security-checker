<?php

/**
 * This file is part of kookas/security-checker.
 *
 * (c) Ashleigh Udoh <mail@audoh.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kookas\SecurityCheckerBundle\Twig;

use Kookas\SecurityCheckerBundle\Service\SecurityCheckerService;
use Twig_Extension,
	Twig_SimpleFunction;

class SecurityChecker extends Twig_Extension
{
	private $authTester;

	public function __construct(SecurityCheckerService $authTester)
    {
        $this->authTester = $authTester;
    }

    public function getFunctions()
    {
    	return
    	[
    		new Twig_SimpleFunction('canAccess', [$this, 'canAccess'])
    	];
    }

    public function canAccess($routeName, $routeParams = [])
    {
    	return $this->authTester->canAccess($routeName, $routeParams);
    }
}