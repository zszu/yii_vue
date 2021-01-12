<?php

namespace app\components;

use app\extensions\ZImage;
use yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

class ZHelper
{
    public static function cutStr($string, $length, $dot = ' ...') {
        if(strlen($string) <= $length) {
            return $string;
        }

        $pre = chr(1);
        $end = chr(1);
        $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), $string);

        $strcut = '';

        $n = $tn = $noc = 0;
        while($n < strlen($string)) {

            $t = ord($string[$n]);
            if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1; $n++; $noc++;
            } elseif(194 <= $t && $t <= 223) {
                $tn = 2; $n += 2; $noc += 2;
            } elseif(224 <= $t && $t <= 239) {
                $tn = 3; $n += 3; $noc += 2;
            } elseif(240 <= $t && $t <= 247) {
                $tn = 4; $n += 4; $noc += 2;
            } elseif(248 <= $t && $t <= 251) {
                $tn = 5; $n += 5; $noc += 2;
            } elseif($t == 252 || $t == 253) {
                $tn = 6; $n += 6; $noc += 2;
            } else {
                $n++;
            }

            if($noc >= $length) {
                break;
            }

        }
        if($noc > $length) {
            $n -= $tn;
        }

        $strcut = substr($string, 0, $n);

        $strcut = str_replace(array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

        $pos = strrpos($strcut, chr(1));
        if($pos !== false) {
            $strcut = substr($strcut,0,$pos);
        }
        return $strcut.($strcut != $string ? $dot : '');
    }

    public static function addSuffix($filename, $suffix) {
        $fps = pathinfo($filename);
        $filename = $fps['filename'].$suffix.'.'.self::arrayValue($fps, 'extension', '');
        return $fps['dirname'].'/'.$filename;
    }

    public static function showMessage($message, $url_forward='', $extraparam=array())
    {
        $show_message = $message;
        $param = array(
            'type' => $url_forward ? 'alert_info' : 'alert_error',
            'title'	 => '系统提示',
            'url'   => $url_forward,
            'header'	=> false,
            'timeout'	=> null,
            'refreshtime'	=> null,
            'closetime'	=> null,
            'time'	=> 3000,
            'alert'		=> null,
            'return'	=> false,
            'redirectmsg'	=> 0,
            'msgtype'	=> 1,
            'showmsg'	=> true,
            'showdialog'	=> false,
            'login'		=> false,
            'handle'	=> false,
            'extrajs'	=> '',
            'striptags'	=> true,
        );
        foreach($extraparam as $k => $v) {
            $param[$k] = $v;
        }
        $param['message'] = $param['striptags'] ? strip_tags($message) : $message;
        if(is_array($param['url'])) {
            $param['url'] = Url::to($param['url']);
        }
        return Yii::$app->controller->render('//layouts/' . Yii::$app->controller->id .'_message', [
            'data'=>$param
        ]);
    }

    public static function actionReturn($message, $url = '', $params = [])
    {
        $data = [
            'title' => '系统提示',
            'type' => 'alert_info',
            'time' => 3000,
            'message' => ArrayHelper::getValue($params, 'strip_tags') ? strip_tags($message) : $message,
            'url' => is_array($url) ? Url::to($url) : $url,
            'layout' => Yii::$app->controller->id . '_message',
        ];
        foreach ($params as $k => $v) {
            if (isset($data[$k])) {
                $data[$k] = $v;
            }
        }

        return Yii::$app->controller->render('//layouts/' . $data['layout'], [
            'data' => $data,
        ]);
    }

    public static function success($message, $url = '', $params = [])
    {
        $params['type'] = 'alert_right';
        if (!$url) {
            $params['url'] = Url::current();
        }

        return self::actionReturn($message, $url, $params);
    }

    public static function error($message, $url = '', $params = [])
    {
        $params['type'] = 'alert_error';
        return self::actionReturn($message, $url, $params);
    }

    public static function info($message, $url = '', $params = [])
    {
        if (!$url) {
            $params['url'] = Url::current();
        }

        return self::actionReturn($message, $url, $params);
    }

    public static function random($length, $numeric = 0) {
        $seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
        if($numeric) {
            $hash = '';
        } else {
            $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
            $length--;
        }
        $max = strlen($seed) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $seed{mt_rand(0, $max)};
        }
        return $hash;
    }

    public static function isLocalFile($file) {
        $valueparse = parse_url($file);
        return !isset($valueparse['host']);
    }
    public static function unlink($file) {
        if(self::isLocalFile($file)) {
            $path = $file;
            $path = Yii::getAlias('@webroot') . str_replace('/', DIRECTORY_SEPARATOR, $path);
            @unlink($path);
        }
    }
    public static function modelUpload($model, $attribute, $type, $size='', $thumbs=array()) {
        $oldpic = $model->$attribute;
        if(isset($_POST['delete'.$attribute]) && $_POST['delete'.$attribute]) {
            self::unlink($oldpic);
            $model->$attribute = '';
        }
        $upload = new ZUpload(\yii\web\UploadedFile::getInstance($model, $attribute), $type);
        if($upload->save()) {
            if($oldpic) self::unlink($oldpic);
            if($thumbs) {
                foreach($thumbs as $k=>$v) {
                    if($oldpic) self::unlink(self::addsuffix($oldpic, $k));
                    list($w, $h) = explode('*', $v);
                    $w = intval($w);
                    $h = intval($h);
                    if($k && $w && $h) {
                        $img = new \app\extensions\ZImage($upload->get_path());
                        $img->thumb($w, $h);
                        $img->savethumb($upload->get_path(), $k);
                    }
                }
            }
            if($size) {
                list($w, $h) = explode('*', $size);
                $w = intval($w);
                $h = intval($h);
                if($w && $h) {
                    $img = new \app\extensions\ZImage($upload->get_path());
                    $img->thumb($w, $h);
                    $img->save($upload->get_path());
                }
            }
            $model->$attribute = $upload->get_url();
            return true;
        }
        return false;
    }

    public static function sizeArr($size)
    {
        try {
            list($w, $h) = explode('*', $size);
        } catch (\Exception $e) {
            return null;
        }
        $w = intval($w);
        $h = intval($h);
        if ($w < 10 || $w > 3000 || $h < 10 || $h > 3000) {
            return null;
        }

        return ['width' => $w, 'height' => $h];
    }

    public static function imgCrop($file, $size)
    {
        if (!file_exists($file)) {
            return;
        }
        $sizeArr = self::sizeArr($size);
        if ($sizeArr) {
            $img = new ZImage($file);
            $img->thumb($sizeArr['width'], $sizeArr['height']);
            $img->save($file);
        }
    }

    public static function modelList($modelclass, $condition, $keyattribute, $valueattribute='') {
        $data = array();
        $models = $modelclass::model()->findAll($condition);
        foreach($models as $model) {
            $data[$model->$keyattribute] = $valueattribute ? $model->$valueattribute : $model->attributes;
        }
        return $data;
    }

    public static function fullPath($path) {
        if(self::isLocalFile($path)) {
            return Yii::$app->baseUrl.$path;
        } else {
            return $path;
        }
    }

    public static function isItemActive($item, $route)
    {
        if (isset($item['activeUrls']) && is_array($item['activeUrls'])) {
            foreach($item['activeUrls'] as &$url) {
                $url = strtolower(trim($url, '/'));
            }
            if (in_array(strtolower($route), $item['activeUrls'])) {
                return true;
            }
        }

        if (isset($item['url']) && is_array($item['url']) && !strcasecmp(trim($item['url'][0], '/'), $route)) {
            unset($item['url']['#']);
            if (count($item['url'])>1) {
                foreach (array_splice($item['url'],1) as $name=>$value) {
                    if (!isset($_GET[$name]) || $_GET[$name]!=$value) {
                        return false;
                    }
                }
            }
            return true;
        }

        if (isset($item['items']) && is_array($item['items'])) {
            foreach ($item['items'] as $item) {
                $return = self::isItemActive($item, $route);
                if ($return) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function idUrl($route, $id)
    {
        return $id == 0 ? [$route] : [$route, 'id' => $id];
    }

    public static function arrayValue($array, $key, $defValue=false) {

        return isset($array[$key]) ? $array[$key] : $defValue;

    }
    
}
