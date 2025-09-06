<?php

declare(strict_types=1);

/*
 * This file is part of SAC Event Tool Bundle.
 *
 * (c) Marko Cupic <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/sac-event-tool-bundle
 */

use Contao\DC_Table;
use Contao\DataContainer;

$GLOBALS['TL_DCA']['tl_frontend_user_notification'] = [
    'config'      => [
        'dataContainer'    => DC_Table::class,
        'enableVersioning' => true,
        'switchToEdit'     => true,
        'sql'              => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],
    'list'        => [
        'sorting'           => [
            'mode'               => DataContainer::MODE_SORTABLE,
            'fields'             => ['dateAdded'],
            'panelLayout'        => 'filter;sort,search,limit',
            'defaultSearchField' => 'messageTitle',
        ],
        'label'             => [
            'fields'      => ['dateAdded', 'user', 'type', 'messageTitle', 'isRead', 'isReadTstamp'],
            'showColumns' => true,
        ],
        'global_operations' => [
            'all',
        ],
    ],
    'palettes'    => [
        '__selector__' => ['isRead'],
        'default'      => '{user_legend},user;{details_legend},dateAdded,endOfLifeTstamp,type,uuid;{notification_legend},messageTitle,messageText,isRead',
    ],
    // Sub-palettes
    'subpalettes' => [
        'isRead' => 'isReadTstamp',
    ],
    'fields'      => [
        'id'              => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp'          => [
            'sql' => "int(10) unsigned NOT NULL default 0",
        ],
        'dateAdded'       => [
            'default'   => time(),
            'sorting'   => true,
            'flag'      => DataContainer::SORT_DAY_DESC,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'datim', 'doNotCopy' => true, 'datepicker' => true, 'tl_class' => 'w50'],
            'sql'       => "int(10) unsigned NOT NULL default 0",
        ],
        'endOfLifeTstamp' => [
            'sorting'   => true,
            'flag'      => DataContainer::SORT_DAY_DESC,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'datim', 'doNotCopy' => true, 'datepicker' => true, 'tl_class' => 'w50'],
            'sql'       => "int(10) unsigned NOT NULL default 0",
        ],
        'uuid'            => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => false,
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'doNotCopy' => true, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'type'            => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => false,
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'user'            => [
            'exclude'    => true,
            'search'     => true,
            'sorting'    => false,
            'foreignKey' => 'tl_member.CONCAT(firstname," ",lastname, " [", id, "]")',
            'inputType'  => 'select',
            'eval'       => ['mandatory' => true, 'tl_class' => 'w50'],
            'sql'        => "int(10) unsigned NOT NULL default 0",
            'relation'   => ['type' => 'hasOne', 'load' => 'lazy'],
        ],
        'messageTitle'    => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => false,
            'inputType' => 'text',
            'eval'      => ['mandatory' => false, 'tl_class' => 'w50'],
            'sql'       => 'text NULL',
        ],
        'messageText'     => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => false,
            'inputType' => 'text',
            'eval'      => ['mandatory' => false, 'tl_class' => 'w50'],
            'sql'       => 'text NULL',
        ],
        'isRead'          => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => false,
            'inputType' => 'checkbox',
            'eval'      => ['mandatory' => false, 'submitOnChange' => true, 'doNotCopy' => true, 'tl_class' => 'clr'],
            'sql'       => ['type' => 'boolean', 'default' => false],
        ],
        'isReadTstamp'    => [
            'default'   => time(),
            'sorting'   => true,
            'flag'      => DataContainer::SORT_DAY_DESC,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'datim', 'doNotCopy' => true, 'tl_class' => 'w50'],
            'sql'       => "int(10) unsigned NULL default NULL",
        ],
    ],
];
