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
    $this->smtp();
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

  private function smtp(){
    $senha = file_get_contents("/var/aegir/.email.txt");
    $smtp_settings = $this->configFactory->getEditable('smtp.settings');
    $smtp_settings>set('smtp_username', 'noreply.fflch@usp.br')->save();
    $smtp_settings>set('smtp_password', $senha)->save();
    $smtp_settings>set('smtp_host', 'smtp.gmail.com')->save();
    $smtp_settings>set('smtp_port', '587')->save();
    $smtp_settings>set('smtp_protocol', 'tls')->save();
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

    # Aparência da administração mais agradável    
    $p1 = ['access toolbar', 'access administration pages',
           'view the administration theme', 'access in-place editing'];
    # Administração de conteúdo
    $p2 = ['bypass node access', 'access content overview', 'administer node display',
           'administer node fields','administer nodes', 'administer content types',
           'administer node form display','access taxonomy overview',
            'administer taxonomy','add content to books','administer book outlines'];

    # Campos condicionais
    $p3 = ['delete conditional fields', 'edit conditional fields',
           'view conditional fields'];

    # Configurações globais liberadas
    $p4 = ['Administer Inital Page', 'create url aliases'];

    # Administração de blocos, views e menu
    $p5 = ['administer blocks', 'administer block_content display',
           'administer block_content fields','administer views',
           'administer menu'];

    # Módulos e configurações liberados para uso
    $p6 = ['administer google analytics', 'administer css assets injector',
           'administer js assets injector'];

    # Editor de texto
    $p7 = ['use text format full_html'];

    # Temas liberados
    $p8 = ['administer themes', 'administer themes fflch_aegan'];

    # webform
    $p9 = ['administer webform', 'skip CAPTCHA'];

    # Revisões
    $p10 = ['revert all revisions','view all revisions'];
    
    $perms = array_merge($p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10);

    // Cri role se necessário
    $role = Role::load('fflch');
    if(empty($role)) {
      $role = Role::create(array('id' => 'fflch', 'label' => 'fflch'));
      $role->save(); 
    }

    // Remove permissões indevidas
    $currents = $role->getPermissions();
    foreach($currents as $p){
      if (!in_array($p, $perms)) {
        $role->revokePermission($p);
        $role->save();
      }
    }

    // Adiciona devidas permissões
    foreach($perms as $p){
      $role->grantPermission($p);
      $role->save();
    }

  }
}
