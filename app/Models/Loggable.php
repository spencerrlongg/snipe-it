<?php

namespace App\Models;

use App\Notifications\AuditNotification;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

trait Loggable
{
    // an attribute for setting whether or not the item was imported
    public ?bool $imported = false;

    public static function bootLoggable()
    {
        //loops through the events to be recorded (defined in the model)
        //and then calls the proper method for each model
        static::eventsToBeRecorded()->each(function ($event) {
            static::$event(function ($model) use ($event) {
                switch (static::class) {
                    case Setting::class:
                        $model->logAdmin(actionType: $event, note: 'settings trait');
                        break;
                    case User::class:
                        $model->logAdmin(actionType: $event, note: 'user trait');
                        break;
                    case Asset::class:
                        if ($event == 'created') {
                            $model->logCreate(event: $event, note: 'asset trait boot method');
                        }
                        if ($event == 'updated') {
                            $model->logUpdate();
                        }
                        break;
                    case Accessory::class:
                        // accessory seems to not fire eloquent events???
                        $model->logCreate(event: $event, note: 'accessory trait boot method');
                        break;
                    case License::class:
                        $model->logCreate(event: $event, note: 'license trait boot method');
                        break;
                    // should be able to listen for the checkin and checkout events here as well
                    // as long as they're manually declared in the model $recordEvents property
                    // etc...
                    default:
                        //do nothing for now
                        break;
                }
            });
        });
    }

    protected static function eventsToBeRecorded(): Collection
    {
        if (isset(static::$recordEvents)) {
            return collect(static::$recordEvents);
        }

        $events = collect([
            'created',
            'updated',
            'deleted',
        ]);

        if (collect(class_uses_recursive(static::class))->contains(SoftDeletes::class)) {
            $events->push('restored');
        }

        return $events;
    }

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
    public function logCreate($event, $note = null): Actionlog
    {
        //if ($event == 'created') {
        //    $event = 'create';
        //}
        //if ($event == 'deleted') {
        //    $event = 'delete';
        //}
        //if ($event == 'restored') {
        //    $event = 'restore';
        //}
        $user_id = -1;
        //{"name":{"old":"Test api Asset checkbox","new":"poop"}}
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
        $log->logaction($event);
        if ($this->imported) {
            $this->setActionSource('importer');
        }
        $log->save();


        return $log;
    }

    public function logUpdate(): Actionlog|bool
    {
        $attributes = self::getAttributes();
        $attributesOriginal = self::getRawOriginal();
        $same_checkout_counter = false;
        $same_checkin_counter = false;
        $restoring_or_deleting = false;


        // This is a gross hack to prevent the double logging when restoring an asset
        if (array_key_exists('deleted_at', $attributes) && array_key_exists('deleted_at', $attributesOriginal)) {
            $restoring_or_deleting = (($attributes['deleted_at'] != $attributesOriginal['deleted_at']));
        }

        if (array_key_exists('checkout_counter', $attributes) && array_key_exists('checkout_counter', $attributesOriginal)) {
            $same_checkout_counter = (($attributes['checkout_counter'] == $attributesOriginal['checkout_counter']));
        }

        if (array_key_exists('checkin_counter', $attributes) && array_key_exists('checkin_counter', $attributesOriginal)) {
            $same_checkin_counter = (($attributes['checkin_counter'] == $attributesOriginal['checkin_counter']));
        }

        // If the asset isn't being checked out or audited, log the update.
        // (Those other actions already create log entries.)
        if (($attributes['assigned_to'] == $attributesOriginal['assigned_to'])
            && ($same_checkout_counter) && ($same_checkin_counter)
            && ((isset($attributes['next_audit_date']) ? $attributes['next_audit_date'] : null) == (isset($attributesOriginal['next_audit_date']) ? $attributesOriginal['next_audit_date'] : null))
            && ($attributes['last_checkout'] == $attributesOriginal['last_checkout']) && (!$restoring_or_deleting)) {
            $changed = [];

            foreach (self::getRawOriginal() as $key => $value) {
                if (self::getRawOriginal()[$key] != self::getAttributes()[$key]) {
                    $changed[$key]['old'] = self::getRawOriginal()[$key];
                    $changed[$key]['new'] = self::getAttributes()[$key];
                }
            }

            unset($changed['updated_at']);


            if (empty($changed)) {
                return false;
            }

            $logAction = new Actionlog();
            $logAction->item_type = self::class;
            $logAction->item_id = self::getAttributes()['id'];
            $logAction->created_at = date('Y-m-d H:i:s');
            $logAction->user_id = Auth::id();
            $logAction->log_meta = json_encode($changed);
            $logAction->logaction('update');
        }
        return $logAction;
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


    public function logAdmin($actionType = null, $note = null, $providedValue = null)
    {
        if ($this->isDirty()) {
            $changed = [];
            $new = $this->getDirty();
            $old = $this->getRawOriginal();
            if ($this->isDirty('password')) {
                $changed['new']['password'] = '********';
                $changed['old']['password'] = '********';
            } else {
                foreach ($new as $key => $value) {
                    if (array_key_exists($key, $old) && is_null($providedValue)) {
                        $changed['new'][$key] = $new[$key];
                        $changed['old'][$key] = $old[$key];
                    } else {
                        $changed = $providedValue;
                    }
                }
            }
            if (is_null($providedValue)) {
                unset($changed['new']['updated_at'], $changed['old']['updated_at']);
            }

            $user = Auth::user();

            $log = new Adminlog();
            $log->user_id = $user->id ?? 1;
            $log->action_type = $actionType ? $actionType : null;
            $log->item_type = static::class;
            $log->item_id = $this->id;
            $log->note = $note ? $note : null;
            $log->log_meta = json_encode($changed);

            $log->save();
            return $log;
        } else {
            return;
        }
    }
}
