<?php

namespace Drupal\and;

use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\Url;

/**
 * Hook implementations for and.
 */
abstract class AndHooksBase {

  use StringTranslationTrait;

  /**
   * AndHooksBase constructor.
   *
   * @param \Drupal\Core\Extension\ModuleExtensionList $moduleExtensionList
   *   The module extension list.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   The module handler.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $translation
   *   The string translation.
   */
  public function __construct(
    private readonly ModuleExtensionList $moduleExtensionList,
    private readonly ModuleHandlerInterface $moduleHandler,
    private readonly TranslationInterface $translation,
  ) {
    $this->setStringTranslation($translation);
  }

  /**
   * Implements hook_help().
   */
  #[Hook('help')]
  public function help(
    string $route_name,
    RouteMatchInterface $route_match,
  ): string {
    $output = '';
    $name = explode('\\', get_class($this))[1];

    if ($route_name !== "help.page.$name") {
      return $output;
    }

    $info = $this->moduleExtensionList->getExtensionInfo($name);

    if (($name = $this->module()) === NULL) {
      foreach ($info['dependencies'] as $dependency) {
        $dependency = explode(':', $dependency);

        if ($dependency[0] === $dependency[1]) {
          $name = $dependency[0];

          break;
        }
      }
    }

    if (
      !empty($name) &&
      preg_match(
        '/^Provides a wrapper for the (.+) module(| and related)\.$/',
        $info['description'],
        $matches,
      )
    ) {
      $phrase = str_replace($matches[1], '@module', $matches[0]);

      if ($this->moduleHandler->hasImplementations('help', $name)) {
        $link = Link::createFromRoute(
          $matches[1],
          'help.page',
          ['name' => $name],
        );
      }
      else {
        $link = Link::fromTextAndUrl(
          $matches[1],
          Url::fromUri("https://www.drupal.org/project/$name"),
        );
      }

      $info = ['@module' => $link->toString()];
      $output = "<h3>{$this->t('About')}</h3><p>{$this->t($phrase, $info)}</p>";
    }

    return $output;
  }

  /**
   * Gets the module name.
   *
   * Return the machine name of the module being wrapped if it cannot be
   * automatically determined from the dependency list.
   */
  protected function module(): ?string {
    return NULL;
  }

}
