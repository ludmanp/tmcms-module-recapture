<?php
namespace TMCms\Modules\Recaptcha;

use TMCms\Modules\Settings\ModuleSettings;

class CmsRecaptcha
{
    public function settings()
    {
        echo ModuleSettings::requireTableForExternalModule();
    }

    public function _settings()
    {
        ModuleSettings::requireUpdateModuleSettings();

        if (IS_AJAX_REQUEST) {
            die('1');
        }
        back();
    }
}