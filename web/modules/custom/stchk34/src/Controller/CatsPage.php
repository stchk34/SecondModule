<?php

namespace Drupal\stchk34\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;

/**
 * Provides route for our custom module.
 *
 * @method database()
 */
class CatsPage extends ControllerBase {

  /**
   * Getting form from Catsform.
   */
  public function content() {
    $form = \Drupal::formBuilder()->getForm('\Drupal\stchk34\Form\CatsForm');
    return [
      '#theme' => 'cat_page',
      '#markup' => 'Hello! You can add review about my company.',
      '#form' => $form,
      '#list' => $this->getCatsInfo(),
    ];
  }

  /**
   * Get database.
   */
  public function getCatsInfo(): array {
    $current_user = \Drupal::currentUser();
    $roles = $current_user->getRoles();
    $admin = "administrator";
    $query = \Drupal::database();
    $result = $query->select('stchk34', 's')
      ->fields('s', ['name', 'email', 'image', 'photo', 'date', 'phone', 'message', 'id'])
      ->orderBy('date', 'DESC')
      ->execute()->fetchAll();
    $data = [];

    foreach ($result as $row) {
      if ($row->image != NULL) {
        $image = [
          '#theme' => 'image',
          '#uri' => File::load($row->image)->getFileUri(),
          '#alt' => 'Cat',
          '#title' => 'Cat',
          '#width' => 60,
          '#height' => 60,
        ];
      }
      else {
        $image = [
          '#theme' => 'image',
          '#uri' => '/modules/custom/stchk34/img/default_image.png',
          '#attributes' => [
            'class' => 'image',
            'alt' => 'avatar',
            'width' => 60,
            'height' => 60,
          ],
        ];
      }
      if ($row->photo != NULL) {
        $photo = [
          '#theme' => 'image',
          '#uri' => File::load($row->photo)->getFileUri(),
          '#attributes' => [
            'class' => 'review-image',
            'alt' => 'Image',
            'width' => 150,
            'height' => 150,
          ],
        ];
      }
      $variable = [
        'name' => $row->name,
        'image' => [
          'data' => $image,
        ],
        'date' => date('d-m-Y H:i:s', $row->date),
        'email' => $row->email,
        'phone' => $row->phone,
        'message' => $row->message,
        'photo' => [
          'data' => $photo,
        ],
      ];

      if (in_array($admin, $roles)) {
        $url = Url::fromRoute('delete_form', ['id' => $row->id]);
        $url_edit = Url::fromRoute('edit_form', ['id' => $row->id]);
        $project_link = [
          '#title' => 'Delete',
          '#type' => 'link',
          '#url' => $url,
          '#attributes' => [
            'class' => ['use-ajax'],
            'data-dialog-type' => 'modal',
          ],
          '#attached' => [
            'library' => ['core/drupal.dialog.ajax'],
          ],
        ];
        $link_edit = [
          '#title' => 'Edit',
          '#type' => 'link',
          '#url' => $url_edit,
          '#attributes' => [
            'class' => ['use-ajax'],
            'data-dialog-type' => 'modal',
          ],
          '#attached' => [
            'library' => ['core/drupal.dialog.ajax'],
          ],
        ];
        $variable['link'] = [
          'data' => [
            "#theme" => 'operations',
            'delete' => $project_link,
            'edit' => $link_edit,
          ],
        ];
      }
      $data[] = $variable;
    }
    $build['table'] = [
      '#type' => 'table',
      '#rows' => $data,
    ];
    return $build;
  }

  /**
   * @param $id
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function delete($id): AjaxResponse {
    $response = new AjaxResponse();

    $delete_form = \Drupal::formBuilder()->getForm('Drupal\stchk34\Form\DeleteForm', $id);
    $response->addCommand(new OpenModalDialogCommand(
          'Delete',
          $delete_form,
          [
            'width' => 350,
          ]
      ));

    return $response;
  }

  /**
   * Return modal window with edit form.
   */
  public function edit($id): AjaxResponse {
    $response = new AjaxResponse();

    $conn = $this->database()->select('stchk34', 's');
    $conn->fields('s', ['id', 'name', 'email', 'photo', 'phone', 'message']);
    $conn->condition('id', $id);
    $results = $conn->execute()->fetchAssoc();

    $edit_form = $this->formBuilder()->getForm('Drupal\stchk34\Form\CatsForm', $results);
    $response->addCommand(new OpenModalDialogCommand('Edit', $edit_form, ['width' => 500]));

    return $response;
  }

}
