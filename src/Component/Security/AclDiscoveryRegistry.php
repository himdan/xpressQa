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
     * @var RoleProviderInterface $roleProvider
     */
    private $roleProvider;

    /**
     * The Kernel root directory
     * @var string
     */
    private $rootDir;

    /**
     * @var array
     */
    private $acl = [];
    private $permissions = [];
    private $roles = [];
    /**
     * @var PermissionMapManager
     */
    private $permissionMapManager;


    /**
     * AclDiscoveryRegistry constructor.
     * @param Reader $annotationReader
     * @param KernelInterface $kernel
     * @param PermissionMapManager $permissionMapManager
     */
    public function __construct(
        Reader $annotationReader,
        KernelInterface $kernel,
        PermissionMapManager $permissionMapManager
    )
    {

        $this->annotationReader = $annotationReader;
        $this->kernel = $kernel;
        $this->permissionMapManager = $permissionMapManager;

    }

    /**
     * @param RoleProviderInterface $roleProvider
     */
    public function setRoleProvider(RoleProviderInterface $roleProvider): void
    {
        $this->roles = $roleProvider->getRoles();
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

        return [
            'matrix'=>$this->acl,
            'applied'=>$this->permissionMapManager->getMapping($this->roles,$this->permissions),
            'permissions'=>$this->permissions,
            'roles'=> $this->roles];
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
                $groups = array_map(function(string $context){
                    return trim(strtoupper($context));
                },$annotation->getContextGroup());
                foreach ($groups as $group){
                    if(!isset($this->acl[$group])){
                        $this->acl[$group] = [];
                    }
                    array_push($this->acl[$group],[
                        'method' => sprintf('%s::%s', self::uglify($class), $method->getName()),
                        'permission' => $annotationRoute->getName(),
                        'path' => $annotationRoute->getPath(),
                    ]);
                    array_push($this->permissions, $annotationRoute->getName());
                    $this->permissions = array_unique($this->permissions);
                }

            }
        }
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
