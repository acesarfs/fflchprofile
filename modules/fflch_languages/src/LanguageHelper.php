<?php

namespace Drupal\fflch_languages;

use Drupal\language\ConfigurableLanguageManagerInterface;
use Drupal\language\Entity\ConfigurableLanguage;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\State\StateInterface;

class LanguageHelper implements ContainerInjectionInterface {

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
    ConfigurableLanguageManagerInterface $languageManager,
    StateInterface $state
  ) {
    $this->configFactory = $configFactory;
    $this->moduleHandler = $moduleHandler;
    $this->languageManager = $languageManager;
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('module_handler'),
      $container->get('language_manager'),
      $container->get('state')
    );
  }

  // copied form drush_languages, class: DrushLanguageCliService
  public function doConfig(){

    $langcodes = ['en','pt-br','es'];
   
    foreach ($langcodes as $langcode) {

      $languages = $this->languageManager->getLanguages();

      // Do not re-add existing languages.
      if (isset($languages[$langcode])) {
        continue;
      }

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
         \Drupal::service('batch.storage')->create($batch);
          _batch_process();
        }
      }
    }
    $this->configFactory->getEditable('system.site')->set('default_langcode', 'pt-br')->save();
    $this->languageManager->reset();
  }

}
