<?php
/**
 * Created by PhpStorm.
 * User: seb
 * Date: 20.09.2016
 * Time: 15:24
 */


/**
 * @param $serial
 * @param bool $createnew
 * @return mixed
 */
function loadconfig($serial, $createnew = false)
{
    $config_file = "configs/{$serial}.json";
    if (!file_exists($config_file)) {
        if ($createnew) {
            file_put_contents($config_file, "{}");
        } else {
            return null;
        }
    }
    $json_config = strtolower(file_get_contents($config_file));
    $config = json_decode($json_config);
    return $config;
}


/**
 * @param $serial
 * @param $config
v */
function saveconfig($serial, $config)
{
    $config_file = "configs/{$serial}.json";
    $json_encode = json_encode($config, JSON_PRETTY_PRINT);
    file_put_contents($config_file, $json_encode);
}


function getAllConfigs()
{
    $res = array();
    $scandir = scandir('configs');
    foreach ($scandir as $name) {
        if (substr($name, -5) != '.json') {
            continue;
        }
        $serial = substr($name, 0, strlen($name) - 5);
        $json_config = file_get_contents("configs/$name");
        $config = json_decode($json_config);
        if ($config != null) {
            $res[$serial] = $config;
        }
    }
    return $res;
}
