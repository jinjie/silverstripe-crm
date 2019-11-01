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
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\View\HTML;
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
        'ClosingDaysAgo'      => '',
    ];

    private static $casting = [
        'ClosingDaysAgo'    => 'HTMLText',
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

    /**
     * Get number of days to expected closing date
     * @return int Number of days
     */
    public function getClosingDaysAgo()
    {
        $expectedClosingDate = $this->obj('ExpectedClosingDate');

        if ($this->Pipeline()->Title == 'Won' || $this->Pipeline()->Title == 'Lost') {
            return DBHTMLText::create()
                ->setValue(
                    HTML::createTag(
                        'span',
                        [
                            'class' => 'card border-0 px-2 py-1 bg-light d-inline',
                        ],
                        $this->Pipeline()->Title
                    )
                );
        }

        if (! $expectedClosingDate->getValue()) {
            $text = "Closing Date Not Set";
        } else {
            $text = ($expectedClosingDate->InFuture() ?
                "Expected closing " :
                "Expired "
            ) . $expectedClosingDate->Ago();
        }

        return DBHTMLText::create()
            ->setValue(
                HTML::createTag(
                    'span',
                    [
                        'class' => (
                            ($expectedClosingDate->getValue() and $expectedClosingDate->InFuture()) ?
                                'card border-0 px-2 py-1 bg-success d-inline text-white' :
                                'card border-0 px-2 py-1 bg-warning d-inline text-dark'
                        )
                    ],
                    $text
                )
            );
    }
}
