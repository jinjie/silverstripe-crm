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
use SilverStripe\ORM\DataExtension;
use SwiftDevLabs\CRM\Models\OpportunityStage;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

class SiteConfigExtension extends DataExtension
{
    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab(
            'Root.CRM',
            GridField::create(
                'OpportunityStages',
                'Opportunity Stages',
                OpportunityStage::get(),
                GridFieldConfig_RecordEditor::create()
                    ->addComponent(new GridFieldOrderableRows())
            )
        );

        return $fields;
    }
}
