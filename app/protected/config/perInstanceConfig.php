<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-10-8
 * Time: 上午9:58
 */

/**
 * Custom configuration for the XGATE Web Form and Email Campaign project.
 */

$instanceConfig   = array(
    'modules' => array(
        'animals',
    ),
);

$instanceConfig['components']['custom']['class'] =
    'application.extensions.customZurmoModule.components.ZurmoModuleCustomManagement';
$instanceConfig['import'][] = "application.extensions.customZurmoModule.*";                          // Not Coding Standard
$instanceConfig['import'][] = "application.extensions.customZurmoModule.components.*";               // Not Coding Standard
$instanceConfig['import'][] = "application.extensions.customZurmoModule.utils.*";                    // Not Coding Standard

// $instanceConfig['components']['custom']['class'] =
// 'application.extensions.webform.components.WebformCustomManagement';
// $instanceConfig['import'][] = "application.extensions.webform.*";                          // Not Coding Standard
// $instanceConfig['import'][] = "application.extensions.webform.components.*";               // Not Coding Standard
// $instanceConfig['import'][] = "application.extensions.webform.utils.*";                    // Not Coding Standard

foreach (array('webforms', 'emailCampaigns', 'prospects', 'dmsLists', 'wechatCampaigns', 'wechatMessagings') as $index => $moduleName)
{
    $instanceConfig['import'][] = "application.modules.$moduleName.*";                           // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.adapters.*";                  // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.adapters.columns.*";          // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.dataproviders.*";             // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.elements.*";                  // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.elements.actions.*";          // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.elements.actions.security.*"; // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.elements.derived.*";          // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.components.*";                // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.controllers.*";               // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.controllers.filters.*";       // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.exceptions.*";                // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.forms.*";                     // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.forms.attributes.*";          // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.interfaces.*";                // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.models.*";                    // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.modules.*";                   // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.rules.*";                     // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.rules.attributes.*";          // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.rules.policies.*";            // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.tests.unit.*";                // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.tests.unit.files.*";          // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.tests.unit.models.*";         // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.tests.unit.walkthrough.*";    // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.utils.*";                     // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.utils.charts.*";              // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.utils.sanitizers.*";          // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.utils.security.*";            // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.utils.analyzers.*";           // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.validators.*";                // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.views.*";                     // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.views.attributetypes.*";      // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.views.charts.*";              // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.views.related.*";             // Not Coding Standard
    $instanceConfig['import'][] = "application.modules.$moduleName.widgets.*";                   // Not Coding Standard
}
?>

