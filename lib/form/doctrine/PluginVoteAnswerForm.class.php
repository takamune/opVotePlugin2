<?php

/**
 * PluginVoteAnswer form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginVoteAnswerForm extends BaseVoteAnswerForm
{
  public function setup()
  {
    parent::setup();

//    $this->widgetSchema->setLabel('', '選択肢');
//    $this->widgetSchema->setLabel('投票', '選択肢');

    // 選択肢を指定するウィジェット追加
//    $this->setWidget('option', new sfWidgetFormChoice());
//    //$this->setValidator('option', new sfValidatorString(array('trim' => true)));
//    $this->widgetSchema->setLabel('option', '選択肢');
//    $this->widgetSchema->setHelp('option', '選択肢をスペース区切りで入力してください');

    $this->setDefault('member_id', 'ああああ');
    //$this->useFields(array('member_id'));
  }
}
