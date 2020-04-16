<?php
/**
 * Created by PhpStorm.
 * User: mihailnilov
 * Date: 15.04.2020
 * Time: 18:11
 */

namespace Service;


class Service
{

    /**
     * @return bool|mixed|string
     * @throws \Exception
     * Метод подключения конфига
     */
    static public function getConfig()
    {
        $pathToConfig = dirname(__DIR__, 1) . '/config.json';
        $config = @file_get_contents($pathToConfig);

        if (!$config) {
            throw new \Exception("{$pathToConfig} not found");
        } else {
            $config = json_decode($config);

            if (is_null($config)) {
                throw new \Exception("{$pathToConfig} not format json");
            } else {

                if (is_array($config->mime)) {
                    return $config;
                } else {
                    throw new \Exception("{$pathToConfig} not have array mime");
                }
            }
        }
    }

    /**
     * @param $pathToDir
     * @return bool
     * @throws \Exception
     * Метод создания папки для файлов
     */
    static public function tmpDir($pathToDir)
    {
        if (is_dir($pathToDir)) {
            return true;
        } else {
            if (@mkdir($pathToDir, 0755)) {
                return true;
            } else {
                throw new \Exception("{$pathToDir} don't accept create dir");
            }
        }
    }
}