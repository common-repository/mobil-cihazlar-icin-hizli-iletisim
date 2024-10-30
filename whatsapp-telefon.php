<?php
/*
   Plugin Name: Mobil Cihazlar İçin Hızlı İletişim
   Plugin URI: https://www.muratbutun.com/mobil-cihazlar-icin-hizli-iletisim.html
   Author URI: https://www.muratbutun.com/
   Version: 1.0
   Author: Murat Bütün
   Description: Mobil Cihazlardan Sitenize Giren Ziyaretçileriniz İçin Hızlı Telefon Araması ve WhatsApp'dan Mesaj Gönderme Butonu Ekler
   Text Domain: whatsapp-telefon
   License: GPLv3
  */
$WhatsappTelefon_minimalRequiredPhpVersion = '5.0';
function WhatsappTelefon_noticePhpVersionWrong()
{
global $WhatsappTelefon_minimalRequiredPhpVersion;
echo '<div class="updated fade">' . __('Error: plugin "Mobil Cihazla İçin Hızlı İletişim" requires a newer version of PHP to be running.', 'whatsapp-telefon') . '<br/>' . __('Minimal version of PHP required: ', 'whatsapp-telefon') . '<strong>' . $WhatsappTelefon_minimalRequiredPhpVersion . '</strong>' . '<br/>' . __('Your server\'s PHP version: ', 'whatsapp-telefon') . '<strong>' . phpversion() . '</strong>' . '</div>';
}
function WhatsappTelefon_PhpVersionCheck()
{
global $WhatsappTelefon_minimalRequiredPhpVersion;
if (version_compare(phpversion(), $WhatsappTelefon_minimalRequiredPhpVersion) < 0) {
add_action('admin_notices', 'WhatsappTelefon_noticePhpVersionWrong');
return false;
}
return true;
}
function WhatsappTelefon_i18n_init()
{
$pluginDir = dirname(plugin_basename(__FILE__));
load_plugin_textdomain('whatsapp-telefon', false, $pluginDir . '/languages/');
}
add_action('plugins_loadedi', 'WhatsappTelefon_i18n_init');
if (WhatsappTelefon_PhpVersionCheck()) {
include_once('whatsapp-telefon_init.php');
WhatsappTelefon_init(__FILE__);
}
function whatsapp_telefon()
{
if (wp_is_mobile()) {
?>
<script src="https://use.fontawesome.com/de86a4f404.js">
</script>
<?php
$telefon = get_option('WhatsappTelefon_Plugin_mrtbtnTelefon');
?>
<?php
$whatsapp = get_option('WhatsappTelefon_Plugin_mrtbtnWhatsapp');
?>
<?php
$mesaj = get_option('WhatsappTelefon_Plugin_mrtbtnmesaj');
?>
<div id="hizliiletisim">
  <ul>
    <li>
      <a href="tel://<?php
               echo $telefon;
               ?>">
        <i class="fa fa-phone" aria-hidden="true">
        </i>
      </a>
    </li>
    <li>
      <a href="whatsapp://send?phone=++9<?php
               echo $whatsapp;
               ?>​⁠​​⁠​&text=<?php
               echo $mesaj;
               ?>​⁠​​⁠​">
        <i class="fa fa-whatsapp" aria-hidden="true">
        </i>
      </a>
    </li>
  </ul>
</div>
<?php
}
?>
<?php
}
add_action('wp_head', 'whatsapp_telefon');