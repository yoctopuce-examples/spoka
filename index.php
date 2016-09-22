<!DOCTYPE html>
<html lang="en">
<head>
    <title>Spoka</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="mystyle.css">
    <script>
        function requestAction(str_action, str_target, str_value) {
            //alert(str_action + " for " + str_target + " ->" + str_value);
            var xhttp = new XMLHttpRequest();
            xhttp.open("GET", "action.php?action=" + str_action + "&target=" + str_target + "&value=" + str_value, true);
            xhttp.send();
        }

        function updateClock(input) {
            var name = input.name;
            var basename = name.substr(0, name.length - 2);
            var e = document.getElementById(basename + '_h');
            var hour = e.options[e.selectedIndex].value;
            e = document.getElementById(basename + '_m');
            var minute = e.options[e.selectedIndex].value;
            requestAction("clock", basename, hour + 'h' + minute);
        }

        function updatePattern(input) {
            var name = input.name;
            var pattern = input.value;
            requestAction("pattern", name, pattern);
            var basetarget = name.substr(0, name.length - 8);
            var button1 = document.getElementById(basetarget + '_color1');
            var button2 = document.getElementById(basetarget + '_color2');
            if (pattern == 'off') {
                button1.style.visibility = 'hidden';
                button2.style.visibility = 'hidden';
            } else if (pattern == 'static') {
                button1.style.visibility = 'visible';
                button2.style.visibility = 'hidden';
            } else {
                button1.style.visibility = 'visible';
                button2.style.visibility = 'visible';

            }
        }


        function setcolor(button) {
            var id = button.id;
            var split = id.split('_');
            var serial = split[0];
            var target = split[1];
            var index = split[2];
            var color = button.attributes.hexcolor.value;
            initialcolor = parseInt(color, 16);
            var target_input = document.getElementById("hexColorTarget");
            target_input.value = id;
            preconfigureUI();
        }

        function colorCancel() {
            var pannel = document.getElementById("colorchooser");
            pannel.style.visibility = 'hidden';
        }

        function colorApply() {

            var input = document.getElementById("hexcode");
            var value = input.value;
            var target_input = document.getElementById("hexColorTarget");
            var target = target_input.value;
            requestAction('color', target, value);
            var button = document.getElementById(target);
            button.style.backgroundColor = '#' + value;
            button.attributes.hexcolor.value = value;
            var pannel = document.getElementById("colorchooser");
            pannel.style.visibility = 'hidden';
        }


        var initialcolor = 0xff8000;
        var color2set = null;
        var originalColor = 0;

        var r = 0;
        var g = 0;
        var b = 0;

        var h = 0;
        var s = 0;
        var l = 0;

        var colorSystemisHsl = false;

        function color2HTML(int_color) {
            var c = int_color.toString(16);
            while (c.length < 6) c = '0' + c;
            return '#' + c;
        }

        function refreshled() {
            color2set = null;
            setTimeout(refreshled, 100);
        }

        function hsl2rgbInt(temp1, temp2, temp3) {
            if (temp3 >= 170) return ((temp1 + 127) / 255);
            if (temp3 > 42) {
                if (temp3 <= 127) return ((temp2 + 127) / 255);
                temp3 = 170 - temp3;
            }
            return ((temp1 * 255 + (temp2 - temp1) * (6 * temp3) + 32512) / 65025);
        }


        function hsl2rgb(int_value) {
            var H = (int_value >> 16) & 0xff;
            var S = (int_value >> 8) & 0xff;
            var L = int_value & 0xff;

            var temp2 = 0;
            if (S == 0) return (L << 16) | (L << 8) | L;
            if (L <= 127) temp2 = L * (255 + S);
            else temp2 = (L + S) * 255 - L * S;
            var temp1 = 510 * L - temp2;

            var temp3 = (H + 85);
            if (temp3 > 255) temp3 = temp3 - 255;
            var R = hsl2rgbInt(temp1, temp2, temp3);

            temp3 = H;
            if (temp3 > 255) temp3 = temp3 - 255;
            var G = hsl2rgbInt(temp1, temp2, temp3);

            if (H >= 85) temp3 = H - 85; else temp3 = H + 170;
            var B = hsl2rgbInt(temp1, temp2, temp3);

            if (R > 255) R = 255; // just in case
            if (G > 255) G = 255;
            if (B > 255) B = 255;
            if (R < 0) R = 0;
            if (G < 0) G = 0;
            if (B < 0) B = 0;

            return (R << 16) | (G << 8) | B;
        }


        function rgb2hsl(int_value) {
            var R = (int_value >> 16) & 0xff;
            var G = (int_value >> 8) & 0xff;
            var B = int_value & 0xff;

            var max = (R > G ? R : G);
            var min = (R < G ? R : G);
            var correction;
            var diviseur;
            var res;
            var H = 0;
            var S = 0;
            var L = 0;
            if (B > max) max = B;
            if (B < min) min = B;

            L = ((max + min + 1) / 2);
            if (max == min) {
                return L;
            }
            correction = ((max + min) / 2);

            if (L <= 127)
                S = ((255 * (max - min) + correction) / (max + min));
            else
                S = ((255 * (max - min) + 255 - correction) / (510 - (max + min)));

            correction = 3 * (max - min);
            diviseur = 2 * correction;

            if (R == max) {
                H = 0;
                R = G;
                G = B;
            } else if (G == max) {
                H = 85;
                G = R;
                R = B;
            } else {
                H = 170;
            }
            if (R >= G)
                H += ((255 * (R - G) + correction) / diviseur);
            else
                H += 255 - ((255 * (G - R) - correction) / diviseur);
            if (H > 255) H -= 255;
            if (S > 255) S = 255; // just in case
            if (L > 255) L = 255;
            return (H << 16) | (S << 8) | L;
        }


        function manualChange() {
            var color = parseInt("0x" + document.getElementById('hexcode').value.toUpperCase()) & 0xFFFFFF;
            if (colorSystemisHsl) {
                h = (color >> 16) & 0xFF;
                s = (color >> 8) & 0xFF;
                l = (color ) & 0xFF;
                color = hsl2rgb(color);
            }
            else {
                r = (color >> 16) & 0xFF;
                g = (color >> 8) & 0xFF;
                b = (color ) & 0xFF;
            }
            refreshGauge(1);
            refreshGauge(2);
            refreshGauge(3);

            color2set = color;
            document.getElementById('colorSample').style.backgroundColor = color2HTML(color);
        }

        var mouseisdown = false;

        function doMouseMove(canvas, evt, force) {
            var color;
            if ((mouseisdown) || (force)) {
                var rect = canvas.getBoundingClientRect();
                if (evt.changedTouches) evt = evt.changedTouches[0];
                var x = evt.clientX - rect.left;
                if (x < 0) x = 0;
                if (x > 255) x = 255;
                var index = parseInt(canvas.id.substring(3));

                if (colorSystemisHsl) {
                    switch (index) {
                        case 1 :
                            h = x;
                            break;
                        case 2 :
                            s = x;
                            break;
                        case 3 :
                            l = x;
                            break;
                    }
                    for (var i = 1; i <= 3; i++) refreshGauge(i);
                    color = hsl2rgb((h << 16) | (s << 8) | l);
                } else {
                    switch (index) {
                        case 1 :
                            r = x;
                            break;
                        case 2 :
                            g = x;
                            break;
                        case 3 :
                            b = x;
                            break;
                    }
                    refreshGauge(index);
                    color = (r << 16) | (g << 8) | b;

                }
                document.getElementById('colorSample').style.backgroundColor = color2HTML(color);
                refreshHexCode();
                color2set = color;
            }
        }


        function preconfigureUI() {
            //RegisterWindowAction(WIN_ACTION_OK, save, false, false);
            //RegisterWindowAction(WIN_ACTION_CANCEL, cancel, false, false);
            var pannel = document.getElementById("colorchooser");
            pannel.style.visibility = 'visible';

            window.addEventListener("mousedown", function (evt) {
                if (evt.button == 0) mouseisdown = true;
            }, false);
            window.addEventListener("mouseup", function (evt) {
                if (evt.button == 0) mouseisdown = false;
            }, false);

            var c1 = document.getElementById("Col1");
            c1.addEventListener("mousemove", function (evt) {
                doMouseMove(c1, evt, false);
            }, false);
            c1.addEventListener("click", function (evt) {
                doMouseMove(c1, evt, true);
            }, false);
            c1.addEventListener("touchstart", function (evt) {
                mouseisdown = true;
                doMouseMove(c1, evt, false);
            }, false);
            c1.addEventListener("touchmove", function (evt) {
                mouseisdown = true;
                doMouseMove(c1, evt, false);
            }, false);
            c1.addEventListener("touchend", function (evt) {
                mouseisdown = false;
            }, false);

            var c2 = document.getElementById("Col2");
            c2.addEventListener("mousemove", function (evt) {
                doMouseMove(c2, evt, false);
            }, false);
            c2.addEventListener("click", function (evt) {
                doMouseMove(c2, evt, true);
            }, false);
            c2.addEventListener("touchstart", function (evt) {
                mouseisdown = true;
                doMouseMove(c2, evt, false);
            }, false);
            c2.addEventListener("touchmove", function (evt) {
                mouseisdown = true;
                doMouseMove(c2, evt, false);
            }, false);
            c2.addEventListener("touchend", function (evt) {
                mouseisdown = false;
            }, false);

            var c3 = document.getElementById("Col3");
            c3.addEventListener("mousemove", function (evt) {
                doMouseMove(c3, evt);
            }, false);
            c3.addEventListener("click", function (evt) {
                doMouseMove(c3, evt, true);
            }, false);
            c3.addEventListener("touchstart", function (evt) {
                mouseisdown = true;
                doMouseMove(c3, evt, false);
            }, false);
            c3.addEventListener("touchmove", function (evt) {
                mouseisdown = true;
                doMouseMove(c3, evt, false);
            }, false);
            c3.addEventListener("touchend", function (evt) {
                mouseisdown = false;
            }, false);

            originalColor = initialcolor;
            r = (originalColor >> 16) & 0xff;
            g = (originalColor >> 8) & 0xff;
            b = (originalColor ) & 0xff;

            document.getElementById('desc').innerHTML = 'Choose the color';
            refreshUI();
            refreshled();
        }

        function isHsl() {
            var hsl = false;
            var options = document.getElementsByName("colorspace");
            for (var i = 0; i < options.length; i++) {
                if (options[i].checked) hsl = (options[i].value == 'hsl')
            }
            return hsl;
        }

        function refreshHexCode() {
            var color;
            if (colorSystemisHsl)
                color = (h << 16) | (s << 8) | l;
            else
                color = (r << 16) | (g << 8) | b;
            color = color.toString(16).toUpperCase();
            while (color.length < 6) color = '0' + color;
            document.getElementById('hexcode').value = color;
        }


        function refreshGauge(index) {
            var c = document.getElementById("Col0");
            var ctx = c.getContext("2d");
            var w = ctx.canvas.width;
            var ch = ctx.canvas.height;
            var i, col;

            var tmp = isHsl();
            if (tmp != colorSystemisHsl) {
                colorSystemisHsl = tmp;
                if (colorSystemisHsl) {
                    col = rgb2hsl((r << 16) | (g << 8) | b);
                    h = (col >> 16 & 0xFF);
                    s = (col >> 8 & 0xFF);
                    l = (col & 0xFF);
                    document.getElementById("Col1desc").innerHTML = 'Hue:';
                    document.getElementById("Col2desc").innerHTML = 'Sat.:';
                    document.getElementById("Col3desc").innerHTML = 'Light:';

                } else {
                    col = hsl2rgb((h << 16) | (s << 8) | l);
                    r = (col >> 16 & 0xFF);
                    g = (col >> 8 & 0xFF);
                    b = (col & 0xFF);
                    document.getElementById("Col1desc").innerHTML = 'Red:';
                    document.getElementById("Col2desc").innerHTML = 'Green.:';
                    document.getElementById("Col3desc").innerHTML = 'Blue:';
                }
            }
            var grd = ctx.createLinearGradient(0, 0, w, 0);
            if (colorSystemisHsl) {
                switch (index) {
                    case 1 :
                        for (i = 0; i < 255; i++) {
                            color = "hsl(" + parseInt((360 * i / 255)) + ",100%,50%)";
                            grd.addColorStop(i / 255.0, color);
                        }
                        x = w * h / 255;
                        break;
                    case 2 :
                        for (i = 0; i < 255; i++) {
                            color = "hsl(" + (360 * h / 255) + "," + parseInt(100 * i / 255) + "%," + parseInt(100 * l / 255) + "%)";
                            grd.addColorStop(i / 255.0, color);
                        }

                        x = w * s / 255;
                        break;
                    case 3 :
                        for (i = 0; i < 255; i++) {
                            var color = "hsl(" + (360 * h / 255) + "," + parseInt(100 * s / 255) + "%," + parseInt(100 * i / 255) + "%)";
                            grd.addColorStop(i / 255.0, color);

                        }
                        x = w * l / 255;
                        break;
                }
            } else {
                grd.addColorStop(0, "#000000");
                switch (index) {
                    case 1 :
                        grd.addColorStop(1, "#FF0000");
                        x = w * r / 255;
                        break;
                    case 2 :
                        grd.addColorStop(1, "#00FF00");
                        x = w * g / 255;
                        break;
                    case 3 :
                        grd.addColorStop(1, "#0000FF");
                        x = w * b / 255;
                        break;
                }
            }
            ctx.fillStyle = grd;
            ctx.fillRect(0, 0, w, ch);
            ctx.fillStyle = '#000000';
            ctx.strokeStyle = "#FFFFFF";
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(x, ch / 4);
            ctx.lineTo(x - 5, 0);
            ctx.lineTo(x + 5, 0);
            ctx.closePath();
            ctx.fill();
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(x, ch - ch / 4);
            ctx.lineTo(x - 5, ch);
            ctx.lineTo(x + 5, ch);
            ctx.closePath();
            ctx.fill();
            ctx.stroke();
            var destCtx = document.getElementById("Col" + index).getContext('2d');
            destCtx.drawImage(c, 0, 0);
        }

        function refreshUI() {
            for (var i = 1; i <= 3; i++) refreshGauge(i);
            var color;
            if (colorSystemisHsl)
                color = hsl2rgb((h << 16) | (s << 8) | l);
            else
                color = (r << 16) | (g << 8) | b;
            document.getElementById('colorSample').style.backgroundColor = color2HTML(color);
            refreshHexCode();
        }

    </script>
</head>
<body>

<!--Header -->
<h1> Yoctopuce Night Light </h1>
<?php

include("common.php");

function displayClockSelect($name, $h, $m)
{


    printf('<select name="%s_h" id="%s_h" onchange="updateClock(this);">', $name, $name);
    for ($i = 0; $i < 24; $i++) {
        printf('<option value="%02d" %s>%d</option>', $i, ($h == $i ? 'selected' : ''), $i);
    }
    print('</select>' . "h");

    printf('<select name="%s_m"  id="%s_m" onchange="updateClock(this);">' . "\n", $name, $name);
    for ($i = 0; $i < 60; $i++) {
        printf('<option value="%02d" %s>%d</option>', $i, ($m == $i ? 'selected' : ''), $i);
    }
    print('</select>' . "\n");
}


function displayConfig($title, $name, $hour, $config)
{
    $h_part = explode('h', $hour);
    print('<div class="time_config">');
    print('<h5>' . $title . '</h5>');
    print('<table class="pattern_config">');
    print('<tr><td>');
    printf('<label>Start at</label>');
    print('</td><td>');
    displayClockSelect($name, (int)$h_part[0], (int)$h_part[1]);
    print("</td></tr>\n");

    print("<tr><td>");
    printf('<label >Lighting</label></td><td>');
    printf('<select name="%s_pattern" onchange="updatePattern(this);" style="width: 100%%">' . "\n", $name);
    printf('<option value="off" %s>Off</option>' . "\n", ($config->pattern == 'off' ? 'selected' : ''));
    printf('<option value="static" %s>Static</option>' . "\n", ($config->pattern == 'static' ? 'selected' : ''));
    printf('<option value="blink" %s>Blink</option>' . "\n", ($config->pattern == 'blink' ? 'selected' : ''));
    printf('<option value="fade" %s>Fade</option>' . "\n", ($config->pattern == 'fade' ? 'selected' : ''));
    printf('<option value="rotate" %s>Rotate</option>' . "\n", ($config->pattern == 'rotate' ? 'selected' : ''));
    print('</select>' . "</td>");
    print("</td></tr>\n");

    print("<tr><td>");
    print("</td><td>\n");

    switch ($config->pattern) {
        case 'off':
            $show_b1 = false;
            $show_b2 = false;
            break;
        case 'static':
            $show_b1 = true;
            $show_b2 = false;
            break;
        default:
            $show_b1 = true;
            $show_b2 = true;
            break;
    }

    printf('<button id="%s_color1" class="color" hexcolor="%s" onclick="setcolor(this);" style="background: #%s; visibility: %s;"></button>' . "\n",
        $name, $config->color1, $config->color1, $show_b1?'visible':'hidden');
    printf('<button id="%s_color2" class="color" hexcolor="%s" onclick="setcolor(this);" style="background: #%s; visibility: %s;"></button>' . "\n",
        $name, $config->color2, $config->color2, $show_b2?'visible':'hidden');
    print('</td></tr></table>');
    print('</div>');
}

function displayNightLight($serial, $config)
{
    print("<section>\n");
    if ($config->name != "") {
        print("    <h2>{$config->name} ({$serial})</h2>\n");
    } else {
        print("    <h2>$serial</h2>\n");
    }
    print('    <div class="row">');
    print('        <div class="time_icon"><img src="img/night.png" alt="Night icon"></div>');
    displayConfig('Night configuration', $serial . '_night', $config->clock->night, $config->night_color);
    print('    </div>');

    print('<hr>');
    print('    <div class="row">');
    displayConfig('Wake up configuration', $serial . '_wakeup', $config->clock->wakeup, $config->wakeup_color);
    print('        <div class="time_icon"><img src="img/wakeup.png" alt="Wakup icon"></div>');
    print('    </div>');

    print('<hr>');
    print('    <div class="row">');

    print('        <div class="time_icon"><img src="img/day.png" alt="Day icon"></div>');
    displayConfig('Day configuration', $serial . '_day', $config->clock->day, $config->day_color);

    print('    </div>');

    print('</section>');

}


$configs = getAllConfigs();
foreach ($configs as $serial => $conf) {
    displayNightLight($serial, $conf);
}


$cburl = $_SERVER['SERVER_NAME'];
if ($_SERVER['SERVER_PORT'] != 80) {
    $cburl .= ':' . $_SERVER['SERVER_PORT'];
}
$cburl .= dirname($_SERVER['SCRIPT_NAME']) . '/callback.php';
?>

<div id="colorchooser" style="visibility: hidden">
    <div id="colorchooser_int">

        <canvas id="Col0" class='colorCtrl' style='display:none;'></canvas>
        <h5 id='desc'></h5>
        <div id='colorSample'
        ></div>

        <table id="colorWidget">
            <tr>
                <td colspan="2" id='rgbHsl'><input type="radio" name="colorspace" value='rgb' checked
                                                   onchange="refreshUI();">RGB&nbsp;&nbsp;
                    <input type="radio" name="colorspace" value='hsl' onchange="refreshUI();">HSL&nbsp;&nbsp;
                </td>
            </tr>
            <tr>
                <td id='Col1desc'>Red:</td>
                <td>
                    <canvas id="Col1" class='colorCtrl'></canvas>
                </td>
            </tr>
            <tr>
                <td id='Col2desc'>Green:</td>
                <td>
                    <canvas id="Col2" class='colorCtrl'></canvas>
                </td>
            </tr>
            <tr>
                <td id='Col3desc'>Blue:</td>
                <td>
                    <canvas id="Col3" class='colorCtrl'></canvas>
                </td>
            </tr>
        </table>
        <div id="hexviewdiv">
            Hex code:<input id='hexcode' style='text-align:right' size=6 maxlength=6 value=''
                            onkeyup='manualChange()'>
            <input id="hexColorTarget" value="" type="hidden">
        </div>
        <div id="colorChooserAction">
            <button id="colorCancel" onclick="colorCancel(this);">Cancel</button>
            <button id="colorSave" onclick="colorApply(this);">Apply</button>
        </div>
    </div>
</div>

<!--Second Grid-->

<section>
    <h2>Configuration</h2>
    <div class="row">
        <div class="time_icon"><img src="img/gear.png" alt="Wakup icon"></div>
        <div class="time_config">
            <h5>This example need to be run by a VirtualHub or a YoctoHub .</h5>
            <ol>
                <li> Connect to the web interface of the VirtualHub or YoctoHub that will run this script .</li>
                <li> Click on the <em>configure </em> button of the VirtualHub or YoctoHub .</li>
                <li> Click on the <em>edit </em> button of "Callback URL" settings .</li>
                <li> Set the <em>type of Callback </em> to <b> Yocto - API Callback </b>.</li>
                <li> Set the <em>callback URL</em> to<br/>
                    http://<b><?= $cburl ?></b>
                </li>
                <li>Click on the <em>test</em> button.</li>
            </ol>
        </div>
</section>


</body>
</html>
