<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 04/03/22
 * Time: 10:48 ص
 */

namespace App\Component\Menu;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

abstract class MenuBuilder extends AbstractExtension
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    private $menuConfig = [];
    /**
     * @var RequestStack $requestStack
     */
    private $requestStack;
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        ParameterBagInterface $parameterBag,
        RequestStack $requestStack
    )
    {
        $this->urlGenerator = $urlGenerator;
        $this->menuConfig = $parameterBag->get('menu');
        $this->requestStack = $requestStack;
    }


    public function getFunctions()
    {
        return [
            new TwigFunction('generate_menu', [$this, 'build'])
        ];
    }

    public function build()
    {
        $menu = [];
        $this->buildMenu($this->menuConfig, $menu);
        return $menu;
    }

    private function buildMenu($menuItems = [], &$currentLevel = []){
        foreach ($menuItems as $menuItem){
            if($this->isEnabled($menuItem)){
                $nextLevel = [];
                if(isset($menuItem['children']) && is_array($menuItem['children'])){
                    $this->buildMenu($menuItem['children'], $nextLevel);
                }
                array_push($currentLevel, $this->generateMenuItem(
                    $menuItem['name'],
                    isset($menuItem['route'])?['route' => $menuItem['route']]:'#',
                    count($nextLevel)?$nextLevel:false,
                    isset($menuItem['decoration'])?$menuItem['decoration']:false
                ));
            }

        }
    }

    private function generateMenuItem($name, $routesParam = null, $children = null, $decoration = false)
    {
        $item = new \stdClass();
        $item->href = is_array($routesParam)?$this->generateUrl($routesParam['route'], [], UrlGenerator::ABSOLUTE_URL):$routesParam;
        $active = isset($routesParam)?(isset($routesParam['route'])?$routesParam['route'] === $this->getCurrentRoute(): false):false;
        $item->open = $active? ' active':' ';
        $item->decoration = $decoration ? $decoration : false;
        $item->name = $name;
        $item->children = $children;
        return $item;
    }

    private function generateUrl($name, $parameters = [], $type = UrlGenerator::ABSOLUTE_PATH)
    {
        return $this->urlGenerator->generate($name, $parameters, $type);
    }

    private function getCurrentRoute(){
        return $this->requestStack->getCurrentRequest()->attributes->get('_route');
    }

    /**
     * @param $menuItem
     * @return bool
     */
    protected  function  isEnabled($menuItem){
        return true;
    }
}
