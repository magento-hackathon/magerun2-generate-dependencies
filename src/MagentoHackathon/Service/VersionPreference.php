<?php

namespace MagentoHackathon\Service;

/**
 * @package MagentoHackathon\Service
 */
class VersionPreference
{
    /**
     * @param $filePath
     * @return array
     * @throws \Exception
     */
    public function execute($filePath): array
    {
        if (!file_exists($filePath)) {
            throw new \Exception(sprintf('File %s not found or readable.', $filePath));
        }

        $fileContent = file_get_contents($filePath);
        return (array)json_decode($fileContent, true);
    }
}
