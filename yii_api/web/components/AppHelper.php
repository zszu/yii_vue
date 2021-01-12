<?php

namespace app\components;

use app\extensions\PHPMailer\PHPMailer;
use app\models\Param;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;

class AppHelper
{
    /**
     * @param $model ActiveRecord
     * @param $file UploadedFile
     * @param $field string
     * @param $dir string
     */
    public static function upload($model, $file, $field, $dir, $imageSize = null)
    {
        if (!$file) {
            return;
        }

        $path = Yii::$app->params['uploadPath'] . $dir . '/';
        $fullPath = Yii::getAlias('@webroot' . $path);
        if (!file_exists($fullPath)) {
            FileHelper::createDirectory($fullPath);
        }
        $filename = date('ymdHis') . mt_rand(10000, 99999) . '.' . $file->extension;
        if ($file->saveAs($fullPath . $filename)) {
            ZHelper::imgCrop($fullPath . $filename, $imageSize);
            if ($model->$field) {
                @unlink(Yii::getAlias('@webroot') . $model->$field);
            }
            $model->$field = $path . $filename;
        } else {
            Yii::error($file->error);
        }
    }

    public static function processRelativeUrl($str, $onlyHost = false)
    {
        return strtr($str, ['<img src="/' => '<img src="' . Url::to($onlyHost ? '/' : '@web/',  true)]);
    }

    public static function sendMail($to, $subject, $content, $view = null, $params = [])
    {
        if (1 == 1) {
            return self::sendMailByPhpMailer($to, $subject, $content, $view, $params);
        }
        $host = Param::getValue('mail_host');
        $password = Param::getValue('mail_password');
        $port = Param::getValue('mail_port');
        $ssl = Param::getValue('mail_ssl');
        $fromAddress = Param::getValue('mail_from_address');
        $fromName = Param::getValue('mail_from_name');
        if (empty($to)) {
            $toAddress = Param::getValue('mail_to_address');
        }

        if (!($host && $port && $fromAddress && $password && $fromName && $toAddress)) {
            return false;
        }


        /* @var $mailer \yii\swiftmailer\Mailer */
        $mailer = Yii::$app->mailer;
        $mailer->useFileTransport = false;
        $mailer->transport = [
            'class' => 'Swift_SmtpTransport',
            'host' => $host,
            'username' => $fromAddress,
            'password' => $password,
            'port' => $port,
            'encryption' => $ssl ? 'ssl' : '',
        ];

        try {
            $mailer->compose($view, $params)
                ->setFrom([$fromAddress => $fromName])
                ->setTo($toAddress)
                ->setSubject($subject)
                ->setHtmlBody($content)
                ->send();
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            return false;
        }

        return true;
    }

    public static function sendMailByPhpMailer($to, $subject, $content, $view = null, $params = [])
    {
        $host = Param::getValue('mail_host');
        $password = Param::getValue('mail_password');
        $port = Param::getValue('mail_port');
        $ssl = Param::getValue('mail_ssl');
        $fromAddress = Param::getValue('mail_from_address');
        $fromName = Param::getValue('mail_from_name');
        if (empty($to)) {
            $toAddress = Param::getValue('mail_to_address');
        }

        if (!($host && $port && $fromAddress && $password && $fromName && $toAddress)) {
            return false;
        }

        $mailer = new PHPMailer(true);
        try {
            $mailer->CharSet = 'UTF-8';
            $mailer->isSMTP();
            $mailer->Host = $host;
            $mailer->SMTPAuth = true;
            $mailer->Username = $fromAddress;
            $mailer->Password = $password;
            if ($ssl) {
                $mailer->SMTPSecure = 'ssl';
            }
            $mailer->Port = $port;
            $mailer->setFrom($fromAddress, $fromName);
            $mailer->addAddress($toAddress);
            $mailer->isHTML(true);
            $mailer->Subject = $subject;
            if ($view) {
                $mailer->Body = Yii::$app->view->renderFile("@app/mail/$view.php", $params);
            } else {
                $mailer->Body = $content;
            }
            $mailer->AltBody = strip_tags($mailer->Body);
            return $mailer->send();

        } catch (\Exception $e) {
            Yii::error($e->getMessage());
        }
        return false;
    }


    //无限级分类
    public static function cateGroy($_data, $_pid = 0, $_level = 1) {
        static $_new_data = [];

        foreach ($_data as $_key => $_value) {
            if ($_value['up_id'] == $_pid) {
                $_value['level'] = $_level;
                $_new_data[] = $_value;
                unset($_data[$_key]);
                self::cateGroy($_data, $_value['id'], $_level + 1);
            }
        }
        return $_new_data;
    }

}