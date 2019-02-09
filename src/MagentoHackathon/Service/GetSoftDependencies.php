<?php


namespace MagentoHackathon\Service;

use Magento\Framework\Component\ComponentRegistrar;
use SimpleXMLElement;

class GetSoftDependencies
{
    /** @var ComponentRegistrar $registrar */
    private $componentRegistrar;

    public function __construct($componentRegistrar)
    {
        $this->componentRegistrar = $componentRegistrar;
    }

    /**
     * @param SimpleXMLElement $softDependenciesXml
     * @return array
     */
    public function execute(SimpleXMLElement $softDependenciesXml)
    {

        $path = $this->componentRegistrar->getPaths(ComponentRegistrar::MODULE);

        $softDependencies = [[]];

        if (isset($softDependenciesXml->module->sequence)) {
            return $softDependencies;
        }

        foreach ($softDependenciesXml->module->sequence->module as $module) {
            $moduleName = (string)$module->attributes()->name;
            if (\array_key_exists($moduleName, $path)) {
                $composerFilePath = preg_replace('/\/src$/', '', $path[$moduleName]);
            }
        }

        return array_merge(...$softDependencies);
    }
}
