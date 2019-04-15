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
    $this->
      ->importPages()
      ->importBlockContent();
  }

  /**
   * Imports pages.
   *
   * @return $this
   */
  protected function importPages() {
    /*
    if (($handle = fopen($this->moduleHandler->getModule('fflch_fakecontent')->getPath() . '/default_content/pages.csv', "r")) !== FALSE) {
      $headers = fgetcsv($handle);
      $uuids = [];
      while (($data = fgetcsv($handle)) !== FALSE) {
        $data = array_combine($headers, $data);

        // Prepare content.
        $values = [
          'type' => 'page',
          'title' => $data['title'],
          'moderation_state' => 'published',
        ];
        // Fields mapping starts.
        // Set Body Field.
        if (!empty($data['body'])) {
          $values['body'] = [['value' => $data['body'], 'format' => 'basic_html']];
        }
        // Set node alias if exists.
        if (!empty($data['slug'])) {
          $values['path'] = [['alias' => '/' . $data['slug']]];
        }
        // Set article author.
        if (!empty($data['author'])) {
          $values['uid'] = $this->getUser($data['author']);
        }

        // Create Node.
        $node = $this->entityTypeManager->getStorage('node')->create($values);
        $node->save();
        $uuids[$node->uuid()] = 'node';
      }
      $this->storeCreatedContentUuids($uuids);
      fclose($handle);
    }
    return $this;
    */
  }

  /**
   * Imports block content entities.
   *
   * @return $this
   */
  protected function importBlockContent() {
    /*
    $module_path = $this->moduleHandler->getModule('fflch_fakecontent')->getPath();
    $block_content_entities = [
      'umami_home_banner' => [
        'uuid' => '9aadf4a1-ded6-4017-a10d-a5e043396edf',
        'info' => 'Umami Home Banner',
        'type' => 'banner_block',
        'field_title' => [
          'value' => 'Super easy vegetarian pasta bake',
        ],
        'field_content_link' => [
          'uri' => 'internal:' . call_user_func(function () {
            $nodes = $this->entityTypeManager->getStorage('node')->loadByProperties(['title' => 'Super easy vegetarian pasta bake']);
            $node = reset($nodes);
            return $this->aliasManager->getAliasByPath('/node/' . $node->id());
          }),
          'title' => 'View recipe',
        ],
        'field_summary' => [
          'value' => 'A wholesome pasta bake is the ultimate comfort food. This delicious bake is super quick to prepare and an ideal midweek meal for all the family.',
        ],
        'field_banner_image' => [
          'target_id' => $this->createFileEntity($module_path . '/default_content/images/veggie-pasta-bake-hero-umami.jpg'),
          'alt' => 'Mouth watering vegetarian pasta bake with rich tomato sauce and cheese toppings',
        ],
      ],
      'umami_recipes_banner' => [
        'uuid' => '4c7d58a3-a45d-412d-9068-259c57e40541',
        'info' => 'Umami Recipes Banner',
        'type' => 'banner_block',
        'field_title' => [
          'value' => 'Vegan chocolate and nut brownies',
        ],
        'field_content_link' => [
          'uri' => 'internal:' . call_user_func(function () {
            $nodes = $this->entityTypeManager->getStorage('node')->loadByProperties(['title' => 'Vegan chocolate and nut brownies']);
            $node = reset($nodes);
            return $this->aliasManager->getAliasByPath('/node/' . $node->id());
          }),
          'title' => 'View recipe',
        ],
        'field_summary' => [
          'value' => 'These sumptuous brownies should be gooey on the inside and crisp on the outside. A perfect indulgence!',
        ],
        'field_banner_image' => [
          'target_id' => $this->createFileEntity($module_path . '/default_content/images/vegan-brownies-hero-umami.jpg'),
          'alt' => 'A stack of chocolate and pecan brownies, sprinkled with pecan crumbs and crushed walnut, fresh out of the oven',
        ],
      ],
      'umami_disclaimer' => [
        'uuid' => '9b4dcd67-99f3-48d0-93c9-2c46648b29de',
        'info' => 'Umami disclaimer',
        'type' => 'disclaimer_block',
        'field_disclaimer' => [
          'value' => '<strong>Umami Magazine & Umami Publications</strong> is a fictional magazine and publisher for illustrative purposes only.',
          'format' => 'basic_html',
        ],
        'field_copyright' => [
          'value' => '&copy; 2018 Terms & Conditions',
          'format' => 'basic_html',
        ],
      ],
      'umami_footer_promo' => [
        'uuid' => '924ab293-8f5f-45a1-9c7f-2423ae61a241',
        'info' => 'Umami footer promo',
        'type' => 'footer_promo_block',
        'field_title' => [
          'value' => 'Umami Food Magazine',
        ],
        'field_summary' => [
          'value' => 'Skills and know-how. Magazine exclusive articles, recipes and plenty of reasons to get your copy today.',
        ],
        'field_content_link' => [
          'uri' => 'internal:' . call_user_func(function () {
            $nodes = $this->entityTypeManager->getStorage('node')->loadByProperties(['title' => 'About Umami']);
            $node = reset($nodes);
            return $this->aliasManager->getAliasByPath('/node/' . $node->id());
          }),
          'title' => 'Find out more',
        ],
        'field_promo_image' => [
          'target_id' => $this->createFileEntity($module_path . '/default_content/images/umami-bundle.png'),
          'alt' => '3 issue bundle of the Umami food magazine',
        ],
      ],
    ];

    // Create block content.
    foreach ($block_content_entities as $values) {
      $block_content = $this->entityTypeManager->getStorage('block_content')->create($values);
      $block_content->save();
      $this->storeCreatedContentUuids([$block_content->uuid() => 'block_content']);
    }
    return $this;
    */
  }

  /**
   * Deletes any content imported by this module.
   *
   * @return $this
   */
  public function deleteImportedContent() {
    /*
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
    */
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
    /*
    $uri = $this->fileUnmanagedCopy($path);
    $file = $this->entityTypeManager->getStorage('file')->create([
      'uri' => $uri,
      'status' => 1,
    ]);
    $file->save();
    $this->storeCreatedContentUuids([$file->uuid() => 'file']);
    return $file->id();
    */
  }

  /**
   * Stores record of content entities created by this import.
   *
   * @param array $uuids
   *   Array of UUIDs where the key is the UUID and the value is the entity
   *   type.
   */
  protected function storeCreatedContentUuids(array $uuids) {
    /*
    $uuids = $this->state->get('fflch_fakecontent_uuids', []) + $uuids;
    $this->state->set('fflch_fakecontent_uuids', $uuids);
    */
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
    /*
    $filename = basename($path);
    return file_unmanaged_copy($path, 'public://' . $filename, FILE_EXISTS_REPLACE);
    */
  }

}
