<?php
/**
 * Created by PhpStorm.
 * User: mihailnilov
 * Date: 15.04.2020
 * Time: 17:57
 */

require_once 'vendor/autoload.php';

use Search\Search;

$search = new Search(true); //Параметр включения|отключения конфига
$search->localFile('text.csv'); //Метод загрузки файла в класс
$search->http('http://ya.ru'); //Метод загрузки файла по http
$search->searchString('yandex'); //Метод поиска пхождения


