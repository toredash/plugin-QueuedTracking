<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\QueuedTracking;

use Piwik\Plugins\QueuedTracking\Queue\Backend\MySQL;
use Piwik\Plugins\QueuedTracking\Tracker\Handler;

class QueuedTracking extends \Piwik\Plugin
{
    /**
     * @see \Piwik\Plugin::registerEvents
     */
    public function registerEvents()
    {
        return array(
            'Tracker.newHandler' => 'replaceHandlerIfQueueIsEnabled'
        );
    }

    public function install()
    {
        $mysql = new MySQL();
        $mysql->install();

        $configuration = new Configuration();
        $configuration->install();
    }

    public function uninstall()
    {
        $mysql = new MySQL();
        $mysql->uninstall();

        $configuration = new Configuration();
        $configuration->uninstall();
    }

    public function isTrackerPlugin()
    {
        return true;
    }

    public function replaceHandlerIfQueueIsEnabled(&$handler)
    {
        $settings = Queue\Factory::getSettings();

        if ($settings->queueEnabled->getValue()) {
            $handler = new Handler();

            if ($settings->processDuringTrackingRequest->getValue()) {
                $handler->enableProcessingInTrackerMode();
            }
        }
    }

}
