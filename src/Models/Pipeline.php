<?php

/**
 * Pipeline
 *
 * @package SwiftDevLabs\CRM\Models
 * @author Kong Jin Jie <jinjie@swiftdev.sg>
 */

namespace SwiftDevLabs\CRM\Models;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\ORM\DataObject;
use SwiftDevLabs\CRM\Models\Opportunity;

class Pipeline extends DataObject
{
    private static $table_name = 'CRM_Pipeline';

    private static $db = [
        'Title'       => 'Varchar(50)',
        'Probability' => 'Int',
        'Description' => 'Text',
        'System'      => 'Boolean',
        'Sort'        => 'Int',
    ];

    private static $default_records = [
        [
            'Title'       => 'New',
            'Description' => 'Any new opportunities will be categorised here.',
            'Probability' => '10',
            'System'      => true,
        ],
        [
            'Title'       => 'Won',
            'Description' => 'Opportunities that has been won.',
            'Probability' => '100',
            'System'      => true,
        ],
        [
            'Title'       => 'Lost',
            'Description' => 'Opportunities that has been lost.',
            'Probability' => '0',
            'System'      => true,
        ],
    ];

    private static $has_many = [
        'Opportunities' => Opportunity::class,
    ];

    private static $default_sort = "Sort";

    private static $summary_fields = [
        'Title',
        'Probability'   => 'Probability (%)',
        'Description',
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('System');

        $fields->removeByName('Sort');

        return $fields;
    }

    public static function getNewPipeline()
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
