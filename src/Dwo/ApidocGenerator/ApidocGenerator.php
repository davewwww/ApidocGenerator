<?php

namespace Dwo\ApidocGenerator;

use Dwo\ApidocGenerator\Route\RouteFinder;
use Dwo\ApidocGenerator\Treebuilder\ReqresTreebuilderInterface;
use Dwo\ApidocTreebuilder\Dumper\NodeDumper;
use Dwo\ApidocTreebuilder\Nodes\NodeInterface;
use Dwo\RequestLogger\Storage\StorageInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ApidocGenerator
 *
 * @author Dave Www <davewwwo@gmail.com>
 */
class ApidocGenerator
{
    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @var RouteFinder
     */
    protected $routeFinder;

    /**
     * @var ReqresTreebuilderInterface[]
     */
    protected $treebuilders;

    /**
     * Generator constructor.
     *
     * @param StorageInterface             $storage
     * @param RouteFinder                  $routeFinder
     * @param ReqresTreebuilderInterface[] $treebuilders
     */
    public function __construct(StorageInterface $storage, RouteFinder $routeFinder, array $treebuilders)
    {
        $this->storage = $storage;
        $this->routeFinder = $routeFinder;
        $this->treebuilders = $treebuilders;
    }

    /**
     * @param string $type
     *
     * @return NodeInterface
     * @throws \Exception
     */
    public function generate($type)
    {
        $requestEntries = $this->storage->getEntries();

        $this->routeFinder->addRoutes($requestEntries);

        if (!isset($this->treebuilders[$type])) {
            throw new \Exception('unknown treebuilder: '.$type);
        }

        return $this->treebuilders[$type]->addRequests($requestEntries);
    }

    /**
     * @param NodeInterface $tree
     * @param string        $type
     *
     * @return array|string
     * @throws \Exception
     */
    public function dump(NodeInterface $tree, $type = 'array')
    {
        $dump = NodeDumper::dump($tree);

        switch ($type) {
            case 'json':
                return json_encode($dump);

            case 'yml':
                return Yaml::dump($dump, 10, 2);

            case 'array':
                return $dump;

            default:
                throw new \Exception('unknown dump type: '.$type);
        }
    }
}