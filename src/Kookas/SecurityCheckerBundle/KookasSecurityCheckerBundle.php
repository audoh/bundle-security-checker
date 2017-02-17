<?php

/**
 * This file is part of kookas/security-checker.
 *
 * (c) Ashleigh Udoh <mail@audoh.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kookas\SecurityCheckerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use Kookas\SecurityCheckerBundle\DependencyInjection\KookasSecurityCheckerExtension;

/**
 * KookasSecurityCheckerBundle
 *
 * @package    kookas/bundle/security-checker
 * @author     Ashleigh Udoh <mail@audoh.co.uk>
 * @copyright  2017 Ashleigh Udoh
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class KookasSecurityCheckerBundle extends Bundle
{
	public function getContainerExtension()
    {
        return new KookasSecurityCheckerExtension();
    }
}