<?php

function smarty_compiler_html($arrParams,  $smarty){
    $strResourceApiPath = preg_replace('/[\\/\\\\]+/', '/', dirname(__FILE__) . '/lib/FISPagelet.class.php');
    $strFramework = $arrParams['framework'];
    $strMode = isset($arrParams['mode']) ? $arrParams['mode'] : 'null';
    unset($arrParams['framework']);
    unset($arrParams['mode']);
    $strAttr = '';
    $strCode = '<?php ';
    $strCode .= 'if(!class_exists(\'FISPagelet\', false)){require_once(\'' . $strResourceApiPath . '\');}';

    if (isset($strFramework)) {
        $strCode .= 'FISResource::setFramework(FISResource::load('.$strFramework.', $_smarty_tpl->smarty));';
    }

    /********************autopack init********************************/
    $strAutoPackPath = preg_replace('/[\\/\\\\]+/', '/', dirname(__FILE__) . '/lib/FISAutoPack.class.php');
    $strCode .= 'if(!class_exists(\'FISAutoPack\', false)){require_once(\'' . $strAutoPackPath . '\');}';
    $fid = $arrParams['fid'];
    $sampleRate = $arrParams['sampleRate'];
    unset($arrParams['fid']);
    unset($arrParams['sampleRate']);
    if (isset($fid)){
        $strCode .= 'FISAutoPack::setFid('.$fid.');';    
    }
    if (isset($sampleRate)){
        $strCode .= 'FISAutoPack::setSampleRate('.$sampleRate.');';  
    }
    /*********************autopack end*******************************/


    $strCode .= 'FISPagelet::init('.$strMode.');';
    $strCode .= ' ?>';
    foreach ($arrParams as $_key => $_value) {
        if (is_numeric($_key)) {
            $strAttr .= ' <?php echo ' . $_value .';?>';
        } else {
            $strAttr .= ' ' . $_key . '="<?php echo ' . $_value . ';?>"';
        }
    }
    return $strCode . "<html{$strAttr}>";
}

function smarty_compiler_htmlclose($arrParams,  $smarty){
    $strCode = '<?php ';

    /********************autopack setPageName********************************/
    $template_dir = $smarty->getTemplateDir();
    $template_dir = str_replace('\\', '/', $template_dir[0]);
    $strAutoPackPath = preg_replace('/[\\/\\\\]+/', '/', dirname(__FILE__) . '/lib/FISAutoPack.class.php');
    $strCode .= '$tpl=str_replace("\\\\", "/", $_smarty_tpl->template_resource);';
    $strCode .= 'if(!class_exists(\'FISAutoPack\')){require_once(\'' . $strAutoPackPath . '\');}';
    $strCode .= 'FISAutoPack::setPageName(str_replace("' . $template_dir . '", "", $tpl));';
    /*********************autopack end*******************************/

    $strCode .= '$_smarty_tpl->registerFilter(\'output\', array(\'FISPagelet\', \'renderResponse\'));';
    $strCode .= '?>';
    $strCode .= '</html>';
    return $strCode;
}
