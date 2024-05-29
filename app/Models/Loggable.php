<?php

namespace App\Models;

use App\Notifications\AuditNotification;
use Exception;
use Illuminate\Support\Facades\Auth;

trait Loggable
{
    // an attribute for setting whether or not the item was imported
    public ?bool $imported = false;

    /**
     * @author  Daniel Meltzer <dmeltzer.devel@gmail.com>
     * @since [v3.4]
     */
    public function log(): Actionlog
    {
        return $this->morphMany(Actionlog::class, 'item');
    }

    public function setImported(bool $bool): void
    {
        $this->imported = $bool;
    }

    /**
     * @author  Daniel Meltzer <dmeltzer.devel@gmail.com>
     * @since [v3.4]
     */
    public function logCheckout($note, $target, $action_date = null, $originalValues = []): Actionlog|bool
    {
        $log = new Actionlog;
        $log = $this->determineLogItemType($log);
        if (Auth::user()) {
            $log->user_id = Auth::user()->id;
        }

        if (! isset($target)) {
            throw new Exception('All checkout logs require a target.');
        }

        if (! isset($target->id)) {
            throw new Exception('That target seems invalid (no target ID available).');
        }

        $log->target_type = get_class($target);
        $log->target_id = $target->id;

        // Figure out what the target is
        if ($log->target_type == Location::class) {
            $log->location_id = $target->id;
        } elseif ($log->target_type == Asset::class) {
            $log->location_id = $target->location_id;
        } else {
            $log->location_id = $target->location_id;
        }

        $log->note = $note;
        $log->action_date = $action_date;

        if (! $log->action_date) {
            $log->action_date = date('Y-m-d H:i:s');
        }

        $changed = [];
        $originalValues = array_intersect_key($originalValues, array_flip(['action_date','name','status_id','location_id','expected_checkin']));

        foreach ($originalValues as $key => $value) {
            if ($key == 'action_date' && $value != $action_date) {
                $changed[$key]['old'] = $value;
                $changed[$key]['new'] = is_string($action_date) ? $action_date : $action_date->format('Y-m-d H:i:s');
            } elseif ($value != $this->getAttributes()[$key]) {
                $changed[$key]['old'] = $value;
                $changed[$key]['new'] = $this->getAttributes()[$key];
            }
        }

        if (!empty($changed)){
            $log->log_meta = json_encode($changed);
        }

        $log->logaction('checkout');

        return $log;
    }

    /**
     * Helper method to determine the log item type
     */
    private function determineLogItemType($log)
    {
        // We need to special case licenses because of license_seat vs license.  So much for clean polymorphism :
        if (static::class == LicenseSeat::class) {
            $log->item_type = License::class;
            $log->item_id = $this->license_id;
        } else {
            $log->item_type = static::class;
            $log->item_id = $this->id;
        }

        return $log;
    }

    /**
     * @author  Daniel Meltzer <dmeltzer.devel@gmail.com>
     * @since [v3.4]
     */
    public function logCheckin($target, $note, $action_date = null, $originalValues = []): Actionlog
    {
        $settings = Setting::getSettings();
        $log = new Actionlog;

        if($target != null){
            $log->target_type = get_class($target);
            $log->target_id = $target->id;

        }

        if (static::class == LicenseSeat::class) {
            $log->item_type = License::class;
            $log->item_id = $this->license_id;
        } else {
            $log->item_type = static::class;
            $log->item_id = $this->id;

            if (static::class == Asset::class) {
                if ($asset = Asset::find($log->item_id)) {
                    $asset->increment('checkin_counter');
                }
            }
        }

        $log->location_id = null;
        $log->note = $note;
        $log->action_date = $action_date;

        if (! $log->action_date) {
            $log->action_date = date('Y-m-d H:i:s');
        }

        if (Auth::user()) {
            $log->user_id = Auth::user()->id;
        }

        $changed = [];
        $originalValues = array_intersect_key($originalValues, array_flip(['action_date','name','status_id','location_id','rtd_location_id','expected_checkin']));

        foreach ($originalValues as $key => $value) {
            if ($key == 'action_date' && $value != $action_date) {
                $changed[$key]['old'] = $value;
                $changed[$key]['new'] = is_string($action_date) ? $action_date : $action_date->format('Y-m-d H:i:s');
            } elseif ($value != $this->getAttributes()[$key]) {
                $changed[$key]['old'] = $value;
                $changed[$key]['new'] = $this->getAttributes()[$key];
            }
        }

        if (!empty($changed)){
            $log->log_meta = json_encode($changed);
        }

        $log->logaction('checkin from');

//        $params = [
//            'target' => $target,
//            'item' => $log->item,
//            'admin' => $log->user,
//            'note' => $note,
//            'target_type' => $log->target_type,
//            'settings' => $settings,
//        ];
//
//
//        $checkinClass = null;
//
//        if (method_exists($target, 'notify')) {
//            try {
//                $target->notify(new static::$checkinClass($params));
//            } catch (\Exception $e) {
//                \Log::debug($e);
//            }
//
//        }
//
//        // Send to the admin, if settings dictate
//        $recipient = new \App\Models\Recipients\AdminRecipient();
//
//        if (($settings->admin_cc_email!='') && (static::$checkinClass!='')) {
//            try {
//                $recipient->notify(new static::$checkinClass($params));
//            } catch (\Exception $e) {
//                \Log::debug($e);
//            }
//
//        }

        return $log;
    }

    /**
     * @author  A. Gianotto <snipe@snipe.net>
     * @since [v4.0]
     */
    public function logAudit($note, $location_id, $filename = null): Actionlog
    {
        $log = new Actionlog;
        $location = Location::find($location_id);
        if (static::class == LicenseSeat::class) {
            $log->item_type = License::class;
            $log->item_id = $this->license_id;
        } else {
            $log->item_type = static::class;
            $log->item_id = $this->id;
        }
        $log->location_id = ($location_id) ?: null;
        $log->note = $note;
        $log->user_id = Auth::user()->id;
        $log->filename = $filename;
        $log->logaction('audit');

        $params = [
            'item' => $log->item,
            'filename' => $log->filename,
            'admin' => $log->admin,
            'location' => ($location) ? $location->name : '',
            'note' => $note,
        ];
        Setting::getSettings()->notify(new AuditNotification($params));

        return $log;
    }

    /**
     * @author  Daniel Meltzer <dmeltzer.devel@gmail.com>
     * @since [v3.5]
     */
    public function logCreate($note = null): Actionlog
    {
        $user_id = -1;
        if (Auth::user()) {
            $user_id = Auth::user()->id;
        }
        $log = new Actionlog;
        if (static::class == LicenseSeat::class) {
            $log->item_type = License::class;
            $log->item_id = $this->license_id;
        } else {
            $log->item_type = static::class;
            $log->item_id = $this->id;
        }
        $log->location_id = null;
        $log->note = $note;
        $log->user_id = $user_id;
        $log->logaction('create');
        $log->save();

        return $log;
    }

    /**
     * @author  Daniel Meltzer <dmeltzer.devel@gmail.com>
     * @since [v3.4]
     */
    public function logUpload($filename, $note): Actionlog
    {
        $log = new Actionlog;
        if (static::class == LicenseSeat::class) {
            $log->item_type = License::class;
            $log->item_id = $this->license_id;
        } else {
            $log->item_type = static::class;
            $log->item_id = $this->id;
        }
        $log->user_id = Auth::user()->id;
        $log->note = $note;
        $log->target_id = null;
        $log->created_at = date('Y-m-d H:i:s');
        $log->filename = $filename;
        $log->logaction('uploaded');

        return $log;
    }
}
