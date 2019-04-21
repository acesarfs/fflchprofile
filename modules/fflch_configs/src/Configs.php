<?php

namespace Drupal\fflch_configs;

use Drupal\language\ConfigurableLanguageManagerInterface;
use Drupal\language\Entity\ConfigurableLanguage;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\State\StateInterface;

use Drupal\user\Entity\Role;
use Drupal\user\RoleInterface;

class Configs implements ContainerInjectionInterface {

  protected $configFactory;
  protected $moduleHandler;
  protected $languageManager;
  protected $state;

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
   
  /*******************************************************************/
  /** Daqui para baixo estão as configurações obrigatórias na FFLCH **/
  public function doConfig(){
    $this->idiomas();
    $this->captcha();
    $this->user1();
    $this->permissions();
  }

  private function idiomas(){
    $langcodes = ['en','pt-br','es','fr'];
    foreach ($langcodes as $langcode) {
      $languages = $this->languageManager->getLanguages();
      if (isset($languages[$langcode])) {
        continue;
      }
      $language = ConfigurableLanguage::createFromLangcode($langcode);
      $language->save();
    }

    // pt-br como default
    $system_site = $this->configFactory->getEditable('system.site');
    $system_site->set('default_langcode', 'pt-br')->save();

    // remove prefixo pt-br da url
    $language_negotiation = $this->configFactory->getEditable('language.negotiation');
    $language_negotiation->set('url.prefixes.pt-br', '')->save();
    
    $this->languageManager->reset();
  }

  private function captcha(){
    $captcha_settings = $this->configFactory->getEditable('captcha.settings');
    $captcha_settings->set('default_challenge', 'image_captcha/Image')->save();
  }

  private function user1(){
    $user = \Drupal\user\Entity\User::load(1);
    $user->setUsername('fflch');
    $user->save();
  }

  private function permissions(){
    // Allow all users to use search.
    user_role_grant_permissions(RoleInterface::ANONYMOUS_ID, ['search content']);
    user_role_grant_permissions(RoleInterface::AUTHENTICATED_ID, ['search content']);

    //criar role fflch se ela não existir?
    $fflch = Role::load('fflch');
    $fflch->grantPermission('administer css assets injector');
    $fflch->save();
  }
}
