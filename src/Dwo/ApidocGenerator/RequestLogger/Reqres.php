<?php

namespace Dwo\ApidocGenerator\RequestLogger;

use Dwo\RequestLogger\Reqres as BaseReqres;
use Symfony\Component\Routing\Route;

/**
 * Class Reqres
 *
 * @author Dave Www <davewwwo@gmail.com>
 */
class Reqres extends BaseReqres
{
    /**
     * @var Route|null
     */
    public $route;

    /**
     * @return Route|null
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param Route|null $route
     */
    public function setRoute(Route $route = null)
    {
        $this->route = $route;
    }
}