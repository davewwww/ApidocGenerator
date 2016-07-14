<?php

namespace Dwo\ApidocGenerator\Treebuilder;

use Dwo\ApidocTreebuilder\Nodes\NodeInterface;
use Dwo\ApidocTreebuilder\Treebuilder\TreebuilderInterface;
use Dwo\RequestLogger\Reqres;

/**
 * Interface ReqresTreebuilderInterface
 *
 * @author Dave Www <davewwwo@gmail.com>
 */
interface ReqresTreebuilderInterface extends TreebuilderInterface
{
    /**
     * @param Reqres[] $requests
     *
     * @return NodeInterface
     */
    public function addRequests(array $requests);
}