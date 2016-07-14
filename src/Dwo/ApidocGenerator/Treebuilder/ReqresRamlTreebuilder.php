<?php

namespace Dwo\ApidocGenerator\Treebuilder;

use Dwo\ApidocGenerator\RequestLogger\Reqres;
use Dwo\ApiDocTreebuilder\Nodes\Raml\Root;
use Dwo\ApidocTreebuilder\Treebuilder\RamlTreebuilder;

/**
 * Class ReqresTreeBuilder
 *
 * @author Dave Www <davewwwo@gmail.com>
 */
class ReqresRamlTreebuilder extends RamlTreebuilder implements ReqresTreebuilderInterface
{
    /**
     * @param Reqres[] $requests
     *
     * @return Root
     */
    public function addRequests(array $requests)
    {
        $root = $this->getRoot();

        foreach ($requests as $reqres) {
            if (null !== $route = $reqres->getRoute()) {
                $routePath = $route->getPath();
            } else {
                $routePath = $reqres->getPath();
            }

            $method = $this->nodeFinder->getMethod($routePath, $reqres->getMethod(), $root);

            $this->add($reqres->request, $reqres->response, $method);
        }

        return $root;
    }
}