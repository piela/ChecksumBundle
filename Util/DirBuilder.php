<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flexix\ChecksumBundle\Util;

/**
 * Generates a CRUD controller.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class DirBuilder
{
    
    protected $projectDir;
    protected $rootDir;
    
    public function __construct($rootDir)
    {

        $this->rootDir = $rootDir;
      
    }

    public function getRootDir()
    {
        return $this->changeSlashes($this->rootDir);
    }

    public function getProjectDir()
    {
        if (!$this->projectDir) {
            $kernelRootDirArray = explode('/', $this->getRootDir());
            array_pop($kernelRootDirArray);
            $this->projectDir = implode('/', $kernelRootDirArray);
        }
        return $this->projectDir;
    }

    public function getTempDir()
    {
        return $this->changeSlashes($this->getRootDir()) . '/tmp';
    }

    public function getTempFilePath($filePath)
    {
        $filePath=$this->changeSlashes($filePath);
        return str_replace($this->getProjectDir(), $this->getTempDir(), $filePath);
    }

    public function getTempFolderPath($filePath)
    {
        
        
        $filePathArray = explode('/', $this->getTempFilePath($filePath));
        array_pop($filePathArray);
        return implode('/', $filePathArray);
    }

   
    
    protected function changeSlashes($filePath)
    {
        return str_replace('\\', '/', $filePath);
    }

}
