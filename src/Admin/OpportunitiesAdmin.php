<?php

/**
 * OpportunitiesAdmin
 *
 * @package SwiftDevLabs\CRM\Admin
 * @author Kong Jin Jie <jinjie@swiftdev.sg>
 */

namespace SwiftDevLabs\CRM\Admin;

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\GridField\GridField;
use SwiftDevLabs\CRM\Models\Opportunity;
use SwiftDevLabs\CRM\Models\Pipeline;

class OpportunitiesAdmin extends ModelAdmin
{
    private static $managed_models = [
        Opportunity::class,
    ];

    private static $menu_title = 'Opportunities';

    private static $url_segment = 'opportunities';

    public function getList()
    {
        $list = parent::getList();

        $opportunityTable = singleton(Opportunity::class)->baseTable();
        $pipelineTable    = singleton(Pipeline::class)->baseTable();

        $list = $list
            ->leftJoin(
                $pipelineTable,
                "{$opportunityTable}.PipelineID = {$pipelineTable}.ID"
            )
            ->sort("{$pipelineTable}.Sort, Created DESC");

        return $list;
    }
}
