<?php
/*********************************************************************
 *
 * $Id: yocto_colorledcluster.php 25202 2016-08-17 10:24:49Z seb $
 *
 * Implements YColorLedCluster, the high-level API for ColorLedCluster functions
 *
 * - - - - - - - - - License information: - - - - - - - - - 
 *
 *  Copyright (C) 2011 and beyond by Yoctopuce Sarl, Switzerland.
 *
 *  Yoctopuce Sarl (hereafter Licensor) grants to you a perpetual
 *  non-exclusive license to use, modify, copy and integrate this
 *  file into your software for the sole purpose of interfacing
 *  with Yoctopuce products.
 *
 *  You may reproduce and distribute copies of this file in
 *  source or object form, as long as the sole purpose of this
 *  code is to interface with Yoctopuce products. You must retain
 *  this notice in the distributed source file.
 *
 *  You should refer to Yoctopuce General Terms and Conditions
 *  for additional information regarding your rights and
 *  obligations.
 *
 *  THE SOFTWARE AND DOCUMENTATION ARE PROVIDED 'AS IS' WITHOUT
 *  WARRANTY OF ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING 
 *  WITHOUT LIMITATION, ANY WARRANTY OF MERCHANTABILITY, FITNESS
 *  FOR A PARTICULAR PURPOSE, TITLE AND NON-INFRINGEMENT. IN NO
 *  EVENT SHALL LICENSOR BE LIABLE FOR ANY INCIDENTAL, SPECIAL,
 *  INDIRECT OR CONSEQUENTIAL DAMAGES, LOST PROFITS OR LOST DATA,
 *  COST OF PROCUREMENT OF SUBSTITUTE GOODS, TECHNOLOGY OR
 *  SERVICES, ANY CLAIMS BY THIRD PARTIES (INCLUDING BUT NOT
 *  LIMITED TO ANY DEFENSE THEREOF), ANY CLAIMS FOR INDEMNITY OR
 *  CONTRIBUTION, OR OTHER SIMILAR COSTS, WHETHER ASSERTED ON THE
 *  BASIS OF CONTRACT, TORT (INCLUDING NEGLIGENCE), BREACH OF
 *  WARRANTY, OR OTHERWISE.
 *
 *********************************************************************/

//--- (YColorLedCluster return codes)
//--- (end of YColorLedCluster return codes)
//--- (YColorLedCluster definitions)
if(!defined('Y_ACTIVELEDCOUNT_INVALID'))     define('Y_ACTIVELEDCOUNT_INVALID',    YAPI_INVALID_UINT);
if(!defined('Y_MAXLEDCOUNT_INVALID'))        define('Y_MAXLEDCOUNT_INVALID',       YAPI_INVALID_UINT);
if(!defined('Y_BLINKSEQMAXCOUNT_INVALID'))   define('Y_BLINKSEQMAXCOUNT_INVALID',  YAPI_INVALID_UINT);
if(!defined('Y_BLINKSEQMAXSIZE_INVALID'))    define('Y_BLINKSEQMAXSIZE_INVALID',   YAPI_INVALID_UINT);
if(!defined('Y_COMMAND_INVALID'))            define('Y_COMMAND_INVALID',           YAPI_INVALID_STRING);
//--- (end of YColorLedCluster definitions)

//--- (YColorLedCluster declaration)
/**
 * YColorLedCluster Class: ColorLedCluster function interface
 *
 * The Yoctopuce application programming interface
 * allows you to drive a color LED cluster. Unlike the ColorLed class, the ColorLedCluster
 * allows to handle several LEDs at one. Color changes can be done   using RGB coordinates as well as
 * HSL coordinates.
 * The module performs all conversions form RGB to HSL automatically. It is then
 * self-evident to turn on a LED with a given hue and to progressively vary its
 * saturation or lightness. If needed, you can find more information on the
 * difference between RGB and HSL in the section following this one.
 */
class YColorLedCluster extends YFunction
{
    const ACTIVELEDCOUNT_INVALID         = YAPI_INVALID_UINT;
    const MAXLEDCOUNT_INVALID            = YAPI_INVALID_UINT;
    const BLINKSEQMAXCOUNT_INVALID       = YAPI_INVALID_UINT;
    const BLINKSEQMAXSIZE_INVALID        = YAPI_INVALID_UINT;
    const COMMAND_INVALID                = YAPI_INVALID_STRING;
    //--- (end of YColorLedCluster declaration)

    //--- (YColorLedCluster attributes)
    protected $_activeLedCount           = Y_ACTIVELEDCOUNT_INVALID;     // UInt31
    protected $_maxLedCount              = Y_MAXLEDCOUNT_INVALID;        // UInt31
    protected $_blinkSeqMaxCount         = Y_BLINKSEQMAXCOUNT_INVALID;   // UInt31
    protected $_blinkSeqMaxSize          = Y_BLINKSEQMAXSIZE_INVALID;    // UInt31
    protected $_command                  = Y_COMMAND_INVALID;            // Text
    //--- (end of YColorLedCluster attributes)

    function __construct($str_func)
    {
        //--- (YColorLedCluster constructor)
        parent::__construct($str_func);
        $this->_className = 'ColorLedCluster';

        //--- (end of YColorLedCluster constructor)
    }

    //--- (YColorLedCluster implementation)

    function _parseAttr($name, $val)
    {
        switch($name) {
        case 'activeLedCount':
            $this->_activeLedCount = intval($val);
            return 1;
        case 'maxLedCount':
            $this->_maxLedCount = intval($val);
            return 1;
        case 'blinkSeqMaxCount':
            $this->_blinkSeqMaxCount = intval($val);
            return 1;
        case 'blinkSeqMaxSize':
            $this->_blinkSeqMaxSize = intval($val);
            return 1;
        case 'command':
            $this->_command = $val;
            return 1;
        }
        return parent::_parseAttr($name, $val);
    }

    /**
     * Returns the number of LEDs currently handled by the device.
     *
     * @return an integer corresponding to the number of LEDs currently handled by the device
     *
     * On failure, throws an exception or returns Y_ACTIVELEDCOUNT_INVALID.
     */
    public function get_activeLedCount()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_ACTIVELEDCOUNT_INVALID;
            }
        }
        return $this->_activeLedCount;
    }

    /**
     * Changes the number of LEDs currently handled by the device.
     *
     * @param newval : an integer corresponding to the number of LEDs currently handled by the device
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_activeLedCount($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("activeLedCount",$rest_val);
    }

    /**
     * Returns the maximum number of LEDs that the device can handle.
     *
     * @return an integer corresponding to the maximum number of LEDs that the device can handle
     *
     * On failure, throws an exception or returns Y_MAXLEDCOUNT_INVALID.
     */
    public function get_maxLedCount()
    {
        if ($this->_cacheExpiration == 0) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_MAXLEDCOUNT_INVALID;
            }
        }
        return $this->_maxLedCount;
    }

    /**
     * Returns the maximum number of sequences that the device can handle.
     *
     * @return an integer corresponding to the maximum number of sequences that the device can handle
     *
     * On failure, throws an exception or returns Y_BLINKSEQMAXCOUNT_INVALID.
     */
    public function get_blinkSeqMaxCount()
    {
        if ($this->_cacheExpiration == 0) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_BLINKSEQMAXCOUNT_INVALID;
            }
        }
        return $this->_blinkSeqMaxCount;
    }

    /**
     * Returns the maximum length of sequences.
     *
     * @return an integer corresponding to the maximum length of sequences
     *
     * On failure, throws an exception or returns Y_BLINKSEQMAXSIZE_INVALID.
     */
    public function get_blinkSeqMaxSize()
    {
        if ($this->_cacheExpiration == 0) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_BLINKSEQMAXSIZE_INVALID;
            }
        }
        return $this->_blinkSeqMaxSize;
    }

    public function get_command()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_COMMAND_INVALID;
            }
        }
        return $this->_command;
    }

    public function set_command($newval)
    {
        $rest_val = $newval;
        return $this->_setAttr("command",$rest_val);
    }

    /**
     * Retrieves a RGB LED cluster for a given identifier.
     * The identifier can be specified using several formats:
     * <ul>
     * <li>FunctionLogicalName</li>
     * <li>ModuleSerialNumber.FunctionIdentifier</li>
     * <li>ModuleSerialNumber.FunctionLogicalName</li>
     * <li>ModuleLogicalName.FunctionIdentifier</li>
     * <li>ModuleLogicalName.FunctionLogicalName</li>
     * </ul>
     *
     * This function does not require that the RGB LED cluster is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method YColorLedCluster.isOnline() to test if the RGB LED cluster is
     * indeed online at a given time. In case of ambiguity when looking for
     * a RGB LED cluster by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     *
     * @param func : a string that uniquely characterizes the RGB LED cluster
     *
     * @return a YColorLedCluster object allowing you to drive the RGB LED cluster.
     */
    public static function FindColorLedCluster($func)
    {
        // $obj                    is a YColorLedCluster;
        $obj = YFunction::_FindFromCache('ColorLedCluster', $func);
        if ($obj == null) {
            $obj = new YColorLedCluster($func);
            YFunction::_AddToCache('ColorLedCluster', $func, $obj);
        }
        return $obj;
    }

    public function sendCommand($command)
    {
        return $this->set_command($command);
    }

    /**
     * Changes the current color of consecutve LEDs in the cluster, using a RGB color. Encoding is done as
     * follows: 0xRRGGBB.
     *
     * @param ledIndex :  index of the first affected LED.
     * @param count    :  affected LED count.
     * @param rgbValue :  new color.
     *
     * @return YAPI_SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_rgbColor($ledIndex,$count,$rgbValue)
    {
        return $this->sendCommand(sprintf('SR%d,%d,%x',$ledIndex,$count,$rgbValue));
    }

    /**
     * Changes the  color at device startup of consecutve LEDs in the cluster, using a RGB color. Encoding
     * is done as follows: 0xRRGGBB.
     * Don't forget to call saveLedsConfigAtPowerOn() to make sure the modification is saved in the device
     * flash memory.
     *
     * @param ledIndex :  index of the first affected LED.
     * @param count    :  affected LED count.
     * @param rgbValue :  new color.
     *
     * @return YAPI_SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_rgbColorAtPowerOn($ledIndex,$count,$rgbValue)
    {
        return $this->sendCommand(sprintf('SC%d,%d,%x',$ledIndex,$count,$rgbValue));
    }

    /**
     * Changes the current color of consecutive LEDs in the cluster, using a HSL color. Encoding is done
     * as follows: 0xHHSSLL.
     *
     * @param ledIndex :  index of the first affected LED.
     * @param count    :  affected LED count.
     * @param hslValue :  new color.
     *
     * @return YAPI_SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_hslColor($ledIndex,$count,$hslValue)
    {
        return $this->sendCommand(sprintf('SH%d,%d,%x',$ledIndex,$count,$hslValue));
    }

    /**
     * Allows you to modify the current color of a group of adjacent LEDs to another color, in a seamless and
     * autonomous manner. The transition is performed in the RGB space.
     *
     * @param ledIndex :  index of the first affected LED.
     * @param count    :  affected LED count.
     * @param rgbValue :  new color (0xRRGGBB).
     * @param delay    :  transition duration in ms
     *
     * @return YAPI_SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function rgb_move($ledIndex,$count,$rgbValue,$delay)
    {
        return $this->sendCommand(sprintf('MR%d,%d,%x,%d',$ledIndex,$count,$rgbValue,$delay));
    }

    /**
     * Allows you to modify the current color of a group of adjacent LEDs  to another color, in a seamless and
     * autonomous manner. The transition is performed in the HSL space. In HSL, hue is a circular
     * value (0..360°). There are always two paths to perform the transition: by increasing
     * or by decreasing the hue. The module selects the shortest transition.
     * If the difference is exactly 180°, the module selects the transition which increases
     * the hue.
     *
     * @param ledIndex :  index of the fisrt affected LED.
     * @param count    :  affected LED count.
     * @param hslValue :  new color (0xHHSSLL).
     * @param delay    :  transition duration in ms
     *
     * @return YAPI_SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function hsl_move($ledIndex,$count,$hslValue,$delay)
    {
        return $this->sendCommand(sprintf('MH%d,%d,%x,%d',$ledIndex,$count,$hslValue,$delay));
    }

    /**
     * Adds an RGB transition to a sequence. A sequence is a transition list, which can
     * be executed in loop by a group of LEDs.  Sequences are persistent and are saved
     * in the device flash memory as soon as the saveBlinkSeq() method is called.
     *
     * @param seqIndex :  sequence index.
     * @param rgbValue :  target color (0xRRGGBB)
     * @param delay    :  transition duration in ms
     *
     * @return YAPI_SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function addRgbMoveToBlinkSeq($seqIndex,$rgbValue,$delay)
    {
        return $this->sendCommand(sprintf('AR%d,%x,%d',$seqIndex,$rgbValue,$delay));
    }

    /**
     * Adds an HSL transition to a sequence. A sequence is a transition list, which can
     * be executed in loop by an group of LEDs.  Sequences are persistant and are saved
     * in the device flash memory as soon as the saveBlinkSeq() method is called.
     *
     * @param seqIndex : sequence index.
     * @param hslValue : target color (0xHHSSLL)
     * @param delay    : transition duration in ms
     *
     * @return YAPI_SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function addHslMoveToBlinkSeq($seqIndex,$hslValue,$delay)
    {
        return $this->sendCommand(sprintf('AH%d,%x,%d',$seqIndex,$hslValue,$delay));
    }

    /**
     * Adds a mirror ending to a sequence. When the sequence will reach the end of the last
     * transition, its running speed will automatically be reversed so that the sequence plays
     * in the reverse direction, like in a mirror. After the first transition of the sequence
     * is played at the end of the reverse execution, the sequence starts again in
     * the initial direction.
     *
     * @param seqIndex : sequence index.
     *
     * @return YAPI_SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function addMirrorToBlinkSeq($seqIndex)
    {
        return $this->sendCommand(sprintf('AC%d,0,0',$seqIndex));
    }

    /**
     * Links adjacent LEDs to a specific sequence. These LEDs start to execute
     * the sequence as soon as  startBlinkSeq is called. It is possible to add an offset
     * in the execution: that way we  can have several groups of LED executing the same
     * sequence, with a  temporal offset. A LED cannot be linked to more than one sequence.
     *
     * @param ledIndex :  index of the first affected LED.
     * @param count    :  affected LED count.
     * @param seqIndex :  sequence index.
     * @param offset   :  execution offset in ms.
     *
     * @return YAPI_SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function linkLedToBlinkSeq($ledIndex,$count,$seqIndex,$offset)
    {
        return $this->sendCommand(sprintf('LS%d,%d,%d,%d',$ledIndex,$count,$seqIndex,$offset));
    }

    /**
     * Links adjacent LEDs to a specific sequence at device poweron. Don't forget to configure
     * the sequence auto start flag as well and call saveLedsConfigAtPowerOn(). It is possible to add an offset
     * in the execution: that way we  can have several groups of LEDs executing the same
     * sequence, with a  temporal offset. A LED cannot be linked to more than one sequence.
     *
     * @param ledIndex :  index of the first affected LED.
     * @param count    :  affected LED count.
     * @param seqIndex :  sequence index.
     * @param offset   :  execution offset in ms.
     *
     * @return YAPI_SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function linkLedToBlinkSeqAtPowerOn($ledIndex,$count,$seqIndex,$offset)
    {
        return $this->sendCommand(sprintf('LO%d,%d,%d,%d',$ledIndex,$count,$seqIndex,$offset));
    }

    /**
     * Links adjacent LEDs to a specific sequence. These LED start to execute
     * the sequence as soon as  startBlinkSeq is called. This function automatically
     * introduces a shift between LEDs so that the specified number of sequence periods
     * appears on the group of LEDs (wave effect).
     *
     * @param ledIndex :  index of the first affected LED.
     * @param count    :  affected LED count.
     * @param seqIndex :  sequence index.
     * @param periods  :  number of periods to show on LEDs.
     *
     * @return YAPI_SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function linkLedToPeriodicBlinkSeq($ledIndex,$count,$seqIndex,$periods)
    {
        return $this->sendCommand(sprintf('LP%d,%d,%d,%d',$ledIndex,$count,$seqIndex,$periods));
    }

    /**
     * Unlinks adjacent LEDs from a  sequence.
     *
     * @param ledIndex  :  index of the first affected LED.
     * @param count     :  affected LED count.
     *
     * @return YAPI_SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function unlinkLedFromBlinkSeq($ledIndex,$count)
    {
        return $this->sendCommand(sprintf('US%d,%d',$ledIndex,$count));
    }

    /**
     * Starts a sequence execution: every LED linked to that sequence starts to
     * run it in a loop.
     *
     * @param seqIndex :  index of the sequence to start.
     *
     * @return YAPI_SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function startBlinkSeq($seqIndex)
    {
        return $this->sendCommand(sprintf('SS%d',$seqIndex));
    }

    /**
     * Stops a sequence execution. If started again, the execution
     * restarts from the beginning.
     *
     * @param seqIndex :  index of the sequence to stop.
     *
     * @return YAPI_SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function stopBlinkSeq($seqIndex)
    {
        return $this->sendCommand(sprintf('XS%d',$seqIndex));
    }

    /**
     * Stops a sequence execution and resets its contents. Leds linked to this
     * sequence are not automatically updated anymore.
     *
     * @param seqIndex :  index of the sequence to reset
     *
     * @return YAPI_SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function resetBlinkSeq($seqIndex)
    {
        return $this->sendCommand(sprintf('ZS%d',$seqIndex));
    }

    /**
     * Configures a sequence to make it start automatically at device
     * startup. Don't forget to call saveBlinkSeq() to make sure the
     * modification is saved in the device flash memory.
     *
     * @param seqIndex :  index of the sequence to reset.
     * @param autostart : 0 to keep the sequence turned off and 1 to start it automatically.
     *
     * @return YAPI_SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_blinkSeqStateAtPowerOn($seqIndex,$autostart)
    {
        return $this->sendCommand(sprintf('AS%d,%d',$seqIndex,$autostart));
    }

    /**
     * Changes the execution speed of a sequence. The natural execution speed is 1000 per
     * thousand. If you configure a slower speed, you can play the sequence in slow-motion.
     * If you set a negative speed, you can play the sequence in reverse direction.
     *
     * @param seqIndex :  index of the sequence to start.
     * @param speed :     sequence running speed (-1000...1000).
     *
     * @return YAPI_SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_blinkSeqSpeed($seqIndex,$speed)
    {
        return $this->sendCommand(sprintf('CS%d,%d',$seqIndex,$speed));
    }

    /**
     * Saves the LEDs power-on configuration. This includes the start-up color or
     * sequence binding for all LEDs. Warning: if some LEDs are linked to a sequence, the
     * method saveBlinkSeq() must also be called to save the sequence definition.
     *
     * @return YAPI_SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function saveLedsConfigAtPowerOn()
    {
        return $this->sendCommand('WL');
    }

    public function saveLedsState()
    {
        return $this->sendCommand('WL');
    }

    /**
     * Saves the definition of a sequence. Warning: only sequence steps and flags are saved.
     * to save the LEDs startup bindings, the method saveLedsConfigAtPowerOn()
     * must be called.
     *
     * @param seqIndex :  index of the sequence to start.
     *
     * @return YAPI_SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function saveBlinkSeq($seqIndex)
    {
        return $this->sendCommand(sprintf('WS%d',$seqIndex));
    }

    /**
     * Sends a binary buffer to the LED RGB buffer, as is.
     * First three bytes are RGB components for LED specified as parameter, the
     * next three bytes for the next LED, etc.
     *
     * @param ledIndex : index of the first LED which should be updated
     * @param buff : the binary buffer to send
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_rgbColorBuffer($ledIndex,$buff)
    {
        return $this->_upload(sprintf('rgb:0:%d', $ledIndex), $buff);
    }

    /**
     * Sends 24bit RGB colors (provided as a list of integers) to the LED RGB buffer, as is.
     * The first number represents the RGB value of the LED specified as parameter, the second
     * number represents the RGB value of the next LED, etc.
     *
     * @param ledIndex : index of the first LED which should be updated
     * @param rgbList : a list of 24bit RGB codes, in the form 0xRRGGBB
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_rgbColorArray($ledIndex,$rgbList)
    {
        // $listlen                is a int;
        // $buff                   is a bin;
        // $idx                    is a int;
        // $rgb                    is a int;
        // $res                    is a int;
        $listlen = sizeof($rgbList);
        $buff =  pack('C',array_fill(0, 3*$listlen, 0));
        $idx = 0;
        while ($idx < $listlen) {
            $rgb = $rgbList[$idx];
            $buff[3*$idx] = pack('C', (((($rgb) >> (16))) & (255)));
            $buff[3*$idx+1] = pack('C', (((($rgb) >> (8))) & (255)));
            $buff[3*$idx+2] = pack('C', (($rgb) & (255)));
            $idx = $idx + 1;
        }
        // may throw an exception
        $res = $this->_upload(sprintf('rgb:0:%d', $ledIndex), $buff);
        return $res;
    }

    /**
     * Sets up a smooth RGB color transition to the specified pixel-by-pixel list of RGB
     * color codes. The first color code represents the target RGB value of the first LED,
     * the next color code represents the target value of the next LED, etc.
     *
     * @param rgbList : a list of target 24bit RGB codes, in the form 0xRRGGBB
     * @param delay   : transition duration in ms
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function rgbArray_move($rgbList,$delay)
    {
        // $listlen                is a int;
        // $buff                   is a bin;
        // $idx                    is a int;
        // $rgb                    is a int;
        // $res                    is a int;
        $listlen = sizeof($rgbList);
        $buff =  pack('C',array_fill(0, 3*$listlen, 0));
        $idx = 0;
        while ($idx < $listlen) {
            $rgb = $rgbList[$idx];
            $buff[3*$idx] = pack('C', (((($rgb) >> (16))) & (255)));
            $buff[3*$idx+1] = pack('C', (((($rgb) >> (8))) & (255)));
            $buff[3*$idx+2] = pack('C', (($rgb) & (255)));
            $idx = $idx + 1;
        }
        // may throw an exception
        $res = $this->_upload(sprintf('rgb:%d',$delay), $buff);
        return $res;
    }

    /**
     * Sends a binary buffer to the LED HSL buffer, as is.
     * First three bytes are HSL components for the LED specified as parameter, the
     * next three bytes for the second LED, etc.
     *
     * @param ledIndex : index of the first LED which should be updated
     * @param buff : the binary buffer to send
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_hslColorBuffer($ledIndex,$buff)
    {
        return $this->_upload(sprintf('hsl:0:%d', $ledIndex), $buff);
    }

    /**
     * Sends 24bit HSL colors (provided as a list of integers) to the LED HSL buffer, as is.
     * The first number represents the HSL value of the LED specified as parameter, the second number represents
     * the HSL value of the second LED, etc.
     *
     * @param ledIndex : index of the first LED which should be updated
     * @param hslList : a list of 24bit HSL codes, in the form 0xHHSSLL
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_hslColorArray($ledIndex,$hslList)
    {
        // $listlen                is a int;
        // $buff                   is a bin;
        // $idx                    is a int;
        // $hsl                    is a int;
        // $res                    is a int;
        $listlen = sizeof($hslList);
        $buff =  pack('C',array_fill(0, 3*$listlen, 0));
        $idx = 0;
        while ($idx < $listlen) {
            $hsl = $hslList[$idx];
            $buff[3*$idx] = pack('C', (((($hsl) >> (16))) & (255)));
            $buff[3*$idx+1] = pack('C', (((($hsl) >> (8))) & (255)));
            $buff[3*$idx+2] = pack('C', (($hsl) & (255)));
            $idx = $idx + 1;
        }
        // may throw an exception
        $res = $this->_upload(sprintf('hsl:0:%d', $ledIndex), $buff);
        return $res;
    }

    /**
     * Sets up a smooth HSL color transition to the specified pixel-by-pixel list of HSL
     * color codes. The first color code represents the target HSL value of the first LED,
     * the second color code represents the target value of the second LED, etc.
     *
     * @param hslList : a list of target 24bit HSL codes, in the form 0xHHSSLL
     * @param delay   : transition duration in ms
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function hslArray_move($hslList,$delay)
    {
        // $listlen                is a int;
        // $buff                   is a bin;
        // $idx                    is a int;
        // $hsl                    is a int;
        // $res                    is a int;
        $listlen = sizeof($hslList);
        $buff =  pack('C',array_fill(0, 3*$listlen, 0));
        $idx = 0;
        while ($idx < $listlen) {
            $hsl = $hslList[$idx];
            $buff[3*$idx] = pack('C', (((($hsl) >> (16))) & (255)));
            $buff[3*$idx+1] = pack('C', (((($hsl) >> (8))) & (255)));
            $buff[3*$idx+2] = pack('C', (($hsl) & (255)));
            $idx = $idx + 1;
        }
        // may throw an exception
        $res = $this->_upload(sprintf('hsl:%d',$delay), $buff);
        return $res;
    }

    /**
     * Returns a binary buffer with content from the LED RGB buffer, as is.
     * First three bytes are RGB components for the first LED in the interval,
     * the next three bytes for the second LED in the interval, etc.
     *
     * @param ledIndex : index of the first LED which should be returned
     * @param count    : number of LEDs which should be returned
     *
     * @return a binary buffer with RGB components of selected LEDs.
     *
     * On failure, throws an exception or returns an empty binary buffer.
     */
    public function get_rgbColorBuffer($ledIndex,$count)
    {
        return $this->_download(sprintf('rgb.bin?typ=0&pos=%d&len=%d',3*$ledIndex,3*$count));
    }

    /**
     * Returns a list on 24bit RGB color values with the current colors displayed on
     * the RGB leds. The first number represents the RGB value of the first LED,
     * the second number represents the RGB value of the second LED, etc.
     *
     * @param ledIndex : index of the first LED which should be returned
     * @param count    : number of LEDs which should be returned
     *
     * @return a list of 24bit color codes with RGB components of selected LEDs, as 0xRRGGBB.
     *
     * On failure, throws an exception or returns an empty array.
     */
    public function get_rgbColorArray($ledIndex,$count)
    {
        // $buff                   is a bin;
        $res = Array();         // intArr;
        // $idx                    is a int;
        // $r                      is a int;
        // $g                      is a int;
        // $b                      is a int;
        // may throw an exception
        $buff = $this->_download(sprintf('rgb.bin?typ=0&pos=%d&len=%d',3*$ledIndex,3*$count));
        while(sizeof($res) > 0) { array_pop($res); };
        $idx = 0;
        while ($idx < $count) {
            $r = ord($buff[3*$idx]);
            $g = ord($buff[3*$idx+1]);
            $b = ord($buff[3*$idx+2]);
            $res[] = $r*65536+$g*256+$b;
            $idx = $idx + 1;
        }
        return $res;
    }

    /**
     * Returns a list on 24bit RGB color values with the RGB LEDs startup colors.
     * The first number represents the startup RGB value of the first LED,
     * the second number represents the RGB value of the second LED, etc.
     *
     * @param ledIndex : index of the first LED  which should be returned
     * @param count    : number of LEDs which should be returned
     *
     * @return a list of 24bit color codes with RGB components of selected LEDs, as 0xRRGGBB.
     *
     * On failure, throws an exception or returns an empty array.
     */
    public function get_rgbColorArrayAtPowerOn($ledIndex,$count)
    {
        // $buff                   is a bin;
        $res = Array();         // intArr;
        // $idx                    is a int;
        // $r                      is a int;
        // $g                      is a int;
        // $b                      is a int;
        // may throw an exception
        $buff = $this->_download(sprintf('rgb.bin?typ=4&pos=%d&len=%d',3*$ledIndex,3*$count));
        while(sizeof($res) > 0) { array_pop($res); };
        $idx = 0;
        while ($idx < $count) {
            $r = ord($buff[3*$idx]);
            $g = ord($buff[3*$idx+1]);
            $b = ord($buff[3*$idx+2]);
            $res[] = $r*65536+$g*256+$b;
            $idx = $idx + 1;
        }
        return $res;
    }

    /**
     * Returns a list on sequence index for each RGB LED. The first number represents the
     * sequence index for the the first LED, the second number represents the sequence
     * index for the second LED, etc.
     *
     * @param ledIndex : index of the first LED which should be returned
     * @param count    : number of LEDs which should be returned
     *
     * @return a list of integers with sequence index
     *
     * On failure, throws an exception or returns an empty array.
     */
    public function get_linkedSeqArray($ledIndex,$count)
    {
        // $buff                   is a bin;
        $res = Array();         // intArr;
        // $idx                    is a int;
        // $seq                    is a int;
        // may throw an exception
        $buff = $this->_download(sprintf('rgb.bin?typ=1&pos=%d&len=%d',$ledIndex,$count));
        while(sizeof($res) > 0) { array_pop($res); };
        $idx = 0;
        while ($idx < $count) {
            $seq = ord($buff[$idx]);
            $res[] = $seq;
            $idx = $idx + 1;
        }
        return $res;
    }

    /**
     * Returns a list on 32 bit signatures for specified blinking sequences.
     * Since blinking sequences cannot be read from the device, this can be used
     * to detect if a specific blinking sequence is already programmed.
     *
     * @param seqIndex : index of the first blinking sequence which should be returned
     * @param count    : number of blinking sequences which should be returned
     *
     * @return a list of 32 bit integer signatures
     *
     * On failure, throws an exception or returns an empty array.
     */
    public function get_blinkSeqSignatures($seqIndex,$count)
    {
        // $buff                   is a bin;
        $res = Array();         // intArr;
        // $idx                    is a int;
        // $hh                     is a int;
        // $hl                     is a int;
        // $lh                     is a int;
        // $ll                     is a int;
        // may throw an exception
        $buff = $this->_download(sprintf('rgb.bin?typ=2&pos=%d&len=%d',4*$seqIndex,4*$count));
        while(sizeof($res) > 0) { array_pop($res); };
        $idx = 0;
        while ($idx < $count) {
            $hh = ord($buff[4*$idx]);
            $hl = ord($buff[4*$idx+1]);
            $lh = ord($buff[4*$idx+2]);
            $ll = ord($buff[4*$idx+3]);
            $res[] = (($hh) << (24))+(($hl) << (16))+(($lh) << (8))+$ll;
            $idx = $idx + 1;
        }
        return $res;
    }

    /**
     * Returns a list of integers with the current speed for specified blinking sequences.
     *
     * @param seqIndex : index of the first sequence speed which should be returned
     * @param count    : number of sequence speeds which should be returned
     *
     * @return a list of integers, 0 for sequences turned off and 1 for sequences running
     *
     * On failure, throws an exception or returns an empty array.
     */
    public function get_blinkSeqStateSpeed($seqIndex,$count)
    {
        // $buff                   is a bin;
        $res = Array();         // intArr;
        // $idx                    is a int;
        // $lh                     is a int;
        // $ll                     is a int;
        // may throw an exception
        $buff = $this->_download(sprintf('rgb.bin?typ=6&pos=%d&len=%d',$seqIndex,$count));
        while(sizeof($res) > 0) { array_pop($res); };
        $idx = 0;
        while ($idx < $count) {
            $lh = ord($buff[2*$idx]);
            $ll = ord($buff[2*$idx+1]);
            $res[] = (($lh) << (8))+$ll;
            $idx = $idx + 1;
        }
        return $res;
    }

    /**
     * Returns a list of integers with the "auto-start at power on" flag state for specified blinking sequences.
     *
     * @param seqIndex : index of the first blinking sequence which should be returned
     * @param count    : number of blinking sequences which should be returned
     *
     * @return a list of integers, 0 for sequences turned off and 1 for sequences running
     *
     * On failure, throws an exception or returns an empty array.
     */
    public function get_blinkSeqStateAtPowerOn($seqIndex,$count)
    {
        // $buff                   is a bin;
        $res = Array();         // intArr;
        // $idx                    is a int;
        // $started                is a int;
        // may throw an exception
        $buff = $this->_download(sprintf('rgb.bin?typ=5&pos=%d&len=%d',$seqIndex,$count));
        while(sizeof($res) > 0) { array_pop($res); };
        $idx = 0;
        while ($idx < $count) {
            $started = ord($buff[$idx]);
            $res[] = $started;
            $idx = $idx + 1;
        }
        return $res;
    }

    /**
     * Returns a list of integers with the started state for specified blinking sequences.
     *
     * @param seqIndex : index of the first blinking sequence which should be returned
     * @param count    : number of blinking sequences which should be returned
     *
     * @return a list of integers, 0 for sequences turned off and 1 for sequences running
     *
     * On failure, throws an exception or returns an empty array.
     */
    public function get_blinkSeqState($seqIndex,$count)
    {
        // $buff                   is a bin;
        $res = Array();         // intArr;
        // $idx                    is a int;
        // $started                is a int;
        // may throw an exception
        $buff = $this->_download(sprintf('rgb.bin?typ=3&pos=%d&len=%d',$seqIndex,$count));
        while(sizeof($res) > 0) { array_pop($res); };
        $idx = 0;
        while ($idx < $count) {
            $started = ord($buff[$idx]);
            $res[] = $started;
            $idx = $idx + 1;
        }
        return $res;
    }

    public function activeLedCount()
    { return $this->get_activeLedCount(); }

    public function setActiveLedCount($newval)
    { return $this->set_activeLedCount($newval); }

    public function maxLedCount()
    { return $this->get_maxLedCount(); }

    public function blinkSeqMaxCount()
    { return $this->get_blinkSeqMaxCount(); }

    public function blinkSeqMaxSize()
    { return $this->get_blinkSeqMaxSize(); }

    public function command()
    { return $this->get_command(); }

    public function setCommand($newval)
    { return $this->set_command($newval); }

    /**
     * Continues the enumeration of RGB LED clusters started using yFirstColorLedCluster().
     *
     * @return a pointer to a YColorLedCluster object, corresponding to
     *         a RGB LED cluster currently online, or a null pointer
     *         if there are no more RGB LED clusters to enumerate.
     */
    public function nextColorLedCluster()
    {   $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) return null;
        $next_hwid = YAPI::getNextHardwareId($this->_className, $resolve->result);
        if($next_hwid == null) return null;
        return self::FindColorLedCluster($next_hwid);
    }

    /**
     * Starts the enumeration of RGB LED clusters currently accessible.
     * Use the method YColorLedCluster.nextColorLedCluster() to iterate on
     * next RGB LED clusters.
     *
     * @return a pointer to a YColorLedCluster object, corresponding to
     *         the first RGB LED cluster currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstColorLedCluster()
    {   $next_hwid = YAPI::getFirstHardwareId('ColorLedCluster');
        if($next_hwid == null) return null;
        return self::FindColorLedCluster($next_hwid);
    }

    //--- (end of YColorLedCluster implementation)

};

//--- (ColorLedCluster functions)

/**
 * Retrieves a RGB LED cluster for a given identifier.
 * The identifier can be specified using several formats:
 * <ul>
 * <li>FunctionLogicalName</li>
 * <li>ModuleSerialNumber.FunctionIdentifier</li>
 * <li>ModuleSerialNumber.FunctionLogicalName</li>
 * <li>ModuleLogicalName.FunctionIdentifier</li>
 * <li>ModuleLogicalName.FunctionLogicalName</li>
 * </ul>
 *
 * This function does not require that the RGB LED cluster is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method YColorLedCluster.isOnline() to test if the RGB LED cluster is
 * indeed online at a given time. In case of ambiguity when looking for
 * a RGB LED cluster by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 *
 * @param func : a string that uniquely characterizes the RGB LED cluster
 *
 * @return a YColorLedCluster object allowing you to drive the RGB LED cluster.
 */
function yFindColorLedCluster($func)
{
    return YColorLedCluster::FindColorLedCluster($func);
}

/**
 * Starts the enumeration of RGB LED clusters currently accessible.
 * Use the method YColorLedCluster.nextColorLedCluster() to iterate on
 * next RGB LED clusters.
 *
 * @return a pointer to a YColorLedCluster object, corresponding to
 *         the first RGB LED cluster currently online, or a null pointer
 *         if there are none.
 */
function yFirstColorLedCluster()
{
    return YColorLedCluster::FirstColorLedCluster();
}

//--- (end of ColorLedCluster functions)
?>