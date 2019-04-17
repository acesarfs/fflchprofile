<?php

namespace Drupal\fflch_fakecontent;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Path\AliasManagerInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Component\Utility\Html;

/**
 * Defines a helper class for importing default content.
 *
 * @internal
 *   This code is only for use to import Content.
 */
class InstallHelper implements ContainerInjectionInterface {

  /**
   * The path alias manager.
   *
   * @var \Drupal\Core\Path\AliasManagerInterface
   */
  protected $aliasManager;

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * State.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Constructs a new InstallHelper object.
   *
   * @param \Drupal\Core\Path\AliasManagerInterface $aliasManager
   *   The path alias manager.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Entity type manager.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   Module handler.
   * @param \Drupal\Core\State\StateInterface $state
   *   State service.
   */
  public function __construct(AliasManagerInterface $aliasManager, EntityTypeManagerInterface $entityTypeManager, ModuleHandlerInterface $moduleHandler, StateInterface $state) {
    $this->aliasManager = $aliasManager;
    $this->entityTypeManager = $entityTypeManager;
    $this->moduleHandler = $moduleHandler;
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('path.alias_manager'),
      $container->get('entity_type.manager'),
      $container->get('module_handler'),
      $container->get('state')
    );
  }

  /**
   * Imports default contents.
   */
  public function importContent() {
    $this->importPages()
      ->importBlockContent();
  }

  /**
   * Imports pages.
   *
   * @return $this
   */
  protected function importPages() {

    $module_path = $this->moduleHandler->getModule('fflch_fakecontent')->getPath();
    $fflch_image = $this->createFileEntity($module_path . '/default_content/fflch.jpg');

    $file = $module_path .'/default_content/frontpage.html';
    $body = file_get_contents($file);
    $body = str_replace("__fflch_image__", $fflch_image, $body);
    $uuids = [];

    // Prepare content.
    $values = [
        'type' => 'page',
        'title' => 'Sobre',
        'moderation_state' => 'published',
    ];

    // Set Body Field.
    $values['body'] = [['value' => $body, 'format' => 'full_html']];
     
    // Set article author.
    $values['uid'] = 1;
       
    // Create Node.
    $node = $this->entityTypeManager->getStorage('node')->create($values);
    $node->save();
    $uuids[$node->uuid()] = 'node';
      
    $this->storeCreatedContentUuids($uuids);
    
    return $this;
  }

  /**
   * Imports block content entities.
   *
   * @return $this
   */
  protected function importBlockContent() {
    $module_path = $this->moduleHandler->getModule('fflch_fakecontent')->getPath();
    $logo_image = $this->createFileEntity($module_path . '/default_content/logo.png');
    $usp_image = $this->createFileEntity($module_path . '/default_content/usp.png');

    $file = $module_path .'/default_content/block.logo.html';
    $body = file_get_contents($file);
    $body = str_replace("__logo_image__", $logo_image, $body);
    $body = str_replace("__usp_image__", $usp_image, $body);

    $block = [
        'uuid' => '9aadf4a1-ded6-4017-a10d-a5e043396edf',
        'info' => 'logo',
        'type' => 'basic',
        'title' => [
          'value' => 'logo',
         ],
        'body' => [
          'value' => $body,
          'format' => 'full_html'
         ]
    ];

    // Create block content.
    $block_content = $this->entityTypeManager->getStorage('block_content')->create($block);
    $block_content->save();
    $this->storeCreatedContentUuids([$block_content->uuid() => 'block_content']);
    return $this;
  }

  /**
   * Deletes any content imported by this module.
   *
   * @return $this
   */
  public function deleteImportedContent() {
    $uuids = $this->state->get('fflch_fakecontent_uuids', []);
    $by_entity_type = array_reduce(array_keys($uuids), function ($carry, $uuid) use ($uuids) {
      $entity_type_id = $uuids[$uuid];
      $carry[$entity_type_id][] = $uuid;
      return $carry;
    }, []);
    foreach ($by_entity_type as $entity_type_id => $entity_uuids) {
      $storage = $this->entityTypeManager->getStorage($entity_type_id);
      $entities = $storage->loadByProperties(['uuid' => $entity_uuids]);
      $storage->delete($entities);
    }
    return $this;
  }


  /**
   * Creates a file entity based on an image path.
   *
   * @param string $path
   *   Image path.
   *
   * @return int
   *   File ID.
   */
  protected function createFileEntity($path) {
    $uri = $this->fileUnmanagedCopy($path);
    $file = $this->entityTypeManager->getStorage('file')->create([
      'uri' => $uri,
      'status' => 1,
    ]);
    $file->save();
    $this->storeCreatedContentUuids([$file->uuid() => 'file']);
    //return $file->id();
    return file_url_transform_relative(file_create_url($file->getFileUri()));
  }

  /**
   * Stores record of content entities created by this import.
   *
   * @param array $uuids
   *   Array of UUIDs where the key is the UUID and the value is the entity
   *   type.
   */
  protected function storeCreatedContentUuids(array $uuids) {
    $uuids = $this->state->get('fflch_fakecontent_uuids', []) + $uuids;
    $this->state->set('fflch_fakecontent_uuids', $uuids);
    
  }

  /**
   * Wrapper around file_unmanaged_copy().
   *
   * @param string $path
   *   Path to image.
   *
   * @return string|false
   *   The path to the new file, or FALSE in the event of an error.
   */
  protected function fileUnmanagedCopy($path) {
    $filename = basename($path);
    return file_unmanaged_copy($path, 'public://' . $filename, FILE_EXISTS_REPLACE);
  }

}
