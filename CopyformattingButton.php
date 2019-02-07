<?php

namespace Drupal\copyformatting\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\ckeditor\CKEditorPluginConfigurableInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\editor\Entity\Editor;

/**
 * Defines the "copyformatting" plugin.
 *
 * @CKEditorPlugin(
 *   id = "copyformatting",
 *   label = @Translation("Copy Formatting Button")
 * )
 */
class CopyformattingButton extends CKEditorPluginBase implements CKEditorPluginConfigurableInterface {
  /**
   * Get path to library folder.
   */
  public function getLibraryPath() {
    $path = '/libraries/copyformatting';
    if (\Drupal::moduleHandler()->moduleExists('libraries')) {
      $path = libraries_get_path('copyformatting');
    }

    return $path;
  }

  
   /**
     * {@inheritdoc}
     */
    public function getDependencies(Editor $editor) {
        return [];
    }


  /**
   * {@inheritdoc}
   */
  public function getFile() {
    return $this->getLibraryPath() . '/plugin.js';
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig(Editor $editor) {
        $settings = $editor->getSettings();

        if ($settings['plugins']['copyformatting']['copyFormatting_outerCursor'] === 1) {
            $config = [
                'copyFormatting_outerCursor' => true,
            ];
        } else {
            $config = [
                'copyFormatting_outerCursor' => false,
            ];
        }

        return $config;
    }

  /**
   * {@inheritdoc}
   */
   
   public function getButtons() {
	   $path = $this->getLibraryPath();
        return [
            'CopyFormatting' => array(
                'label' => $this->t('Copy Formatting'),
                'image' => $path . '/icons/copyformatting.png',
            ),
        ];
    }

  /**
     * {@inheritdoc}
     */
    public function settingsForm(array $form, FormStateInterface $form_state, Editor $editor) {
        $settings = $editor->getSettings();

        $form['copyFormatting_outerCursor'] = array(
            '#type' => 'checkbox',
            '#title' => $this->t('copyFormatting_outerCursor'),
            '#description' => $this->t('Whether to wrap the entire table instead of individual cells when creating a <div> in a table cell.'),
            '#default_value' => !empty($settings['plugins']['copyformatting']['copyFormatting_outerCursor']) ? $settings['plugins']['copyformatting']['copyFormatting_outerCursor'] : 0,
        );

        $form['copyformatting']['#element_validate'][] = array($this, 'validateInput');

        return $form;
    }


/**
     * Ensure values entered is boolean
     * @param $element
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     */
    public function validateInput(array $element, FormStateInterface $form_state) {
        $input = $form_state->getValue(['editor', 'settings', 'plugins', 'copyformatting', 'copyFormatting_outerCursor']);

        if (!preg_match('/([0-1]{1})/i', $input)) {
            $form_state->setError($element, 'Only valid boolean values are allowed (0-1). Please check your settings and try again.');
        }
    }

}
