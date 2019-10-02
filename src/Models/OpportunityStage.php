<?php

/**
 * OpportunityStage
 *
 * @package SwiftDevLabs\CRM\Models
 * @author Kong Jin Jie <jinjie@swiftdev.sg>
 */

namespace SwiftDevLabs\CRM\Models;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\ORM\DataObject;
use SwiftDevLabs\CRM\Models\OpportunityStage;

class OpportunityStage extends DataObject
{
    private static $table_name = 'CRM_OpportunityStage';

    private static $db = [
        'Title'       => 'Varchar(50)',
        'Description' => 'Text',
        'System'      => 'Boolean',
        'Sort'        => 'Int',
    ];

    private static $default_records = [
        [
            'Title'       => 'New',
            'Description' => 'Any new opportunities will be categorised here.',
            'System'      => true,
        ],
        [
            'Title'       => 'Won',
            'Description' => 'Opportunities that have been won.',
            'System'      => true,
        ],
        [
            'Title'       => 'Lost',
            'Description' => 'Opportunities that have been lost.',
            'System'      => true,
        ],
    ];

    private static $has_many = [
        'Opportunities' => OpportunityStage::class,
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('System');

        $fields->removeByName('Sort');

        return $fields;
    }

    public static function getNewStage()
    {
        return self::get()->filter('Title', 'New')->first();
    }

    public function canDelete($member = null)
    {
        return ! $this->System;
    }

    public function canEdit($member = null)
    {
        return ! $this->System;
    }
}
