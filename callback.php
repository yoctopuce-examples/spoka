<?php
include('common.php');
include('Sources/yocto_api.php');
include('Sources/yocto_colorledcluster.php');

/**
 * @param YColorLedCluster $ledCluster
 * @param object $currentConfig
 */
function applyPattern($ledCluster, $allconfigs, $currentConfig)
{
    $nbled = $ledCluster->get_activeLedCount();
    $speed = (int)$currentConfig->speed;
    $color1 = hexdec($currentConfig->color1);
    $color2 = hexdec($currentConfig->color2);
    $pattern = $currentConfig->pattern;
    if (($speed == (int)$allconfigs->last_color->speed) &&
        $pattern == $allconfigs->last_color->pattern &&
        $color1 == hexdec($allconfigs->last_color->color1) &&
        $color2 == hexdec($allconfigs->last_color->color2)
    ) {
        // no change
        print("no changes\n");
        return false;
    }


    switch ($pattern) {
        case 'off':
            $ledCluster->set_rgbColor(0, $nbled, 0);
            break;
        case 'static':
            $ledCluster->set_rgbColor(0, $nbled, $color1);
            break;
        case 'fade':
            $ledCluster->resetBlinkSeq(0);
            $ledCluster->addRgbMoveToBlinkSeq(0, $color1, $speed);
            $ledCluster->addRgbMoveToBlinkSeq(0, $color2, $speed);
            $ledCluster->linkLedToBlinkSeq(0, $nbled, 0, 0);
            $ledCluster->startBlinkSeq(0);
            break;
        case 'alternate':
            $part = $nbled / 2;
            $ledCluster->resetBlinkSeq(0);
            $ledCluster->addRgbMoveToBlinkSeq(0, $color1, $speed);
            $ledCluster->addRgbMoveToBlinkSeq(0, $color2, $speed);
            $ledCluster->linkLedToBlinkSeq(0, $part, 0, 0);
            $ledCluster->linkLedToBlinkSeq($part, $part, 0, $speed);
            $ledCluster->startBlinkSeq(0);
            break;
        case 'rotate':
            $ledCluster->resetBlinkSeq(0);
            $ledCluster->addRgbMoveToBlinkSeq(0, $color1, 0);
            $ledCluster->addRgbMoveToBlinkSeq(0, $color1, $speed);
            $ledCluster->addRgbMoveToBlinkSeq(0, $color2, 0);
            $ledCluster->addRgbMoveToBlinkSeq(0, $color2, $speed);
            $ledCluster->linkLedToPeriodicBlinkSeq(0, $nbled, 0, 1);
            $ledCluster->startBlinkSeq(0);
            break;
        case'blink':
            $ledCluster->resetBlinkSeq(0);
            $ledCluster->addRgbMoveToBlinkSeq(0, $color1, 0);
            $ledCluster->addRgbMoveToBlinkSeq(0, $color1, $speed);
            $ledCluster->addRgbMoveToBlinkSeq(0, $color2, 0);
            $ledCluster->addRgbMoveToBlinkSeq(0, $color2, $speed);
            $ledCluster->linkLedToBlinkSeq(0, $nbled, 0, $nbled);
            $ledCluster->startBlinkSeq(0);
            break;
    }

    $allconfigs->last_color = $currentConfig;
    return true;
}


YAPI::DisableExceptions();

if (YAPI::TestHub("callback", 10) == YAPI::SUCCESS) {
    YAPI::RegisterHub("callback");
    /** @var YColorLedCluster $ledcluser */
    $ledcluser = YColorLedCluster::FirstColorLedCluster();
    if ($ledcluser == null) {
        print("No Yocto-Color-V2 detected");
        die();
    }
    /** @var YModule $module */
    $module = $ledcluser->get_module();
    $serial = $module->get_serialNumber();
    $name = $module->get_logicalName();
    $config = loadconfig($serial);
    $need_save = false;
    if ($config->name != $name) {
        $config->name = $name;
        $need_save = true;
    }

    $now = strtolower(date('H\hi'));
    $wakeup = $config->clock->wakeup;
    $day = $config->clock->day;
    $night = $config->clock->night;
    print("$now -> $wakeup / $day / $night\n");
    if (strcmp($now, $wakeup) < 0 || strcmp($now, $night) >= 0) {
        // night
        $need_save |= applyPattern($ledcluser, $config, $config->night_color);
    } else if (strcmp($now, $day) < 0) {
        // wake up
        $need_save |= applyPattern($ledcluser, $config, $config->wakeup_color);
    } else {
        // day
        $need_save |= applyPattern($ledcluser, $config, $config->day_color);
    }
    if ($need_save) {
        saveconfig($serial, $config);
    }
    die();
}
