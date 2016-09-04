<?php

/**
 * Class TxtFilesHandler
 */
class RecursiveTxtFilesHandler
{
    /**
     * The prefix which will be added to a directory's name on printing out
     */
    const DIRECTORY_PREFIX = '[DIR]';

    /**
     * @var string
     *
     * Relative path to the folder of which list of "*.txt" files is supposed to be rendered.
     * For example: "./datafiles"
     */
    private $dir;

    /**
     * TxtFilesHandler constructor.
     * @param $dir
     */
    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    /**
     * Prints out the list of matching "*.txt" files.
     *
     * @param null $filesList
     * @param int $level
     */
    public function renderView($filesList = null, $level = 0)
    {
        if (null === $filesList) {
            $filesList = $this->getFilesList();
        }

        asort($filesList);

        foreach ($filesList as $key => $value) {
            if (is_array($value)) {
                echo '<b>' . str_repeat("-", $level), $key, '</b><br>';
                $this->renderView($value, $level + 1);
            } else {
                echo str_repeat("-", $level), $value, '<br>';
            }
        }
    }

    /**
     * Returns array with file names of the directories and files.
     *
     * @return array
     */
    public function getFilesList()
    {
        $path = realpath($this->dir);
        $directoryIterator = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
        $recIterator = new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::CHILD_FIRST);
        $result = [];
        foreach ($recIterator as $splFileInfo) {
            $name = $splFileInfo->getFilename();
            if ($splFileInfo->isDir()) {
                $path = [$name . self::DIRECTORY_PREFIX => []];
            } else {
                if (!$this->isValidFileName($name)) {
                    continue;
                }
                $path = [$name];
            }

            for ($depth = $recIterator->getDepth() - 1; $depth >= 0; $depth--) {
                $current = $recIterator->getSubIterator($depth)->current();
                $path = [
                    $current->getFilename() . self::DIRECTORY_PREFIX => $path
                ];
            }
            $result = array_merge_recursive($path, $result);
        }

        return $result;
    }

    /**
     * Validates file name to be alphanumeric and to have ".txt" extension
     *
     * @param $fileName
     * @return int
     */
    private function isValidFileName($fileName)
    {
        return preg_match('/^[a-zA-Z0-9]+\.txt$/', $fileName);
    }
}

$txtFilesHandler = new RecursiveTxtFilesHandler('datafiles');
$txtFilesHandler->renderView();