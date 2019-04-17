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

  // copied from drush_languages, class: DrushLanguageCliService
  public function doConfig(){

    $langcodes = ['en','pt-br','es','fr'];
    foreach ($langcodes as $langcode) {

      $languages = $this->languageManager->getLanguages();

      // Do not re-add existing languages.
      if (isset($languages[$langcode])) {
        continue;
      }

      $language = ConfigurableLanguage::createFromLangcode($langcode);
      $language->save();

    }
   
    $this->configFactory->getEditable('system.site')->set('default_langcode', 'pt-br')->save();
    $this->languageManager->reset();
  }

}
