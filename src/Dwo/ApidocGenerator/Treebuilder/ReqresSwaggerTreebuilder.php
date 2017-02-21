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
            $tags = [];
            if (null !== $route = $reqres->getRoute()) {
                $routePath = $route->getPath();

                $compilat = RouteCompiler::compile($route);
                $pathVars = $compilat->getPathVariables();

                //tags
                foreach ($compilat->getTokens() as $token) {
                    if ('text' === $token[0]) {
                        $paths = explode('/',substr($token[1], 1));
                        $tags = array_merge($tags,$paths);
                    }
                }
            } else {
                $routePath = $reqres->getPath();
            }

            $path = $this->nodeFinder->findOrCreatePath($routePath, $root);
            $method = $this->nodeFinder->findOrCreateMethod($reqres->getMethod(), $path);

            if (!empty($tags)) {
                $method->tags = array_values(array_unique(array_merge((array) $method->tags, $tags)));
            }

            foreach ($pathVars as $pathVariable) {
                $parameter = $this->nodeFinder->findOrCreateParameter($pathVariable, 'path', $method);
                $parameter->required = true;
            }

            $this->addContentToMethod($reqres->request, $reqres->response, $method);
        }

        $this->optimizeTags();

        return $root;
    }
}