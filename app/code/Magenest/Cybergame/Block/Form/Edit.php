<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\Cybergame\Block\Form;

/**
 * Customer edit form block
 *
 * @api
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 * @since 100.0.2
 */
class Edit extends \Magento\Customer\Block\Form\Edit
{
    public function getIsManager()
    {
        $coll = $this->getCustomer ();
        if ($coll->getCustomAttribute ('is_manager')->getValue () == 0) {
            return '';
        } else {
            return 'checked';
        }
    }
}
