<?php

namespace TMCms\Modules\Recaptcha;

use TMCms\Modules\IModule;
use TMCms\Templates\PageHead;
use TMCms\Traits\singletonInstanceTrait;

define('RECAPTCHA_BUTTON_CLASS', 'g-recaptcha');

class ModuleRecaptcha implements IModule
{
    use singletonInstanceTrait;

    private static $errors = [];

    /**
     * @param $text
     * @param array $options
     * @param string $prepareFormID
     * @return string
     */
    public static function renderButton($text, array $options = [], string $prepareFormID = ''){
        if($prepareFormID) self::prepareButton($prepareFormID);
        $default = [
            'data-sitekey' => \TMCms\Modules\Settings\ModuleSettings::getCustomSettingValue('recaptcha', 'key'),
            'data-callback' => "onSubmit",
        ];
        if(!empty($options['class'])){
            $options['class'] .= ' '. RECAPTCHA_BUTTON_CLASS;
        }else{
            $options['class'] = RECAPTCHA_BUTTON_CLASS;
        }
        $options = array_merge($default, $options);
        $attrs = [];
        foreach($options as $key=>$v){
            $attrs[] = $key.'="'.$v.'"';
        }
        return '<button '.implode(' ', $attrs).'>'.$text.'</button>';
    }

    /**
     * @param $fomm_id
     */
    public static function prepareButton($fomm_id){
        PageHead::getInstance()
            ->addJsUrl('https://www.google.com/recaptcha/api.js')
            ->addJs('function onSubmit(token) {
                document.getElementById("'.$fomm_id.'").submit();
            }')
        ;
    }

    /**
     * @return bool
     */
    public static function validate(){
        if(empty($_POST['g-recaptcha-response'])){
            self::$errors = ['Recaptcha not set'];
            return false;
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, [
            'secret' => \TMCms\Modules\Settings\ModuleSettings::getCustomSettingValue('recaptcha', 'secret'),
            'response' => $_POST['g-recaptcha-response'],
        ]);
        $out = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($out, true);
        if(!$res['success']){
            self::$errors = $res['error-codes'];
        }
        return $res['success'];
    }

    /**
     * @return array
     */
    public static function getErrors(){
        return self::$errors;
    }

}