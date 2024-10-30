<?php
class WhatsappTelefon_OptionsManager
{
public function getOptionNamePrefix()
{
return get_class($this) . '_';
}
public function getOptionMetaData()
{
return array();
}
public function getOptionNames()
{
return array_keys($this->getOptionMetaData());
}
protected function initOptions()
{
}
protected function deleteSavedOptions()
{
$optionMetaData = $this->getOptionMetaData();
if (is_array($optionMetaData)) {
foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
$prefixedOptionName = $this->prefix($aOptionKey);
delete_option($prefixedOptionName);
}
}
}
public function getPluginDisplayName()
{
return get_class($this);
}
public function prefix($name)
{
$optionNamePrefix = $this->getOptionNamePrefix();
if (strpos($name, $optionNamePrefix) === 0) {
return $name;
}
return $optionNamePrefix . $name;
}
public function &unPrefix($name)
{
$optionNamePrefix = $this->getOptionNamePrefix();
if (strpos($name, $optionNamePrefix) === 0) {
return substr($name, strlen($optionNamePrefix));
}
return $name;
}
public function getOption($optionName, $default = null)
{
$prefixedOptionName = $this->prefix($optionName);
$retVal             = get_option($prefixedOptionName);
if (!$retVal && $default) {
$retVal = $default;
}
return $retVal;
}
public function deleteOption($optionName)
{
$prefixedOptionName = $this->prefix($optionName);
return delete_option($prefixedOptionName);
}
public function addOption($optionName, $value)
{
$prefixedOptionName = $this->prefix($optionName);
return add_option($prefixedOptionName, $value);
}
public function updateOption($optionName, $value)
{
$prefixedOptionName = $this->prefix($optionName);
return update_option($prefixedOptionName, $value);
}
public function getRoleOption($optionName)
{
$roleAllowed = $this->getOption($optionName);
if (!$roleAllowed || $roleAllowed == '') {
$roleAllowed = 'Administrator';
}
return $roleAllowed;
}
protected function roleToCapability($roleName)
{
switch ($roleName) {
case 'Super Admin':
return 'manage_options';
case 'Administrator':
return 'manage_options';
case 'Editor':
return 'publish_pages';
case 'Author':
return 'publish_posts';
case 'Contributor':
return 'edit_posts';
case 'Subscriber':
return 'read';
case 'Anyone':
return 'read';
}
return '';
}
public function isUserRoleEqualOrBetterThan($roleName)
{
if ('Anyone' == $roleName) {
return true;
}
$capability = $this->roleToCapability($roleName);
return current_user_can($capability);
}
public function canUserDoRoleOption($optionName)
{
$roleAllowed = $this->getRoleOption($optionName);
if ('Anyone' == $roleAllowed) {
return true;
}
return $this->isUserRoleEqualOrBetterThan($roleAllowed);
}
public function createSettingsMenu()
{
$pluginName = $this->getPluginDisplayName();
add_menu_page($pluginName . ' Plugin Settings', $pluginName, 'administrator', get_class($this), array(
&$this,
'settingsPage'
));
add_action('admin_init', array(
&$this,
'registerSettings'
));
}
public function registerSettings()
{
$settingsGroup  = get_class($this) . '-settings-group';
$optionMetaData = $this->getOptionMetaData();
foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
register_setting($settingsGroup, $aOptionMeta);
}
}
public function settingsPage()
{
if (!current_user_can('manage_options')) {
wp_die(__('You do not have sufficient permissions to access this page.', 'whatsapp-telefon'));
}
$optionMetaData = $this->getOptionMetaData();
if ($optionMetaData != null) {
foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
if (isset($_POST[$aOptionKey])) {
$this->updateOption($aOptionKey, $_POST[$aOptionKey]);
}
}
}
$settingsGroup = get_class($this) . '-settings-group';
?>
<div class="wrap">
  <h2>Mobil Cihazınız İçin Hızlı İletişim Eklentisi
  </h2>
  <form method="post" action="">
    <?php
settings_fields($settingsGroup);
?>
    <table class="plugin-options-table">
      <tbody>
        <?php
if ($optionMetaData != null) {
foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
$displayText = is_array($aOptionMeta) ? $aOptionMeta[0] : $aOptionMeta;
?>
        <tr valign="top">
          <th scope="row">
            <p>
              <label for="<?php
                          echo $aOptionKey;
                          ?>">
                <?php
echo $displayText;
?>
              </label>
            </p>
          </th>
          <td>
            <?php
$this->createFormControl($aOptionKey, $aOptionMeta, $this->getOption($aOptionKey));
?>
          </td>
        </tr>
        <?php
}
}
?>
      </tbody>
    </table>
    <p>
      <a href="http://www.muratbutun.com/" title="Wordpress ve WooCommerce Uzmanı" target="_blank" rel="nofollow">Geliştirme ve Destek İçin Tıklayınız
      </a>
    </p>
    <p class="submit">
      <input type="submit" class="button-primary"
             value="<?php
                    _e('Ayarları Kaydet', 'whatsapp-telefon');
                    ?>"/>
    </p>
  </form>
</div>
<?php
}
protected function createFormControl($aOptionKey, $aOptionMeta, $savedOptionValue)
{
if (is_array($aOptionMeta) && count($aOptionMeta) >= 2) {
$choices = array_slice($aOptionMeta, 1);
?>
<p>
  <select name="<?php
                echo $aOptionKey;
                ?>" id="<?php
                        echo $aOptionKey;
                        ?>">
    <?php
foreach ($choices as $aChoice) {
$selected = ($aChoice == $savedOptionValue) ? 'selected' : '';
?>
    <option value="<?php
                   echo $aChoice;
                   ?>" 
            <?php
    echo $selected;
    ?>>
    <?php
echo $this->getOptionValueI18nString($aChoice);
?>
    </option>
  <?php
}
?>
</select>
</p>
<?php
} else {
?>
<p>
  <input type="text" name="<?php
                           echo $aOptionKey;
                           ?>" id="<?php
                                   echo $aOptionKey;
                                   ?>"
         value="<?php
                echo esc_attr($savedOptionValue);
                ?>" size="50"/>
</p>
<?php
}
}
protected function getOptionValueI18nString($optionValue)
{
switch ($optionValue) {
case 'true':
return __('true', 'whatsapp-telefon');
case 'false':
return __('false', 'whatsapp-telefon');
case 'Administrator':
return __('Administrator', 'whatsapp-telefon');
case 'Editor':
return __('Editor', 'whatsapp-telefon');
case 'Author':
return __('Author', 'whatsapp-telefon');
case 'Contributor':
return __('Contributor', 'whatsapp-telefon');
case 'Subscriber':
return __('Subscriber', 'whatsapp-telefon');
case 'Anyone':
return __('Anyone', 'whatsapp-telefon');
}
return $optionValue;
}
protected function getMySqlVersion()
{
global $wpdb;
$rows = $wpdb->get_results('select version() as mysqlversion');
if (!empty($rows)) {
return $rows[0]->mysqlversion;
}
return false;
}
public function getEmailDomain()
{
$sitename = strtolower($_SERVER['SERVER_NAME']);
if (substr($sitename, 0, 4) == 'www.') {
$sitename = substr($sitename, 4);
}
return $sitename;
}
}