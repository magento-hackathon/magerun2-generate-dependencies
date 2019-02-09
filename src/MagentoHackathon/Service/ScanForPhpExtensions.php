<?php

namespace MagentoHackathon\Service;

use MagentoHackathon\StringTokenizer;

class ScanForPhpExtensions
{

    /**
     * @var array|null
     */
    private $phpCheckExtensions = ['json', 'xml', 'pcre', 'gd', 'bcmath'];

    /**
     * @var arrayﬂ
     */
    private $registeredPhpExtensions = [];
    /**
     * @var StringTokenizer
     */
    private $tokenizer;

    /**
     * ScanForPhpExtensions constructor.
     * @param StringTokenizer $tokenizer
     * @param array $registeredPhpExtensions
     * @param null $phpCheckExtensions
     */
    public function __construct(
        StringTokenizer $tokenizer,
        $registeredPhpExtensions = [],
        $phpCheckExtensions = null
    ) {
        $this->tokenizer = $tokenizer;
        $this->registeredPhpExtensions = $registeredPhpExtensions;

        if ($phpCheckExtensions !== null) {
            $this->phpExtensions = $phpCheckExtensions;
        }
    }

    /**
     * @param array $classesPathList
     * @return array
     */
    public function execute(array $classesPathList): array
    {
        $results = [];
        $tokens = $this->getTokensByClassesPathList($classesPathList);

        foreach ($this->phpCheckExtensions as $phpExtension) {
            if (array_key_exists($phpExtension, $this->registeredPhpExtensions)) {
                continue;
            }

            foreach (get_extension_funcs($phpExtension) as $phpExtensionFunction) {
                if (!in_array($phpExtensionFunction, $tokens, false)) {
                    continue;
                }

                $results[] = sprintf(
                    'Function "%s" requires PHP extension "ext-%s"',
                    $phpExtensionFunction,
                    $phpExtension
                );
            }
        }

        return $results;
    }

    /**
     * @param array $classesPathList
     * @return array
     */
    private function getTokensByClassesPathList(array $classesPathList): array
    {
        $stringTokens = [[]];

        foreach ($classesPathList as $classPath) {
            $stringTokens[] = $this->tokenizer->execute($classPath);
        }

        return array_unique(array_merge(...$stringTokens));
    }
}
