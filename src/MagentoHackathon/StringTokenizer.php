<?php

namespace MagentoHackathon;

use MagentoHackathon\Api\TokenizerInterface;

/**
 * Service Class for get all string tokens as array.
 */
class StringTokenizer implements TokenizerInterface
{
    /**
     * @param string $filename
     * @return string[]
     */
    public function execute(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return [];
        }

        $content = file_get_contents($filePath);
        $tokens = token_get_all($content);

        $stringTokens = [];
        foreach ($tokens as $token) {
            if (is_array($token) && $token[0] === T_STRING) {
                $stringTokens[] = $token[1];
            }
        }
        return $stringTokens;
    }
}
