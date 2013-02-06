<?php

namespace Camspiers\StatisticalClassifier;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\Config\FileLocator;
use RuntimeException;

class Factory
{
    public static function createContainer(
        array $parameters = array(),
        array $extensions = array(),
        array $compilerPasses = array(),
        $dump = false,
        $dumpClass = 'StatisticalClassifierServiceContainer',
        $dumpLocation = false
    ) {
        $container = new ContainerBuilder();

        foreach ($extensions as $extension) {
            if ($extension instanceof ExtensionInterface) {
                $container->registerExtension($extension);
                $container->loadFromExtension($extension->getAlias(), $parameters);
            }
        }

        $container->getParameterBag()->add($parameters);

        foreach ($compilerPasses as $compilerPass) {
            if ($compilerPass instanceof CompilerPassInterface) {
                $container->addCompilerPass($compilerPass);
            }
        }

        $container->compile();

        if ($dump) {

        	$dumpLocation = $dumpLocation ?: __DIR__ . '/../../../config/';

        	if (file_exists($dumpLocation)) {

				$dumper = new PhpDumper($container);

				file_put_contents(
					realpath($dumpLocation) . "/$dumpClass.php",
				    $dumper->dump(array('class' => $dumpClass))
				);

        	} else {

        		throw new RuntimeException("'$dumpLocation' doesn't exist");
        	}

        }

        return $container;
    }
}
