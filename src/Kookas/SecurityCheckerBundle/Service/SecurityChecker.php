<?php

/**
 * This file is part of kookas/security-checker.
 *
 * (c) Ashleigh Udoh <mail@audoh.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kookas\SecurityCheckerBundle\Service;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface;
use Sensio\Bundle\FrameworkExtraBundle\Security\ExpressionLanguage;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;

use ReflectionMethod;
use Doctrine\Common\Annotations\AnnotationReader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SecurityChecker
{
	private $authChecker;
	private $tokenStorage;
	private $roleHierarchy;
	private $trustResolver;
	private $router;
	private $requestStack;

	private $reader;
	private $language;

	public function __construct(AuthorizationCheckerInterface $authChecker, TokenStorageInterface $tokenStorage, RoleHierarchyInterface $roleHierarchy, AuthenticationTrustResolverInterface $trustResolver, Router $router, RequestStack $requestStack, ExpressionLanguage $language)
	{
		$this->authChecker = $authChecker;
		$this->tokenStorage = $tokenStorage;
		$this->roleHierarchy = $roleHierarchy;
		$this->trustResolver = $trustResolver;
		$this->router = $router;
		$this->requestStack = $requestStack;

		$this->reader = new AnnotationReader();
		$this->language = $language;
	}

	public function canAccess($routeName, $routeParams = [])
	{
		// Resolve route to controller method.

		$route = $this->router->getRouteCollection()->get($routeName);
		$method = $route->getDefaults()['_controller'];

		list($class, $method) = explode('::', $method);

		// Get method annotations.

		$reflectionMethod = new ReflectionMethod($class, $method);
		$actionAnnotations = $this->reader->getMethodAnnotations($reflectionMethod);

		// Find the security annotation.

		$securityAnnotation = null;

		foreach($actionAnnotations as $annotation)
		{
			if($annotation instanceof Security)
			{
				$securityAnnotation = $annotation;
				break;
			}

		}

		// If not found, assume true.

		if(!$securityAnnotation)
			return true;

		// Get security expression.

		$expression = $securityAnnotation->getExpression();

		// Evaluate expression via AuthorizationChecker.

		return $this->language->evaluate($expression, $this->getVariables($routeParams));
	}

	public function getVariables($routeParams)
	{
		$request = $this->requestStack->getCurrentRequest();

		$token = $this->tokenStorage->getToken();

        if ($this->roleHierarchy != null)
            $roles = $this->roleHierarchy->getReachableRoles($token->getRoles());
        else
            $roles = $token->getRoles();

		$variables =
		[
            'token' => $token,
            'user' => $token->getUser(),
            'object' => $request,
            'request' => $request,
            'roles' => array_map(function ($role) { return $role->getRole(); }, $roles),
            'trust_resolver' => $this->trustResolver,
            // needed for the is_granted expression function
            'auth_checker' => $this->authChecker,
        ];

		return array_merge($routeParams, $variables);
	}
}