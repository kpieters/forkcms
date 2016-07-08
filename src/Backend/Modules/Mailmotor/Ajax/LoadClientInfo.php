<?php

namespace Backend\Modules\Mailmotor\Ajax;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Base\AjaxAction as BackendBaseAJAXAction;
use Backend\Modules\Mailmotor\Engine\CMHelper as BackendMailmotorCMHelper;

/**
 * This loads CM client info
 */
class LoadClientInfo extends BackendBaseAJAXAction
{
    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();

        // get parameters
        $clientId = \SpoonFilter::getPostValue('client_id', null, '');

        // check input
        if (empty($clientId)) {
            $this->output(self::BAD_REQUEST);
        } else {
            // get basic details for this client
            $client = BackendMailmotorCMHelper::getCM()->getClient($clientId);

            // CM was successfully initialized
            $this->output(self::OK, $client);
        }
    }
}
