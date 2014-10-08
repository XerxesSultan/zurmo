<?php
    /**********************************************************************************
     * Zurmo is a customer relationship management program developed by
     * Zurmo, Inc. Copyright (C) 2011 Zurmo Inc.
     *
     * Zurmo is free software; you can redistribute it and/or modify it under
     * the terms of the GNU General Public License version 3 as published by the
     * Free Software Foundation with the addition of the following permission added
     * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
     * IN WHICH THE COPYRIGHT IS OWNED BY ZURMO, ZURMO DISCLAIMS THE WARRANTY
     * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
     *
     * Zurmo is distributed in the hope that it will be useful, but WITHOUT
     * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
     * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
     * details.
     *
     * You should have received a copy of the GNU General Public License along with
     * this program; if not, see http://www.gnu.org/licenses or write to the Free
     * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
     * 02110-1301 USA.
     *
     * You can contact Zurmo, Inc. with a mailing address at 113 McHenry Road Suite 207,
     * Buffalo Grove, IL 60089, USA. or at email address contact@zurmo.com.
     *********************************************************************************/

    class Animal extends Item
    {
        public function __toString()
        {
            if (trim($this->name) == '')
            {
                return Yii::t('Default', '(Unnamed)');
            }
            return $this->name;
        }

        public static function getModuleClassName()
        {
            return 'AnimalsModule';
        }

        public static function canSaveMetadata()
        {
            return true;
        }

        public static function getDefaultMetadata()
        {
            $metadata = parent::getDefaultMetadata();
            $metadata[__CLASS__] = array(
                'members' => array(
                    'name',
                    'description',
                    'checkBox',
                    'date',
                    'dateTime',
                    'decimal',
                    'integer',
                    'phone',
                    'text',
                    'textArea',
                    'url',
                ),
                'relations' => array(
                    'type'          => array(RedBeanModel::HAS_ONE,   'OwnedCustomField', RedBeanModel::OWNED),
                    'currency'      => array(RedBeanModel::HAS_ONE,   'CurrencyValue', RedBeanModel::OWNED),
                    'pickList'      => array(RedBeanModel::HAS_ONE,   'OwnedCustomField', RedBeanModel::OWNED),
                    'radioPickList' => array(RedBeanModel::HAS_ONE,   'OwnedCustomField', RedBeanModel::OWNED),
                ),
                'derivedRelationsViaCastedUpModel' => array(
                    'meetings' => array(RedBeanModel::MANY_MANY, 'Meeting', 'activityItems'),
                    'notes'    => array(RedBeanModel::MANY_MANY, 'Note',    'activityItems'),
                    'tasks'    => array(RedBeanModel::MANY_MANY, 'Task',    'activityItems'),
                ),
                'rules' => array(
                    array('name',           'required'),
                    array('name',           'type',           'type'  => 'string'),
                    array('name',           'length',         'max'   => 100),
                    array('description',    'type',           'type'  => 'string'),
                    array('checkBox',  'type',           'type'  => 'boolean'),
                    array('checkBox',  'default',        'value' => 1),
                    array('date',      'type',           'type'  => 'date'),
                    array('date',      'dateTimeDefault','value' => 2),
                    array('dateTime',  'type',           'type'  => 'datetime'),
                    array('dateTime',  'dateTimeDefault','value' => 2),
                    array('decimal',   'default',        'value' => 1),
                    array('decimal',   'length',         'max'   => 18),
                    array('decimal',   'numerical',      'precision' => 2),
                    array('decimal',   'type',           'type'   => 'float'),
                    array('integer',   'length',         'max'    => 11),
                    array('integer',   'numerical',      'max'    => 9999, 'min' => 0 ),
                    array('integer',   'type',           'type'   => 'integer'),
                    array('pickList',  'default',        'value'  => 'Value one'),
                    array('phone',     'length',         'max'    => 20),
                    array('phone',     'type',           'type'   => 'string'),
                    array('text',      'length',         'max'    => 255),
                    array('text',      'type',           'type'   => 'string'),
                    array('textArea',  'type',           'type'   => 'string'),
                    array('url',       'length',         'max'    => 255),
                    array('url',       'url'),
                ),
                'elements' => array(
                    'description'   => 'TextArea',
                    'checkBox'      => 'CheckBox',
                    'currency'      => 'CurrencyValue',
                    'date'          => 'Date',
                    'dateTime'      => 'DateTime',
                    'decimal'       => 'Decimal',
                    'integer'       => 'Integer',
                    'pickList'      => 'DropDown',
                    'phone'         => 'Phone',
                    'radioPickList' => 'RadioDropDown',
                    'text'          => 'Text',
                    'textArea'      => 'TextArea',
                    'url'           => 'Url',
                ),
                'customFields' => array(
                    'type'          => 'AnimalType',
                    'pickList'      => 'AnimalPickList',
                    'radioPickList' => 'AnimalRadioPickList',
                ),
                'defaultSortAttribute' => 'name',
                'noAudit' => array(
                ),
            );
            return $metadata;
        }

        public static function isTypeDeletable()
        {
            return true;
        }

        protected static function translatedAttributeLabels($language)
        {
            $params = LabelUtil::getTranslationParamsForAllModules();
            return array_merge(parent::translatedAttributeLabels($language),
                array(
                    'name'              => Zurmo::t('AnimalsModule', 'Name',  $params, null, $language),
                    'description'       => Zurmo::t('AnimalsModule', 'Description',  $params, null, $language),
                    'checkBox'          => Zurmo::t('AnimalsModule', 'Check Box',  $params, null, $language),
                    'date'              => Zurmo::t('AnimalsModule', 'Date',  $params, null, $language),
                    'dateTime'          => Zurmo::t('AnimalsModule', 'Date Time',  $params, null, $language),
                    'decimal'           => Zurmo::t('AnimalsModule', 'Decimal',  $params, null, $language),
                    'integer'           => Zurmo::t('AnimalsModule', 'Integer',  $params, null, $language),
                    'phone'             => Zurmo::t('AnimalsModule', 'Phone',  $params, null, $language),
                    'text'              => Zurmo::t('AnimalsModule', 'Text',  $params, null, $language),
                    'textArea'          => Zurmo::t('AnimalsModule', 'Text Area',  $params, null, $language),
                    'url'               => Zurmo::t('AnimalsModule', 'Url',  $params, null, $language),
                    'type'              => Zurmo::t('AnimalsModule', 'Type',  $params, null, $language),
                    'currency'          => Zurmo::t('AnimalsModule', 'Name',  $params, null, $language),
                    'pickList'          => Zurmo::t('AnimalsModule', 'Pick List',  $params, null, $language),
                    'radioPickList'     => Zurmo::t('AnimalsModule', 'Radio Pick List',  $params, null, $language),
                )
            );
        }
    }
?>
