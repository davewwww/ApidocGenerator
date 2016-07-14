<?php

namespace Dwo\ApidocGenerator\Treebuilder;

use Dwo\ApidocGenerator\RequestLogger\Reqres;
use Dwo\ApidocTreebuilder\Nodes\Swagger\Root;
use Dwo\ApidocTreebuilder\Treebuilder\SwaggerTreebuilder;
use Symfony\Component\Routing\RouteCompiler;

/**
 * Class ReqresSwaggerTreebuilder
 *
 * @author Dave Www <davewwwo@gmail.com>
 */
class ReqresSwaggerTreebuilder extends SwaggerTreebuilder implements ReqresTreebuilderInterface
{
    /**
     * @param Reqres[] $requests
     *
     * @return Root
     */
    public function addRequests(array $requests)
    {
        $root = $this->getRoot();

        $pathVars = [];
        foreach ($requests as $reqres) {
            if (null !== $route = $reqres->getRoute()) {
                $routePath = $route->getPath();

                $compilat = RouteCompiler::compile($route);
                $pathVars = $compilat->getPathVariables();
            } else {
                $routePath = $reqres->getPath();
            }

            $path = $this->nodeFinder->findOrCreatePath($routePath, $root);
            $method = $this->nodeFinder->findOrCreateMethod($reqres->getMethod(), $path);

            foreach ($pathVars as $pathVariable) {
                $parameter = $this->nodeFinder->findOrCreateParameter($pathVariable, 'path', $method);
                $parameter->required = true;
            }

            $this->addContentToMethod($reqres->request, $reqres->response, $method);
        }

        return $root;
    }
}