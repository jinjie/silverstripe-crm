<?php

/**
 * ContactsAdmin
 *
 * @package SwiftDevLabs\CRM\Admin
 * @author Kong Jin Jie <jinjie@swiftdev.sg>
 */

namespace SwiftDevLabs\CRM\Admin;

use SilverStripe\Admin\ModelAdmin;
use SwiftDevLabs\CRM\Models\Contact;

class ContactsAdmin extends ModelAdmin
{
    private static $managed_models = [
        Contact::class,
    ];

    private static $menu_title = 'Contacts';

    private static $url_segment = 'contacts';
}
