<?php

namespace MagentoHackathon\Service;

use Magento\Framework\Component\ComponentRegistrar;
use MagentoHackathon\Api\GetModulePathFolderByNameInterface;

/**
 * @codeCoverageIgnore
 */
class GetModuleFolderByName implements GetModulePathFolderByNameInterface
{
    /**
     * @var ComponentRegistrar
     */
    private $componentRegistrar;

    /**
     * @param ComponentRegistrar $componentRegistrar
     */
    public function __construct(ComponentRegistrar $componentRegistrar)
    {
        $this->componentRegistrar = $componentRegistrar;
    }

    /**
     * @param string $moduleName
     * @return null|string
     */
    public function execute($moduleName)
    {
        return $this->componentRegistrar->getPath('module', $moduleName);
    }
}
