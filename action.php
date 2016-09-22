<?php
/**
 * Created by PhpStorm.
 * User: seb
 * Date: 20.09.2016
 * Time: 15:26
 */

include("common.php");

if (isset($_GET['action'])) {
    $parts = explode('_', $_GET['target']);
    $serial = $parts[0];
    $config = loadconfig($serial);
    if ($config == null) {
        print("Unknown Color-Led : " . $serial);
        die();
    }

    switch ($_GET['action']) {
        case 'clock':
            switch ($parts[1]) {
                case 'night':
                    $config->clock->night = $_GET['value'];
                    break;
                case 'wakeup':
                    $config->clock->wakup = $_GET['value'];
                    break;
                case 'day':
                    $config->clock->day = $_GET['value'];
                    break;
                default:
                    print("Invalid target:" . $parts[1]);
                    die();
            }
            break;
        case 'pattern':
            switch ($parts[1]) {
                case 'night':
                    $config->night_color->pattern = $_GET['value'];
                    break;
                case 'wakeup':
                    $config->wakeup_color->pattern = $_GET['value'];
                    break;
                case 'day':
                    $config->day_color->pattern = $_GET['value'];
                    break;
                default:
                    print("Invalid target:" . $parts[1]);
                    die();
            }
            break;
        case 'color':
            switch ($parts[1]) {
                case 'night':
                    if ($parts[2] == 'color2')
                        $config->night_color->color2 = $_GET['value'];
                    else
                        $config->night_color->color1 = $_GET['value'];
                    break;
                case 'wakeup':
                    if ($parts[2] == 'color2')
                        $config->wakeup_color->color2 = $_GET['value'];
                    else
                        $config->wakeup_color->color1 = $_GET['value'];
                    break;
                case 'day':
                    if ($parts[2] == 'color2')
                        $config->day_color->color2 = $_GET['value'];
                    else
                        $config->day_color->color1 = $_GET['value'];
                    break;
                default:
                    print("Invalid target:" . $parts[1]);
                    die();
            }
            break;
        default:
            print("Unknown action:" . $_GET['action'] . "\n");
            die();
            break;
    }
    saveconfig($serial, $config);
    die();
}