<?php

namespace Drupal\fflch_audit\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class AuditController.
 */
class AuditController extends ControllerBase {

  /**
   *
   * @return string
   *   Return json string.
   */
  public function audit() {
    $reportManager = \Drupal::service('plugin.manager.site_audit_report');
    $reportDefinitions = $reportManager->getDefinitions();

    foreach ($reportDefinitions AS $reportDefinition) {
      $reports[] = $reportManager->createInstance($reportDefinition['id']);
    }
    foreach($reports as $report){
        print_r($report->report->getLabel());
    }
    die();
  }

}
