<?php

namespace Dwo\ApidocGenerator\Route;

use Dwo\ApidocGenerator\RequestLogger\Reqres;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Router;

/**
 * Class RouteFinder
 *
 * @author Dave Www <davewwwo@gmail.com>
 */
class RouteFinder
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param Reqres[] $requestEntries
     */
    public function addRoutes(array &$requestEntries)
    {
        foreach ($requestEntries as $entry) {
            $entry->setRoute($this->findRoutePath($entry->getMethod(), $entry->request->getRequestUri()));
        }
    }

    /**
     * @param string $method
     * @param string $uri
     *
     * @return Route|null
     */
    public function findRoutePath($method, $uri)
    {
        $this->router->getContext()->setMethod($method);

        try {
            $parse = parse_url($uri);

            $routeName = $this->router->match($parse['path'])["_route"];

            return $this->router->getRouteCollection()->get($routeName);

            #$routePath = $routeInfo->getPath();
            #$routePath = str_replace('.{_format}', '', $routePath);

        } catch (\Exception $e) {
            $routePath = null;
        }

        return $routePath;
    }
}