<?php

namespace Drupal\kelp;

/**
 * Kelp Service class.
 */
class KelpService
{

  /**
   * Constructor.
   */
  public function __construct()
  {
  }

  /**
   * Helper: Check if field exists and is not empty.
   *
   * @param object $entity
   *   Drupal Entity supporting fields.
   * @param mixed $field_name
   *   Either a string of a field name or an array of field names.
   *
   * @return bool
   *   TRUE if all field_names are valid on the entity, FALSE otherwise.
   */
  public function fieldCheck($entity, $field_name)
  {
    $list = is_array($field_name) ? $field_name : [$field_name];

    foreach ($list as $item) {
      if (!$entity->hasField($item) || $entity->{$item}->isEmpty()) {
        return FALSE;
      }
    }

    return TRUE;
  }

  /**
   * Helper function to generate link properties from a link field.
   *
   * @param \Drupal\Core\Link $link
   *   The link object containing the URL and other properties.
   * @param array $options
   *   (Optional) An array of options for customizing the link properties.
   *                      - 'title': The default link title to use if the provided link has no title.
   *                      - 'modifiers': An array of CSS class modifiers to apply to the link.
   *
   * @return array
   *   An array containing the link properties:
   *               - 'url': The URL of the link.
   *               - 'title': The link title (default or provided).
   *               - 'target': The link target ('_blank' for external links, '_self' for internal links).
   *               - 'aria_label': The ARIA label for accessibility, if provided.
   *               - 'modifiers': An array of CSS class modifiers for styling.
   */
  public function linkHelper($link, $options = [])
  {
    $options = array_merge([
      'title' => 'Learn More',
      'modifiers' => [],
    ], $options);

    $properties = $link->getProperties();
    $aria_label = $properties['options']->getValue()['attributes']['aria-label'] ?? FALSE;

    $link_output = [
      'url' => $link->getUrl()->toString(),
      'title' => $link->get('title')->getString() ?: $options['title'],
      'target' => $link->isExternal() ? '_blank' : '_self',
      'aria_label' => $aria_label,
      'modifiers' => $options['modifiers'],
    ];

    return $link_output;
  }

  /**
   * Helper function to extract the YouTube video ID from a given URL.
   *
   * @param string $source
   *   The YouTube video URL from which the ID needs to be extracted.
   *
   * @return string|false
   *   The extracted YouTube video ID if found, or FALSE if not found.
   */
  public function youtubeVideoId($source)
  {
    $pattern = '/^(?:(?:(?:https?:)?\/\/)?(?:www.)?(?:youtu(?:be.com|.be))\/(?:watch\?v\=|v\/|embed\/)?([\w\-]+))/is';

    $matches = [];
    preg_match($pattern, $source, $matches);

    return $matches[1] ?? FALSE;
  }

  /**
   * Helper function to convert a string into a machine-friendly format with an optional separator.
   *
   * @param string $text
   *   The input string to be transformed.
   * @param string $separator
   *   (Optional) The character used to separate words in the resulting string.
   *
   * @return string
   *   The machine-friendly string with the specified separator.
   */
  public function machinify($text, $separator = '_')
  {
    $new_value = preg_replace('/[^a-z0-9_]+/', $separator, strtolower(trim($text)));
    return preg_replace('/' . $separator . '+/', $separator, $new_value);
  }
}
