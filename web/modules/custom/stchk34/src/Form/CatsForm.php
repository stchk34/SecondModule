<?php

namespace Drupal\stchk34\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\MessageCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Our simple form class.
 *
 * @package Drupal\stchk34\Form
 */
class CatsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): CatsForm {
    $instance = parent::create($container);
    $instance->messenger = $container->get('messenger');
    $instance->database = $container->get('database');
    return $instance;
  }

  /**
   * Drupal\Core\Database defenition.
   *
   * @var \Drupal\Core\Database\Connection
   */
  public $database;

  /**
   * Returns a unique string identifying the form.
   *
   * The returned ID should be a unique string that can be a valid PHP function
   * name, since it's used in hook implementation names such as
   * hook_form_FORM_ID_alter().
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'stchk34';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames(): array {
    return ['stchk34.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your name:'),
      '#description' => $this->t('the minimum length of the name-2 and the maximum-100'),
      '#required' => TRUE,
    ];
    $form['image'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Your avatar:'),
      '#description' => $this->t('Valid extensions: jpeg, jpg, png. Max file size 2MB'),
      '#upload_validators' => [
        'file_validate_extensions' => ['jpeg jpg png'],
        'file_validate_size' => [2100000],
      ],
      '#upload_location' => 'public://stchk34/cats',
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Your email:'),
      '#required' => TRUE,
      '#description' => $this->t('Email names can only contain Latin letters, underscores, or hyphens'),
      '#ajax' => [
        'callback' => '::validateEmailAjax',
        'event' => 'input',
        'progress' => 'none',
      ],
    ];
    $form['phone'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your phone number:'),
      '#description' => $this->t('Only numbers'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::validatePhoneAjax',
        'event' => 'input',
        'progress' => 'none',
      ],
    ];
    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t("Message:"),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::validateMessageAjax',
        'event' => 'input',
        'progress' => 'none',
      ],
    ];
    $form['photo'] = [
      '#title' => 'Image for review:',
      '#type' => 'managed_file',
      '#multiple' => FALSE,
      '#description' => t('Valid extensions: jpeg, jpg, png. Max file size 5MB'),
      '#required' => FALSE,
      '#upload_location' => 'public://stchk34/cats',
      '#upload_validators'    => [
        'file_validate_extensions' => ['png jpg jpeg'],
        'file_validate_size' => [5 * 1024 * 1024],
      ],
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add review'),
      '#button_type' => 'primary',
      '#ajax' => [
        'callback' => '::ajaxSubmit',
      ],
    ];
    return $form;
  }

  /**
   * Form validation handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $valid = $this->validateEmail($form, $form_state);
    $validPhone = $this->validatePhone($form, $form_state);
    $validMessage = $this->validateMessage($form, $form_state);
    if (strlen($form_state->getValue('name')) < 2) {
      $form_state->setErrorByName('name', $this->t('Name is too short.'));
    }
    elseif (strlen($form_state->getValue('name')) > 100) {
      $form_state->setErrorByName('name', $this->t('Name is too long.'));
    }
    if (!$valid) {
      $form_state->setErrorByName('email', $this->t('Invalid email'));
    }
    if (!$validPhone) {
      $form_state->setErrorByName('phone', $this->t('Invalid phone'));
    }
    if (!$validMessage) {
      $form_state->setErrorByName('message', $this->t('Please, use only latin characters'));
    }
  }

  /**
   * Email validation handler.
   *
   * @return bool
   *
   *   The current state of the form.
   */
  protected function validateEmail(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('email');
    $stableExpression = '/^[A-Za-z_\-]+@\w+(?:\.\w+)+$/';
    if (preg_match($stableExpression, $email)) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @return \Drupal\Core\Ajax\AjaxResponse
   *
   *   Validation Email.
   */
  public function validateEmailAjax(array &$form, FormStateInterface $form_state) {
    $valid = $this->validateEmail($form, $form_state);
    $response = new AjaxResponse();
    if (!$valid) {
      $response->addCommand(new MessageCommand('Invalid Email', NULL, ['type' => 'error']));
    }
    else {
      $response->addCommand(new MessageCommand('Valid email'));
    }
    return $response;
  }

  /**
   * Phone validation handler.
   *
   * @return bool
   *
   *   The current state of the form.
   */
  protected function validatePhone(array &$form, FormStateInterface $form_state) {
    $phone = $form_state->getValue('phone');
    if (preg_match("/^[+]\d{3}+\d{4}+\d{5}$/", $phone)) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @return \Drupal\Core\Ajax\AjaxResponse
   *
   *   Validation Phone.
   */
  public function validatePhoneAjax(array &$form, FormStateInterface $form_state) {
    $validPhone = $this->validatePhone($form, $form_state);
    $response = new AjaxResponse();
    if (!$validPhone) {
      $response->addCommand(new MessageCommand('Not valid phone number', NULL, ['type' => 'error']));
    }
    else {
      $response->addCommand(new MessageCommand('Valid phone'));
    }
    return $response;
  }

  /**
   * Validate text field.
   */
  protected function validateMessage(array &$form, FormStateInterface $form_state) {
    $message = $form_state->getValue('message');
    $stableExpression = '/^[A-Za-z\d_\-]+$/';
    if (preg_match($stableExpression, $message)) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @return \Drupal\Core\Ajax\AjaxResponse
   *
   *   Validation Email.
   */
  public function validateMessageAjax(array &$form, FormStateInterface $form_state) {
    $validMessage = $this->validateMessage($form, $form_state);
    $response = new AjaxResponse();
    if (!$validMessage) {
      $response->addCommand(new MessageCommand('Please, use only latin characters', NULL, ['type' => 'error']));
    }
    else {
      $response->addCommand(new MessageCommand('Valid message'));
    }
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $avatar = $form_state->getValue('image');
    $picture = $form_state->getValue('photo');
    if ($picture != NULL) {
      $file = File::load($picture[0]);
      if ($file != NULL) {
        $file->setPermanent();
        $file->save();
      }
    }
    if ($avatar != NULL) {
      $avatar_file = File::load($avatar[0]);
      if ($avatar_file != NULL) {
        $avatar_file->setPermanent();
        $avatar_file->save();
      }
    }
    $data = [
      'name' => $form_state->getValue('name'),
      'image' => $form_state->getValue('image')[0],
      'date' => time(),
      'email' => $form_state->getValue('email'),
      'phone' => $form_state->getValue('phone'),
      'message' => $form_state->getValue('message'),
      'photo' => $form_state->getValue('photo')[0],
    ];
    \Drupal::database()->insert('stchk34')->fields($data)->execute();
    $this->messenger->addStatus($this->t('Hi! You added your cat!'));
  }

  /**
   * Submit Ajax.
   */
  public function ajaxSubmit(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    if ($form_state->hasAnyErrors()) {
      foreach ($form_state->getErrors() as $errors_array) {
        $response->addCommand(new MessageCommand($errors_array));
      }
    }
    else {
      $response->addCommand(new MessageCommand('You added a cat!'));
    }
    $this->messenger()->deleteAll();
    return $response;
  }

}
