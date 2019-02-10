<?php

namespace MagentoHackathon\Service;

use MagentoHackathon\Model\FileList;
use Symfony\Component\Finder\SplFileInfo;

class GetNamespacesFromFiles
{
    /**
     * @param FileList $fileList
     * @param string $moduleNameSpaceStart
     * @return array
     */
    public function execute(FileList $fileList, $moduleNameSpaceStart = '')
    {
        $filePhpFiles = array_merge($fileList->getPhpFileList(), $fileList->getTemplates());
        $namespacesFromFiles = [];

        /** @var SplFileInfo $file */
        foreach ($filePhpFiles as $file) {
            $pattern = '/([A-z0-9]+' . preg_quote('\\', '/') . '){2}/';

            preg_match_all($pattern, $file->getContents(), $matches);

            foreach ($matches[0] as $match) {
                $removeLeadingSlash = ltrim($match, '\\');
                $splitNamespace = explode('\\', $removeLeadingSlash, 3);

                if ($splitNamespace[0] === $moduleNameSpaceStart) {
                    continue;
                }

                $namespacesFromFiles[] = $splitNamespace[0] . '\\' . $splitNamespace[1] . '\\';
            }
        }

        return array_unique($namespacesFromFiles);
    }
}
