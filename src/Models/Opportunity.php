<?php

/**
 * Opportunity
 *
 * @package SwiftDevLabs\CRM\Models
 * @author Kong Jin Jie <jinjie@swiftdev.sg>
 */

namespace SwiftDevLabs\CRM\Models;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\File;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\ORM\DataObject;
use SilverStripe\SiteConfig\SiteConfig;
use SwiftDevLabs\CRM\Models\Contact;
use SwiftDevLabs\CRM\Models\Pipeline;

class Opportunity extends DataObject
{
    use Configurable;

    private static $table_name = 'CRM_Opportunity';

    private static $db = [
        'Title'               => 'Varchar(255)',
        'Value'               => 'Currency',
        'ExpectedClosingDate' => 'Date',
        'Description'         => 'Text',
    ];

    private static $has_one = [
        'Contact'   => Contact::class,
        'Pipeline'     => Pipeline::class,
    ];

    private static $many_many = [
        'Quotations'    => File::class,
    ];

    private static $summary_fields = [
        'Contact.FullName'    => 'Contact',
        'Contact.CompanyName' => 'Company',
        'TitleFriendly'       => 'Title',
        'Value.Nice'          => 'Value',
        'Pipeline.Title'      => 'Pipeline',
        'Created',
    ];

    // Config
    private static $quotations_folder_name = 'Uploads/Quotations';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('Quotations');
        $fields->addFieldToTab(
            'Root.Main',
            UploadField::create(
                'Quotations',
                'Quotations',
                $this->Quotations()
            )->setFolderName($this->config()->get('quotations_folder_name'))
        );


        if (! $this->isInDB()) {
            $expectedClosingDays = SiteConfig::current_site_config()->ExpectedClosingDays;

            $fields
                ->fieldByName('Root.Main.ExpectedClosingDate')
                ->setRightTitle("+{$expectedClosingDays} days");
        }

        $contactField = $fields->fieldByName('Root.Main.ContactID');
        $fields->addFieldToTab(
            'Root.Main',
            $contactField,
            'Title'
        );

        return $fields;
    }

    public function populateDefaults()
    {
        parent::populateDefaults();

        $this->Pipeline = Pipeline::getNewPipeline()->ID;

        $expectedClosingDays = SiteConfig::current_site_config()->ExpectedClosingDays;

        $this->ExpectedClosingDate = date('Y-m-d', strtotime("{$expectedClosingDays} day"));
    }

    public function getTitleFriendly()
    {
        return ($this->Title ?: "---");
    }
}
