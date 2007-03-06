<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

  $osC_DirectoryListing = new osC_DirectoryListing('includes/modules/statistics');
  $osC_DirectoryListing->setIncludeDirectories(false);
  $osC_DirectoryListing->setCheckExtension('php');
?>

<h1><?php echo osc_link_object(osc_href_link(FILENAME_DEFAULT, $osC_Template->getModule()), $osC_Template->getPageTitle()); ?></h1>

<?php
  if ( $osC_MessageStack->size($osC_Template->getModule()) > 0 ) {
    echo $osC_MessageStack->output($osC_Template->getModule());
  }
?>

<table border="0" width="100%" cellspacing="0" cellpadding="2" class="dataTable">
  <thead>
    <tr>
      <th><?php echo TABLE_HEADING_MODULES; ?></th>
    </tr>
  </thead>
  <tbody>

<?php
  $installed_modules = array();

  foreach ( $osC_DirectoryListing->getFiles() as $file ) {
    include('includes/modules/statistics/' . $file['name']);

    $class = 'osC_Statistics_' . str_replace(' ', '_', ucwords(str_replace('_', ' ', substr($file['name'], 0, strrpos($file['name'], '.')))));

    if ( class_exists($class) ) {
      $module = new $class();
?>

    <tr onmouseover="rowOverEffect(this);" onmouseout="rowOutEffect(this);">
      <td><?php echo osc_link_object(osc_href_link_admin(FILENAME_DEFAULT, $osC_Template->getModule() . '&module=' . substr($file['name'], 0, strrpos($file['name'], '.'))), $module->getIcon() . '&nbsp;' . $module->getTitle()); ?></td>
    </tr>

<?php
    }
  }
?>

  </tbody>
</table>

<p><?php echo TEXT_MODULE_DIRECTORY . ' ' . realpath(dirname(__FILE__) . '/../../../includes/modules/statistics'); ?></p>