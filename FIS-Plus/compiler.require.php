<?php

function smarty_compiler_require($arrParams,  $smarty){
    $strName = $arrParams['name'];
    $strCode = '';
    if($strName){
        $strResourceApiPath = preg_replace('/[\\/\\\\]+/', '/', dirname(__FILE__) . '/FISResource.class.php');
        $strCode .= '<?php if(!class_exists(\'FISResource\')){require_once(\'' . $strResourceApiPath . '\');}';
        $strCode .= 'FISResource::load(' . $strName . ',$_smarty_tpl->smarty);';

        /********autopack collect require resource************/
        $strAutoPackPath = preg_replace('/[\\/\\\\]+/', '/', dirname(__FILE__) . '/FISAutoPack.class.php');
        //注意加php头，否则解析出错
        $strCode = '<?php if(!class_exists(\'FISAutoPack\')){require_once(\'' . $strAutoPackPath . '\');}';
        $strCode .= 'FISAutoPack::addHashTable(' . $strName . ',$_smarty_tpl->smarty' . ');';
        /*****************autopack end**********************/

        $strCode .= '?>';
    }
    return $strCode;
}
