<?php
function WhatsappTelefon_init($file)
{
require_once('WhatsappTelefon_Plugin.php');
$aPlugin = new WhatsappTelefon_Plugin();
if (!$aPlugin->isInstalled()) {
$aPlugin->install();
} else {
$aPlugin->upgrade();
}
$aPlugin->addActionsAndFilters();
if (!$file) {
$file = __FILE__;
}
register_activation_hook($file, array(
&$aPlugin,
'activate'
));
register_deactivation_hook($file, array(
&$aPlugin,
'deactivate'
));
}