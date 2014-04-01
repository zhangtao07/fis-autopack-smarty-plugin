<?php

function smarty_compiler_require($arrParams,  $smarty){
    $strName = $arrParams['name'];

    $async = 'false';

    if (isset($arrParams['async'])) {
        $async = trim($arrParams['async'], "'\" ");
        if ($async !== 'true') {
            $async = 'false';
        }
    }

    $strCode = '';
    if($strName) {
        $strResourceApiPath = preg_replace('/[\\/\\\\]+/', '/', dirname(__FILE__) . '/lib/FISPagelet.class.php');
        $strCode .= '<?php if(!class_exists(\'FISPagelet\', false)){require_once(\'' . $strResourceApiPath . '\');}';

        /********autopack collect require resource************/
        $strAutoPackPath = preg_replace('/[\\/\\\\]+/', '/', dirname(__FILE__) . '/lib/FISAutoPack.class.php');
        //注意加php头，否则解析出错
        $strCode = '<?php if(!class_exists(\'FISAutoPack\')){require_once(\'' . $strAutoPackPath . '\');}';
        $strCode .= 'FISAutoPack::addHashTable(' . $strName . ',$_smarty_tpl->smarty' . ');';
        /*****************autopack end**********************/

        $strCode .= 'FISPagelet::load(' . $strName . ',$_smarty_tpl->smarty,'.$async.');';
        $strCode .= '?>';
    }
    return $strCode;
}
