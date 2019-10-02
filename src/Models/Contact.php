<?php

/**
 * Contact
 *
 * @package SwiftDevLabs\CRM\Models
 * @author Kong Jin Jie <jinjie@swiftdev.sg>
 */

namespace SwiftDevLabs\CRM\Models;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\ORM\DataObject;
use SwiftDevLabs\CRM\Models\Opportunity;
use Symbiote\Addressable\Addressable;

class Contact extends DataObject
{
    use Configurable;

    private static $table_name = 'CRM_Contact';

    private static $extensions = [
        Addressable::class,
    ];

    private static $db = [
        'FullName'    => 'Varchar(255)',
        'CompanyName' => 'Varchar(255)',
        'Designation' => 'Varchar(255)',
        'Email'       => 'Varchar(255)',
        'Phone'       => 'Varchar(50)',
    ];

    private static $has_many = [
        'Opportunities' => Opportunity::class,
    ];

    private static $summary_fields = [
        'FullName',
        'CompanyName',
        'Designation',
        'Email',
        'Phone',
    ];

    // Configs
    private static $default_country = false;

    public function populateDefaults()
    {
        if ($default_country = $this->config()->get('default_country')) {
            $this->Country = $default_country;
        }
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        if ($this->isInDB()) {
            $fields
                ->fieldByName('Root.Opportunities.Opportunities')
                ->setConfig(
                    GridFieldConfig_RecordEditor::create()
                );

            $addressTab = $fields->fieldByName('Root.Address');
            $fields->removeByName('Address');
            $fields->insertBefore('Opportunities', $addressTab);
        }

        return $fields;
    }

    public function getTitle()
    {
        return $this->FullName . ($this->CompanyName ? " ({$this->CompanyName})" : '');
    }
}
