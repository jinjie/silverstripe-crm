<?php

/**
 * SiteConfigExtension
 *
 * @package SwiftDevLabs\CRM\Extensions
 * @author Kong Jin Jie <jinjie@swiftdev.sg>
 */

namespace SwiftDevLabs\CRM\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\TabSet;
use SilverStripe\ORM\DataExtension;
use SwiftDevLabs\CRM\Models\Pipeline;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

class SiteConfigExtension extends DataExtension
{
    private static $db = [
        'ExpectedClosingDays'   => 'Int',
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab(
            'Root.CRM',
            TabSet::create(
                'CRMTabSet',

                // Configuration Tab
                Tab::create(
                    'Configuration',
                    'Configuration',
                    NumericField::create('ExpectedClosingDays')
                ),

                // Pipeline Tab
                Tab::create(
                    'Pipelines',
                    'Pipelines',
                    GridField::create(
                        'Pipelines',
                        'Pipelines',
                        Pipeline::get(),
                        GridFieldConfig_RecordEditor::create()
                            ->addComponent(new GridFieldOrderableRows())
                    )
                )
            )
        );

        return $fields;
    }
}
