# Kelp
Kelp is a central place to keep Kanopi Drupal helper functions.



## `KelpHelpers::fieldCheck($entity, $field_name)`

This function checks if a field exists on a Drupal entity and is not empty.

```php
use Drupal\kelp\KelpHelpers;


// Example usage:
$node = \Drupal::entityTypeManager()->getStorage('node')->load(1);

if (KelpHelpers::fieldCheck($node, 'field_example')) {
  // The field 'field_example' exists and is not empty.
  // Your code here...
} else {
  // The field 'field_example' does not exist or is empty.
  // Handle accordingly...
}
```

## `KelpHelpers::linkHelper($link, $options = [])`

This function generates link properties from a link field.

```php
use Drupal\kelp\KelpHelpers;


// Example usage:
$link = $node->field_link->first(); // Assuming you have a link field
$linkProperties = KelpHelpers::linkHelper($link, ['title' => 'Find out more about kelp', 'modifiers' => ['btn', 'btn-primary']]);

// $linkProperties will contain an array with URL, title, target, etc.
dump($linkProperties);
```

## `KelpHelpers::youtubeVideoId($source)`

This function extracts the YouTube video ID from a given URL.

```php
use Drupal\kelp\KelpHelpers;


// Example usage:
$youtubeURL = 'https://www.youtube.com/watch?v=VIDEO_ID';
$videoId = KelpHelpers::youtubeVideoId($youtubeURL);

if ($videoId) {
  // $videoId will contain the extracted YouTube video ID.
  // Your code here...
} else {
  // The YouTube video ID was not found in the URL.
  // Handle accordingly...
}
```

## `KelpHelpers::machinify($text, $separator = '_')`

This function converts a string into a machine-friendly format with an optional separator.

```php
use Drupal\kelp\KelpHelpers;


// Example usage:
$text = "Convert This String";
$machineFriendlyText = KelpHelpers::machinify($text, '-');

// $machineFriendlyText will be "convert-this-string"
dump($machineFriendlyText);
```

## `KelpHelpers::getImageData($imageEntity)`

This function returns retuns an array of image related information.

```php
use Drupal\kelp\KelpHelpers;


// Example of an Image media entity on a node with the media
// image field field_media_image:
$node_media_image_entity = $node->get('field_media_collection_image')->entity->get('field_media_image');
$node_media_image_entity_data = KelpHelpers::getImageData($node_media_image_entity);

// Array of useful information about the image.
dump($node_media_image_entity_data);
```
