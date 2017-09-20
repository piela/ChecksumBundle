<?php

namespace Flexix\ChecksumBundle\Util;

use Symfony\Component\Yaml\Yaml;

class ChecksumController
{

    CONST CHECKSUM_FILE_PATH = '/../Resources/checksum/checksum.yml';

    protected $checksumArray = [];
    protected $loaded = false;
    protected $kernelRootDir;

    public function __construct($kernelRootDir)
    {
        $this->kernelRootDir = $kernelRootDir;
    }

    public function addFile($filePath)
    {
        if(file_exists($filePath)){
        $checksum = sha1_file($filePath);
        $filePath=$this->removeFilePrefix($filePath);
        $this->loadChecksumArray();
        $filePath = $this->changeSlashes($filePath);
        $this->checksumArray[$filePath] = $checksum;
        $this->dumpYml();}
    }

    public function checkChecksum($filePath)
    {
        if(file_exists($filePath)){
        $checksum = sha1_file($filePath);
        $filePath=$this->removeFilePrefix($filePath);
        $this->loadChecksumArray();
        $filePath = $this->changeSlashes($filePath);
        if (array_key_exists($filePath, $this->checksumArray)) {

            if ($this->checksumArray[$filePath] == $checksum) {
                return true;
            } else {
                return false;
            }
        }}

        return true;
    }

    protected function removeFilePrefix($filePath)
    {
        $kernelRootDirArray=explode(DIRECTORY_SEPARATOR,$this->kernelRootDir);
        array_pop($kernelRootDirArray);
        return str_replace(implode(DIRECTORY_SEPARATOR,$kernelRootDirArray), '', $filePath);
    }

    protected function loadChecksumArray()
    {
        if (!$this->loaded) {
            $this->readYml();
            $this->loaded = true;
        }
        return $this->checksumArray;
    }

    protected function changeSlashes($filePath)
    {
        return str_replace('\\', '/', $filePath);
    }

    protected function readYml()
    {
        $this->checksumArray = Yaml::parse(file_get_contents(__DIR__ . self::CHECKSUM_FILE_PATH));
        if (!is_array($this->checksumArray)) {
            $this->checksumArray = [];
        }
    }

    protected function dumpYml()
    {
        $yaml = Yaml::dump($this->checksumArray, 5);
        file_put_contents(__DIR__ . self::CHECKSUM_FILE_PATH, $yaml);
    }

}
