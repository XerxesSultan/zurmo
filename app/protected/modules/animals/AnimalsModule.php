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

    class AnimalsModule extends SecurableModule
    {
        const RIGHT_CREATE_ANIMALS = 'Create Animals';
        const RIGHT_DELETE_ANIMALS = 'Delete Animals';
        const RIGHT_ACCESS_ANIMALS = 'Access Animals Tab';

        public function getDependencies()
        {
            return array(
                'zurmo',
            );
        }

        public function getRootModelNames()
        {
            return array('Animal');
        }

        public static function getTranslatedRightsLabels()
        {
            $params                              = LabelUtil::getTranslationParamsForAllModules();
            $labels                              = array();
            $labels[self::RIGHT_CREATE_ACCOUNTS] = Zurmo::t('AnimalsModule', 'Create AnimalsModulePluralLabel',     $params);
            $labels[self::RIGHT_DELETE_ACCOUNTS] = Zurmo::t('AnimalsModule', 'Delete AnimalsModulePluralLabel',     $params);
            $labels[self::RIGHT_ACCESS_ACCOUNTS] = Zurmo::t('AnimalsModule', 'Access AnimalsModulePluralLabel Tab', $params);
            return $labels;
        }

        public static function getDefaultMetadata()
        {
            $metadata = array();
            $metadata['global'] = array(
                'tabMenuItems' => array(
                    array(
                        'label'  => "eval:Zurmo::t('AnimalsModule', 'AnimalsModulePluralLabel', \$translationParams)",
                        'url'    => array('/animals/default'),
                        'right'  => self::RIGHT_ACCESS_ANIMALS,
                        'mobile' => true,
                    ),
                ),
                'designerMenuItems' => array(
                    'showFieldsLink' => true,
                    'showGeneralLink' => true,
                    'showLayoutsLink' => true,
                    'showMenusLink' => true,
                ),
                'globalSearchAttributeNames' => array(
                    'name'
                )
            );
            return $metadata;
        }

        public static function getPrimaryModelName()
        {
            return 'Animal';
        }

        public static function getSingularCamelCasedName()
        {
            return 'Animal';
        }

        public static function getAccessRight()
        {
            return self::RIGHT_ACCESS_ANIMALS;
        }

        public static function getCreateRight()
        {
            return self::RIGHT_CREATE_ANIMALS;
        }

        public static function getDeleteRight()
        {
            return self::RIGHT_DELETE_ANIMALS;
        }

        public static function getGlobalSearchFormClassName()
        {
            return 'AnimalsSearchForm';
        }

        protected static function getSingularModuleLabel($language)
        {
            return Zurmo::t('AnimalsModule', 'Animal', array(), null, $language);
        }

        protected static function getPluralModuleLabel($language)
        {
            return Zurmo::t('AnimalsModule', 'Animal', array(), null, $language);
        }
    }
?>
