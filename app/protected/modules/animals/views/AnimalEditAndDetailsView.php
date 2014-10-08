<?php
    /*********************************************************************************
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
     ********************************************************************************/

    class AnimalEditAndDetailsView extends SecuredEditAndDetailsView
    {
        public static function getDefaultMetadata()
        {
            $metadata = array(
                'global' => array(
                    'toolbar' => array(
                        'elements' => array(
                            array('type' => 'CancelLink', 'renderType' => 'Edit'),
                            array('type' => 'SaveButton', 'renderType' => 'Edit'),
                            array('type' => 'ListLink',
                                'renderType' => 'Details',
                                'label' => "eval:Yii::t('Default', 'Return to List')"
                            ),
                            array('type' => 'EditLink', 'renderType' => 'Details'),
                            array('type' => 'AuditEventsModalListLink', 'renderType' => 'Details'),
                        ),
                    ),
                    'derivedAttributeTypes' => array(
                        'DateTimeCreatedUser',
                        'DateTimeModifiedUser',
                    ),
                    'panelsDisplayType' => FormLayout::PANELS_DISPLAY_TYPE_ALL,
                    'panels' => array(
    array(
        'rows' => array(
            array('cells' =>
                array(
                    array(
                        'elements' => array(
                            array('attributeName' => 'name', 'type' => 'Text'),
                        ),
                    ),
                    array(
                        'elements' => array(
                            array('attributeName' => 'type', 'type' => 'DropDown', 'addBlank' => true),
                        ),
                    ),
                )
            ),
            array('cells' =>
                array(
                    array(
                        'elements' => array(
                            array('attributeName' => 'description', 'type' => 'TextArea'),
                        ),
                    ),
                )
            ),
            array('cells' =>
                array(
                    array(
                        'detailViewOnly' => false,
                        'elements' => array(
                            array('attributeName' => 'checkBox', 'type' => 'CheckBox'),
                        ),
                    ),
                    array(
                        'detailViewOnly' => false,
                        'elements' => array(
                            array('attributeName' => 'dateTime', 'type' => 'DateTime'),
                        ),
                    ),
                )
            ),
            array('cells' =>
                array(
                    array(
                        'detailViewOnly' => false,
                        'elements' => array(
                            array('attributeName' => 'integer', 'type' => 'Integer'),
                        ),
                    ),
                    array(
                        'detailViewOnly' => false,
                        'elements' => array(
                            array('attributeName' => 'text', 'type' => 'Text'),
                        ),
                    ),
                )
            ),
            array('cells' =>
                array(
                    array(
                        'detailViewOnly' => false,
                        'elements' => array(
                            array('attributeName' => 'url', 'type' => 'Url'),
                        ),
                    ),
                    array(
                        'detailViewOnly' => false,
                        'elements' => array(
                            array('attributeName' => 'currency', 'type' => 'CurrencyValue'),
                        ),
                    ),
                )
            ),
            array('cells' =>
                array(
                    array(
                        'detailViewOnly' => false,
                        'elements' => array(
                            array('attributeName' => 'radioPickList', 'type' => 'RadioDropDown', 'addBlank' => true),
                        ),
                    ),
                    array(
                        'detailViewOnly' => false,
                        'elements' => array(
                            array('attributeName' => 'pickList', 'type' => 'DropDown', 'addBlank' => true),
                        ),
                    ),
                )
            ),
            array('cells' =>
                array(
                    array(
                        'detailViewOnly' => false,
                        'elements' => array(
                            array('attributeName' => 'decimal', 'type' => 'Decimal'),
                        ),
                    ),
                    array(
                        'detailViewOnly' => false,
                        'elements' => array(
                            array('attributeName' => 'phone', 'type' => 'Phone'),
                        ),
                    ),
                )
            ),
            array('cells' =>
                array(
                    array(
                        'detailViewOnly' => false,
                        'elements' => array(
                            array('attributeName' => 'textArea', 'type' => 'TextArea'),
                        ),
                    ),
                    array(
                        'detailViewOnly' => false,
                        'elements' => array(
                            array('attributeName' => 'date', 'type' => 'Date'),
                        ),
                    ),
                )
            ),
            array('cells' =>
                array(
                    array(
                        'detailViewOnly' => true,
                        'elements' => array(
                            array('attributeName' => 'null', 'type' => 'DateTimeCreatedUser'),
                        ),
                    ),
                    array(
                        'detailViewOnly' => true,
                        'elements' => array(
                            array('attributeName' => 'null', 'type' => 'DateTimeModifiedUser'),
                        ),
                    ),
                )
            ),
        ),
    ),
),
                ),
            );
            return $metadata;
        }

        protected function getNewModelTitleLabel()
        {
            return Yii::t('Default', 'Create AnimalsModuleSingularLabel',
                                     LabelUtil::getTranslationParamsForAllModules());
        }
    }
?>
