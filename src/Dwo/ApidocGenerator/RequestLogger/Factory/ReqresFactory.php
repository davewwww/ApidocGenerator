<?php

namespace Dwo\ApidocGenerator\RequestLogger\Factory;

use Dwo\ApidocGenerator\RequestLogger\Reqres;
use Dwo\RequestLogger\Factory\ReqresFactory as BaseReqresFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ReqresFactory
 *
 * @author Dave Www <davewwwo@gmail.com>
 */
class ReqresFactory extends BaseReqresFactory
{
    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Reqres
     */
    public static function create(Request $request, Response $response)
    {
        return new Reqres($request, $response);
    }
}