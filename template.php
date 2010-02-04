<?php
// $Id$


/**
 * Override or insert variables into the html template.
 */
function busy_process_html(&$vars) {
  // Hook into color.module
  if (module_exists('color')) {
    _color_html_alter($vars);
  }
}

/**
 * Override or insert variables into the page template.
 */
function busy_process_page(&$vars) {
  // Hook into color.module
  if (module_exists('color')) {
    _color_page_alter($vars);
  }
}

/**
 * Override or insert variables into the page template.
 */
function busy_preprocess_page(&$vars) {
  // Prepare header.
  $site_fields = array();
  if (!empty($vars['site_name'])) {
    $site_fields[] = check_plain($vars['site_name']);
  }
  if (!empty($vars['site_slogan'])) {
    $site_fields[] = check_plain($vars['site_slogan']);
  }
  $vars['site_title'] = implode(' ', $site_fields);
  if (!empty($site_fields)) {
    $site_fields[0] = '<span>' . $site_fields[0] . '</span>';
  }
  $vars['site_html'] = implode(' ', $site_fields);
  
  // Set a variable for the site name title and logo alt attributes text.
  $slogan_text = filter_xss_admin(variable_get('site_slogan', ''));
  $site_name_text = filter_xss_admin(variable_get('site_name', 'Drupal'));
  $vars['site_name_and_slogan'] = $site_name_text . ' ' . $slogan_text;
}

/**
 * Override or insert variables into the node template.
 */
function busy_preprocess_node(&$vars) {
  if ($vars['page'] && isset($vars['content']['links']['comment'])) {
    if (isset($vars['content']['links']['comment']['#links']['comment_add'])) {
      $vars['content']['links']['comment']['#links']['comments_count'] = $vars['content']['links']['comment']['#links']['comment_add'];
    }
    $vars['content']['links']['comment']['#links']['comments_count']['title'] = t('@num_comments', array('@num_comments' => format_plural($vars['comment_count'], '1 comment', '@count comments')));
    $vars['content']['links']['comment']['#links']['comments_count']['attributes'] = array();
  }
  if (theme_get_setting('toggle_node_user_picture', 'busy') && $vars['picture']) {
    $vars['classes_array'][] = 'node-with-author-picture';
  }
}

/**
 * Process variables for comment.tpl.php.
 *
 * @see comment.tpl.php
 */
function busy_preprocess_comment(&$vars) {
  if (theme_get_setting('toggle_comment_user_picture', 'busy') && $vars['picture']) {
    $vars['classes_array'][] = 'comment-with-author-picture';
  }
}

/**
 * Returns renderable local tasks.
 *
 * @ingroup themeable
 */
function busy_menu_local_tasks() {
  $output = array();

  if ($primary = menu_primary_local_tasks()) {
    foreach ($primary as $key => $task) {
      if (is_numeric($key) && $key == (count($primary) - 1)) {
        $primary[$key]['#last'] = TRUE;
      }
    }
    $primary['#prefix'] = '<ul class="tabs primary">';
    $primary['#suffix'] = '</ul>';
    $output[] = $primary;
  }
  if ($secondary = menu_secondary_local_tasks()) {
    foreach ($secondary as $key => $task) {
      if (is_numeric($key) && $key == (count($secondary) - 1)) {
        $secondary[$key]['#last'] = TRUE;
      }
    }
    $secondary['#prefix'] = '<ul class="tabs secondary">';
    $secondary['#suffix'] = '</ul>';
    $output[] = $secondary;
  }

  return $output;
}

/**
 * Override html output of a menu tab.
 */
function busy_menu_local_task($variables) {
  $link = $variables['element']['#link'];
  $link_text = $link['title'];

  if (!empty($variables['element']['#active'])) {
    // Add text to indicate active tab for non-visual users.
    $active = '<span class="element-invisible">' . t('(active tab)') . '</span>';

    // If the link does not contain HTML already, check_plain() it now.
    // After we set 'html'=TRUE the link will not be sanitized by l().
    if (empty($link['localized_options']['html'])) {
      $link['title'] = check_plain($link['title']);
    }
    $link['localized_options']['html'] = TRUE;
    $link_text = t('!local-task-title !active', array('!local-task-title' => $link['title'], '!active' => $active));
  }

  $link_classes = array();
  if (!empty($variables['element']['#active'])) {
    $link_classes[] = 'active';
  }
  if (!empty($variables['element']['#last'])) {
    $link_classes[] = 'last';
  }
  return '<li' . ($link_classes ? ' class="' . implode(' ', $link_classes) . '"' : '') . '>' . l($link_text, $link['href'], $link['localized_options']) . "</li>\n";
}

/**
 * Format a query pager.
 *
 * Menu callbacks that display paged query results should call theme('pager') to
 * retrieve a pager control so that users can view other results.
 * Format a list of nearby pages with additional query results.
 *
 * @param $variables
 *   An associative array containing:
 *   - tags: An array of labels for the controls in the pager.
 *   - element: An optional integer to distinguish between multiple pagers on
 *     one page.
 *   - parameters: An associative array of query string parameters to append to
 *     the pager links.
 *   - quantity: The number of pages in the list.
 *
 * @return
 *   An HTML string that generates the query pager.
 *
 * @ingroup themeable
 */
function busy_pager($variables) {
  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];
  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.

  $li_first = theme('pager_first', array('text' => (isset($tags[0]) ? $tags[0] : t('« first')), 'element' => $element, 'parameters' => $parameters));
  $li_previous = theme('pager_previous', array('text' => (isset($tags[1]) ? $tags[1] : t('‹ previous')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => (isset($tags[3]) ? $tags[3] : t('next ›')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_last = theme('pager_last', array('text' => (isset($tags[4]) ? $tags[4] : t('last »')), 'element' => $element, 'parameters' => $parameters));

  if ($pager_total[$element] > 1) {
    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => array('pager-current'),
            'data' => $i,
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
          );
        }
      }
    }
    if ($li_first) {
      $items[] = array(
        'class' => array('pager-first'),
        'data' => $li_first,
      );
    }
    if ($li_previous) {
      $items[] = array(
        'class' => array('pager-previous'),
        'data' => $li_previous,
      );
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => array('pager-next'),
        'data' => $li_next,
      );
    }
    if ($li_last) {
      $items[] = array(
        'class' => array('pager-last'),
        'data' => $li_last,
      );
    }
    return '<h2 class="element-invisible">' . t('Pages') . '</h2>' . theme('item_list', array('items' => $items, 'title' => NULL, 'type' => 'ul', 'attributes' => array('class' => array('pager'))));
  }
}

/**
 * Override html output of fieldsets and add a wrapper element for fieldset
 * content.
 *
 */
function busy_fieldset($variables) {
  $element = $variables['element'];

  $output = '<fieldset' . drupal_attributes($element['#attributes']) . '>';
  if (!empty($element['#title'])) {
    $output .= '<legend>' . $element['#title'] . '</legend>';
  }
  $output .= '<div class="fieldset-content">' . "\n";
  if (!empty($element['#description'])) {
    $output .= '<div class="fieldset-description">' . $element['#description'] . '</div>';
  }
  $output .= $element['#children'];
  if (isset($element['#value'])) {
    $output .= $element['#value'];
  }
  $output .= "</div>\n</fieldset>\n";
  return $output;
}
