<?php

declare(strict_types=1);

namespace Tbessenreither\FeatureFlagClientBundle\Bundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tbessenreither\FeatureFlagClient\Bundle\DependencyInjection\Compiler\FeatureFlagClientCompilerPass;


class FeatureFlagClientBundle extends Bundle
{

	public function build(ContainerBuilder $container): void
	{
		parent::build($container);
		$container->addCompilerPass(new FeatureFlagClientCompilerPass());
	}

}
