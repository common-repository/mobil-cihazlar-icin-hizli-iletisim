<?php
include_once('WhatsappTelefon_LifeCycle.php');
class WhatsappTelefon_Plugin extends WhatsappTelefon_LifeCycle
{
public function getOptionMetaData()
{
return array(
'mrtbtnTelefon' => array(
__('Aranmasını İstediğiniz Telefon Numarası', 'my-awesome-plugin')
),
'mrtbtnWhatsapp' => array(
__('WhatsApp Mesajlarının Geleceği Telefon Numarası', 'my-awesome-plugin')
),
'mrtbtnFont' => array(
__('Temanız Font Awesome Destekliyor mu? ', 'my-awesome-plugin'),
'Evet',
'Hayır'
),
'mrtbtnmesaj' => array(
__('İlk WhatsApp Mesajın', 'my-awesome-plugin')
)
);
}
protected function initOptions()
{
$options = $this->getOptionMetaData();
if (!empty($options)) {
foreach ($options as $key => $arr) {
if (is_array($arr) && count($arr > 1)) {
$this->addOption($key, $arr[1]);
}
}
}
}
public function getPluginDisplayName()
{
return 'Mobil İletişim';
}
protected function getMainPluginFileName()
{
return 'whatsapp-telefon.php';
}
protected function installDatabaseTables()
{
}
protected function unInstallDatabaseTables()
{
}
public function upgrade()
{
}
public function addActionsAndFilters()
{
add_action('admin_menu', array(
&$this,
'addSettingsSubMenuPage'
));
wp_enqueue_style('my-style', plugins_url('/css/style.css', __FILE__));
}
}