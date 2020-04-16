<?php
/**
 * Created by PhpStorm.
 * User: mihailnilov
 * Date: 15.04.2020
 * Time: 18:12
 */

namespace Service;


class Connect
{

    public $pathToTmpDir;
    public $tmpNameFile;

    public function __construct($pathToTmpDir, $tmpNameFile)
    {
        $this->pathToTmpDir = $pathToTmpDir;
        $this->tmpNameFile = $tmpNameFile;
    }

    /**
     * @param $url
     * @throws \Exception
     * Метод загрузки файлов курлом
     */
    public function http($url){
        Service::tmpDir($this->pathToTmpDir);
        $fp = fopen ($this->pathToTmpDir . "/{$this->tmpNameFile}", 'w+');
        $ch = curl_init(str_replace(" ","%20",$url));
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }
}