<?php

namespace MagentoHackathon\Command;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use MagentoHackathon\Api\GetModulePathFolderByNameInterface;
use MagentoHackathon\FileCollector;
use MagentoHackathon\Service\CheckDependency as CheckDependencyService;
use MagentoHackathon\Service\GetModuleFolderByName;
use N98\Magento\Command\AbstractMagentoCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @codeCoverageIgnore
 */
class CheckDependency extends AbstractMagentoCommand
{
    const COMMAND_NAME = 'generate:dependencies';

    const OPTION_EXT_DIR = 'extension-dir';

    const OPTION_EXT_DIR_SHORT = 'x';

    const OPTION_EXT_DIR_DECS = 'Folder name of the extension (e.g. module-catalog)';

    const OPTION_EXT_PREFERENCE = 'local-magento-dependencies';

    const OPTION_EXT_PREFERENCE_SHORT = 'l';

    const OPTION_EXT_PREFERENCE_DESC = "All extension dependencies will be generated against the local magento version. \n" .
    "\t e.g.  \"magento/module-customer\": \"^101.0.0\" \n" .
    "(If not set the version will be generated against magento 2.2 and 2.3 \n" .
    "e.g  \"magento/module-customer\": \"^101.0.0|^102.0.0\")";

    /**
     * @var CheckDependencyService
     */
    private $checkDependency;
    /**
     * @var GetModulePathFolderByNameInterface|GetModuleFolderByName
     */
    private $folderByName;

    /**
     * @var FileCollector
     */
    private $fileCollector;

    /**
     * @param CheckDependencyService $checkDependency
     * @param GetModuleFolderByName $folderByName
     */
    public function inject(
        CheckDependencyService $checkDependency,
        GetModuleFolderByName $folderByName,
        FileCollector $fileCollector
    ) {
        $this->checkDependency = $checkDependency;
        $this->folderByName = $folderByName;
        $this->fileCollector = $fileCollector;
    }

    protected function configure()
    {
        $this->setName('extension:generate:dependencies')
            ->addOption(
                self::OPTION_EXT_DIR,
                self::OPTION_EXT_DIR_SHORT,
                InputOption::VALUE_REQUIRED,
                self::OPTION_EXT_DIR_DECS
            )
            ->addOption(
                self::OPTION_EXT_PREFERENCE,
                self::OPTION_EXT_PREFERENCE_SHORT,
                InputOption::VALUE_NONE,
                self::OPTION_EXT_PREFERENCE_DESC
            )
            ->setDescription('This command will search for any soft and hard dependencies for the given extension and will generate a list of recommended composer dependencies.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $this->detectMagento($output);
        if ($this->initMagento($output)) {
            $moduleName = $this->getExtensionFolderName($input, $output);
            if ($moduleName === false) {
                return;
            }

            $modulePath = $this->folderByName->execute($moduleName);
            if ($modulePath === null) {
                $output->writeln('<info>Please provide a valid module  name. </info>');
                return;
            }

            $relevantFiles = $this->fileCollector->getRelevantFiles($modulePath);
            $dependencyList = $this->checkDependency->execute($relevantFiles);
        }
        return $output;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool|string
     */
    private function getExtensionFolderName(
        InputInterface $input,
        OutputInterface $output
    ) {
        $extensionName = $input->getOption(self::OPTION_EXT_DIR);

        if (empty($extensionName)) {
            $output->writeln('<info>Please provide a folder name. </info>');
            $output->writeln('<comment>n98-magerun2 ' . self::COMMAND_NAME . ' (-' . self::OPTION_EXT_DIR . '|-' . self::OPTION_EXT_DIR_SHORT . ') FOLDER_NAME</comment>');
            return false;
        }

        $output->writeln('<info>Find extension by folder name : </info><comment>' . $extensionName . '</comment></info>');

        return $extensionName;
    }
}
