<?php

namespace Drupal\kelp;
use Drupal\File\FileInterface;

class KelpHelpers
{
  /**
   * Check if a field exists and is not empty.
   *
   * @param object $entity
   *   Drupal Entity supporting fields.
   * @param mixed $field_name
   *   Either a string of a field name or an array of field names.
   *
   * @return bool
   *   TRUE if all field_names are valid on the entity, FALSE otherwise.
   */
  public static function fieldCheck($entity, $field_name)
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
   * Generate link properties from a link field.
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
  public static function linkHelper($link, $options = [])
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
   * Extract the YouTube video ID from a given URL.
   *
   * @param string $source
   *   The YouTube video URL from which the ID needs to be extracted.
   *
   * @return string|false
   *   The extracted YouTube video ID if found, or FALSE if not found.
   */
  public static function youtubeVideoId($source)
  {
    $pattern = '/^(?:(?:(?:https?:)?\/\/)?(?:www.)?(?:youtu(?:be.com|.be))\/(?:watch\?v\=|v\/|embed\/)?([\w\-]+))/is';

    $matches = [];
    preg_match($pattern, $source, $matches);

    return $matches[1] ?? FALSE;
  }

  /**
   * Convert a string into a machine-friendly format with an optional separator.
   *
   * @param string $text
   *   The input string to be transformed.
   * @param string $separator
   *   (Optional) The character used to separate words in the resulting string.
   *
   * @return string
   *   The machine-friendly string with the specified separator.
   */
  public static function machinify($text, $separator = '_')
  {
    $new_value = preg_replace('/[^a-z0-9_]+/', $separator, strtolower(trim($text)));
    return preg_replace('/' . $separator . '+/', $separator, $new_value);
  }

  /**
   * Retrieves image-related information and generates a formatted array.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $field
   *   The field containing the image to retrieve information for.
   *
   * @return array
   *   An array containing image-related information including:
   *   - 'fid' (int): The File ID of the image.
   *   - 'src' (string): The URL of the image.
   *   - 'alt' (string): The alt text for the image, if available.
   *   - 'focal' (string): The focal point for background positioning.
   *   - 'css' (string): CSS style for background image and position.
   *   - 'info' (array): Information about the image, including:
   *     - 'image_size' (int): The file size of the image in bytes.
   *     - 'image_type' (string): The MIME type of the image.
   *     - 'image_width' (int): The width of the image in pixels.
   *     - 'image_height' (int): The height of the image in pixels.
   *   - 'uri' (string): The URI of the image file.
   */
  public static function getImageData($field) {
    $image_factory = \Drupal::service('image.factory');
    $file = $field->entity;
    if ($file instanceof FileInterface) {
      $fid = '';
      $image = '';
      $imageInfo = [];
      $css = '';
      $alt = '';
      $focalPoint = '50% 50%';
      $uri = '';

      if (isset($file->uri->value)) {
        $fid = $file->fid->getValue()[0]['value'];
        $uri = $file->uri->value;
        $image = \Drupal::service('file_url_generator')->generateAbsoluteString($uri);
        $image_info = $image_factory->get($file->getFileUri());
        $imageInfo['image_size'] = $image_info->getFileSize();
        $imageInfo['image_type'] = $file->getMimeType();
        $image_width = $image_info->getWidth();
        $image_height = $image_info->getHeight();
        $imageInfo['image_width'] = $image_width;
        $imageInfo['image_height'] = $image_height;
        // Alt tag.
        if (isset($field->getValue()[0]['alt'])) {
          $alt = $field->getValue()[0]['alt'];
        }
      }

      $css = 'background-image: url( ' . $image . ' ); background-position: ' . $focalPoint . ';';
      return [
        'fid' => $fid,
        'src' => $image,
        'alt' => $alt,
        'focal' => $focalPoint,
        'css' => $css,
        'info' => $imageInfo,
        'uri' => $uri,
      ];
    }
  }

}
