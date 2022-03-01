<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 01/03/22
 * Time: 07:33 م
 */

namespace App\Component\Security;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class AclDiscoveryRegistry
{

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $directory;

    /**
     * @var Reader
     */
    private $annotationReader;
    /**
     * @var KernelInterface $kernel
     */
    private $kernel;

    /**
     * The Kernel root directory
     * @var string
     */
    private $rootDir;

    /**
     * @var array
     */
    private $acl = [];


    /**
     * AclDiscoveryRegistry constructor.
     * @param Reader $annotationReader
     * @param KernelInterface $kernel
     */
    public function __construct(Reader $annotationReader, KernelInterface $kernel)
    {

        $this->annotationReader = $annotationReader;
        $this->kernel = $kernel;

    }

    /**
     * @param string $namespace
     */
    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
        $this->directory = str_replace(
            ['App\\', '\\'],
            ['src/', '/'],
            $this->namespace
        );
        $this->rootDir = $this->kernel->getProjectDir();
    }


    /**
     * Returns all the acls
     */
    public function getACL()
    {
        if (!$this->acl) {
            $this->discoverACL();
        }

        return $this->acl;
    }

    /**
     * Discovers acls
     */
    private function discoverACL()
    {
        $path = $this->rootDir . '/' . $this->directory;
        $finder = new Finder();
        $finder->files()->in($path);

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $class = str_replace(
                ['.php', $this->rootDir . '/src/', '/'],
                ['', 'App/', '\\'],
                $file->getPathname()
            );
            $methods = (new \ReflectionClass($class))
                ->getMethods(\ReflectionMethod::IS_PUBLIC);
            foreach ($methods as $method) {
                $annotation = $this->annotationReader->getMethodAnnotation($method, ACL::class);
                $annotationRoute = $this->annotationReader->getMethodAnnotation($method, Route::class);
                if (!$annotation || !$annotationRoute) {
                    continue;
                }

                /** @var Acl $annotation */
                $this->acl[] = [
                    'method' => sprintf('%s::%s', self::uglify($class), $method->getName()),
                    'route' => $annotationRoute->getName(),
                    'path' => $annotationRoute->getPath()
                ];
            }
        }
    }

    private static function resolve($fqcn)
    {
        $parts = explode('\\', $fqcn);
        $length = count($parts);
        $index = $length > 0 ? $length - 1 : $length;
        return $parts[$index];
    }

    private static function uglify($fqcn)
    {
        return str_replace('\\', '.', $fqcn);
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}
