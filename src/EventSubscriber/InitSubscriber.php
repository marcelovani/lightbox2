<?php /**
 * @file
 * Contains \Drupal\lightbox2\EventSubscriber\InitSubscriber.
 */

namespace Drupal\lightbox2\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InitSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [KernelEvents::REQUEST => ['onEvent', 0]];
  }

  public function onEvent() {
    if (lightbox2_exclude_these_paths() != 1) {
      lightbox2_add_files();
    }
  }

}
