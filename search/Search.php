<?php
/**
 * Created by PhpStorm.
 * User: mihailnilov
 * Date: 15.04.2020
 * Time: 18:07
 */

namespace Search;

use Service\Connect;
use Service\Service;

class Search
{

    public $config = false;
    private $connect;
    private $pathToFolder;
    private $tmpNameFile;
    private $error = 'not select file';
    private $pathToFile;

    /**
     * Search constructor.
     * @param bool $config
     * @throws \Exception
     * Параметр включения конфигурации
     */
    public function __construct(bool $config)
    {
        if($config)$this->config = Service::getConfig();
        $this->pathToFolder = dirname(__DIR__, 1).'/tmp';
        $this->tmpNameFile = time().'.tmp';
        $this->connect = new Connect($this->pathToFolder, $this->tmpNameFile);
    }

    /**
     * @param string $pathToFile
     * @return array|bool
     * Метод получения локального файла
     */
    public function localFile(string $pathToFile){
        $accept = $this->accept($pathToFile);

        if(!$accept['error']){
            $this->pathToFile = $pathToFile;
            return true;
        }else{
            return $accept;
        }
    }

    /**
     * @param string $url
     * @return array|bool
     * @throws \Exception
     * Метод загрузки файла по http
     */
    public function http(string $url){
        $this->connect->http($url);
        $file = "{$this->pathToFolder}/{$this->tmpNameFile}";
        $result = $this->localFile($file);
        return $result;
    }

    /**
     * @param string $string
     * @return array|string
     * Метод поиска вхождения
     */
    public function searchString(string $string){
        if($this->error) return $this->error;
        $arr = explode("\n", file_get_contents($this->pathToFile));
        $result = [];

        foreach ($arr AS $i => $item){
            $item = trim($item);
            $pos = mb_stripos($item, $string);

            if($pos){
                $count = 0;

                for(true; !empty($item) && $pos !== false; true){
                    $count += $pos;
                    $result[] = ['string' => $i+1, 'char' => $count];
                    $delChar = stripos($item, $string);
                    $delChar = (ord(substr($item, stripos($item, $string)))>127) ? $delChar + 2 : $delChar + 1;
                    ++$count;
                    $item = substr($item, $delChar);
                    $pos = mb_stripos($item, $string);
                }
            }
        }
        return $result;
    }

    /**
     * @param string $pathToFile
     * @return array
     * Метод проверки на валидацию
     */
    private function accept(string $pathToFile){
            $this->error = false;
            $result = ['error' => false];

            if(!is_file($pathToFile)){
                $this->error = "{$pathToFile} not file";
                return ['error' => $this->error];
            }

            if(!$this->config) return $result;
            $mimeAccept = false;
            $size = filesize($pathToFile);

            if($size > $this->config->size){
                $result['error'] = true;
                $result['message'][] = "Размер файла {$size}B, допустимый размер {$this->config->size}B";
            }

            $mime = mime_content_type($pathToFile);

            foreach ($this->config->mime AS $item){
                if($item === $mime) $mimeAccept = true;
            }

            if(!$mimeAccept){
                $result['error'] = true;
                $result['message'][] = "Mime type \"{$mime}\" не допустимый";
            }

            if($result['error']) $this->error = $result;
            return $result;
    }
}