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
use SwiftDevLabs\CRM\Models\Contact;
use SwiftDevLabs\CRM\Models\OpportunityStage;

class Opportunity extends DataObject
{
    use Configurable;

    private static $table_name = 'CRM_Opportunity';

    private static $db = [
        'Title'       => 'Varchar(255)',
        'Value'       => 'Currency',
        'Description' => 'Text',
    ];

    private static $has_one = [
        'Contact'   => Contact::class,
        'Stage'     => OpportunityStage::class,
    ];

    private static $many_many = [
        'Quotations'    => File::class,
    ];

    private static $summary_fields = [
        'Contact.FullName'    => 'Contact',
        'Contact.CompanyName' => 'Company',
        'TitleFriendly'       => 'Title',
        'Value.Nice'          => 'Value',
        'Stage.Title'         => 'Stage',
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

        $this->Stage = OpportunityStage::getNewStage()->ID;
    }

    public function getTitleFriendly()
    {
        return ($this->Title ?: "---");
    }
}
