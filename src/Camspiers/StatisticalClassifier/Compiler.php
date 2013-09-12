<?php

/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Camspiers\StatisticalClassifier;

use Camspiers\StatisticalClassifier\Console\Command\GenerateContainerCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

/**
 * @author  Cam Spiers <camspiers@gmail.com>
 * @package Statistical Classifier
 */
class Compiler
{
    /**
     * @var
     */
    protected $version;
    /**
     * Compiles classifier into a single phar file
     *
     *
     * @param  string $pharFile The full path to the file to create
     * @param string  $interpreter
     * @throws \RuntimeException
     */
    public function compile($pharFile = 'classifier.phar', $interpreter = 'php')
    {
        if (file_exists($pharFile)) {
            unlink($pharFile);
        }

        $command = new GenerateContainerCommand();
        $command->run(
            new ArrayInput(array()),
            new NullOutput()
        );

        $process = new Process('git log --pretty="%H" -n1 HEAD', __DIR__);
        if ($process->run() != 0) {
            throw new \RuntimeException(
                "Can't run git log. You must ensure to run compile from composer "
                . "git repository clone and that git binary is available."
            );
        }
        $this->version = trim($process->getOutput());

        $process = new Process('git describe --tags HEAD');
        if ($process->run() == 0) {
            $this->version = trim($process->getOutput());
        }

        $phar = new \Phar($pharFile, 0, 'classifier.phar');
        $phar->setSignatureAlgorithm(\Phar::SHA1);

        $phar->startBuffering();

        $finder = new Finder();
        $finder->files()
            ->ignoreVCS(true)
            ->name('*.php')
            ->notName('Compiler.php')
            ->in(__DIR__ . '/../../');

        foreach ($finder as $file) {
            $this->addFile($phar, $file);
        }

        $finder = new Finder();
        $finder->files()
            ->ignoreVCS(true)
            ->name('*.json')
            ->name('*.yml')
            ->in(__DIR__ . '/../../../config');

        foreach ($finder as $file) {
            $this->addFile($phar, $file);
        }

        $vendorDir = realpath(__DIR__ . '/../../../vendor');

        $finder = new Finder();
        $finder->files()
            ->ignoreVCS(true)
            ->name('*.php')
            ->exclude('Tests')
            ->in("$vendorDir/camspiers/json-pretty/src/")
            ->in("$vendorDir/camspiers/porter-stemmer/src/")
            ->in("$vendorDir/evenement/evenement/src/")
            ->in("$vendorDir/guzzle/")
            ->in("$vendorDir/maximebf/cachecache/src/")
            ->in("$vendorDir/pimple/pimple/lib/")
            ->in("$vendorDir/react/")
            ->in("$vendorDir/symfony/");

        foreach ($finder as $file) {
            $this->addFile($phar, $file);
        }

        $this->addFile($phar, new \SplFileInfo("$vendorDir/autoload.php"));
        $this->addFile($phar, new \SplFileInfo("$vendorDir/composer/autoload_namespaces.php"));
        $this->addFile($phar, new \SplFileInfo("$vendorDir/composer/autoload_classmap.php"));
        $this->addFile($phar, new \SplFileInfo("$vendorDir/composer/autoload_real.php"));
        if (file_exists("$vendorDir/composer/include_paths.php")) {
            $this->addFile($phar, new \SplFileInfo("$vendorDir/composer/include_paths.php"));
        }
        $this->addFile($phar, new \SplFileInfo("$vendorDir/composer/ClassLoader.php"));
        $this->addClassifierBin($phar);

        // Stubs
        $phar->setStub($this->getStub($interpreter));

        $phar->stopBuffering();

        $this->addFile($phar, new \SplFileInfo(__DIR__.'/../../../LICENSE'), false);
        
        $phar->setStub($this->getStub($interpreter));

        unset($phar);

        file_put_contents($pharFile . '.version', trim($this->version));
    }

    private function addFile($phar, $file)
    {
        $path = str_replace(dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR, '', $file->getRealPath());

        $content = php_strip_whitespace($file);
        if (basename($path) !== 'SelfUpdateCommand.php') {
            $content = str_replace('~package_version~', $this->version, $content);
        }
        $content = str_replace(realpath(__DIR__ . '/../../../'), '.', $content);

        $phar->addFromString($path, $content);
        $phar[$path]->compress(\Phar::GZ);
    }

    private function addClassifierBin($phar)
    {
        $content = file_get_contents(__DIR__ . '/../../../bin/classifier');
        $content = preg_replace('{^#!/usr/bin/env php\s*}', '', $content);
        $phar->addFromString('bin/classifier', $content);
    }

    private function getStub($interpreter)
    {
        return <<<EOF
#!/usr/bin/env $interpreter
<?php
/**
 * This file is part of the Statistical Classifier package.
 *
 * (c) Cam Spiers <camspiers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Phar::mapPhar('classifier.phar');

require 'phar://classifier.phar/bin/classifier';

__HALT_COMPILER();
EOF;
    }
}
