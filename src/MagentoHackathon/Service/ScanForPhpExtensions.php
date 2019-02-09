<?php

namespace MagentoHackathon\Service;

use MagentoHackathon\Api\TokenizerInterface;
use MagentoHackathon\StringTokenizer;

class ScanForPhpExtensions
{
    const EXT = 'ext-';

    /**
     * @var array|null
     */
    private $phpCheckExtensions;

    /**
     * @var arrayﬂ
     */
    private $registeredExtensions;
    /**
     * @var StringTokenizer
     */
    private $tokenizer;

    /**
     * ScanForPhpExtensions constructor.
     * @param TokenizerInterface $tokenizer
     * @param array $registeredPhpExtensions
     * @param null $phpCheckExtensions
     */
    public function __construct(
        TokenizerInterface $tokenizer,
        $registeredExtensions = [],
        $phpCheckExtensions = null
    ) {
        $this->tokenizer = $tokenizer;
        $this->registeredExtensions = $registeredExtensions;

        if ($phpCheckExtensions === null) {
            $phpCheckExtensions = ['json', 'xml', 'pcre', 'gd', 'bcmath'];
        }

        $this->phpCheckExtensions = $phpCheckExtensions;
    }

    /**
     * @param array $classesPathList
     * @return array
     */
    public function execute(array $classesPathList): array
    {
        $results = [];
        $tokens = $this->getTokensByClassesPathList($classesPathList);
        $registeredPhpExtensions = $this->getRegisteredPhpExtensions();

        foreach ($this->phpCheckExtensions as $phpExtension) {
            if (array_key_exists($phpExtension, $registeredPhpExtensions)) {
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

    /**
     * @return array
     */
    private function getRegisteredPhpExtensions(): array
    {
        $registeredPhpExtensions = [];

        foreach ($this->registeredExtensions as $registeredExtension => $value) {
            if (strpos(substr($registeredExtension, 0, 4), self::EXT) === false) {
                continue;
            }

            $registeredExtension = str_replace(self::EXT, '', $registeredExtension);
            $registeredPhpExtensions[$registeredExtension] = '';
        }

        return $registeredPhpExtensions;
    }
}
