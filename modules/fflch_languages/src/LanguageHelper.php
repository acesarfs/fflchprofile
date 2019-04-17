<?php

namespace Drupal\fflch_languages;

use Drupal\language\ConfigurableLanguageManagerInterface;
use Drupal\language\Entity\ConfigurableLanguage;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

class LanguageHelper {

  /**
   * The config.factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * The language_manager service.
   *
   * @var \Drupal\language\ConfigurableLanguageManagerInterface
   */
  protected $languageManager;

  /**
   * The module_handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  public function __construct(
    ConfigFactoryInterface $configFactory,
    ModuleHandlerInterface $moduleHandler,
    ConfigurableLanguageManagerInterface $languageManager
  ) {
    $this->configFactory = $configFactory;
    $this->languageManager = $languageManager;
    $this->moduleHandler = $moduleHandler;
  }

public function doConfig(){

  $langcodes = ['en','pt-br','es'];

  foreach ($langcodes as $langcode) {
   
      $language = ConfigurableLanguage::createFromLangcode($langcode);
      $language->save();

      // Download and import translations for the newly added language if
      // interface translation is enabled.
      if ($this->moduleHandler->moduleExists('locale')) {
        module_load_include('fetch.inc', 'locale');
        $options = _locale_translation_default_update_options();
        if ($batch = locale_translation_batch_update_build([], [$langcode], $options)) {
          batch_set($batch);
          $batch =& batch_get();
          $batch['progressive'] = FALSE;

          // Process the batch.
          drush_backend_batch_process();
        }
      }
  }
}
