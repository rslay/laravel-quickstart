<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Builds JSON response out of a list of notifications, counts of newly read/unread notifications,
     * and (optionally) extra info
     */
    public function formatResponse($notifications, $countUpdated=0, $info=null, $statusCode=200) {
        $response = [];

        if ($countUpdated !== null)
            $response = array_merge($response, ["modified_count" => intval($countUpdated)]);

        if ($notifications !== null)
            $response = array_merge($response, ["notifications" => $notifications]);

        if ($info !== null)
            if (gettype($info) === "array")
                $response = array_merge($response, $info);
            else
                $response = array_merge($response, ["info" => $info]);

        return response()->json($response, $statusCode);
    }

    /**
     * Get unread notifications (status of 0), marking them as read (status of 1) for a user
     */
    public function unread()
    {
        $notifications = Auth::user()->notifications()->where("status", false)->get();
        $countUpdated = Auth::user()->notifications()->where("status", false)->update(["status" => true]);
        return $this->formatResponse($notifications, $countUpdated);
    }

    /**
     * Get already read notifications (status of 1) for a user
     */
    public function read()
    {
        $notifications = Auth::user()->notifications()->where("status", true)->get();
        return $this->formatResponse($notifications);
    }

    /**
     * Get all notifications for a user
     */
    public function all()
    {
        $notifications = Auth::user()->notifications()->get();
        $countUpdated = Auth::user()->notifications()->where("status", false)->update(["status" => true]);
        return $this->formatResponse($notifications, $countUpdated);
    }

    /**
     * Mark notification as read (status of 1) for a user
     *
     * @param Number $id Notification ID
     */
    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if ($notification == null) {
            return $this->formatResponse(null, null, ["error" => Config::get('constants.http_error.e404')], 404);
        }
        Notification::find($id)->update(["status" => true]);
        return $this->formatResponse([$notification], !$notification["status"]);
    }

    /**
     * Mark notification as unread (status of 0) for a user
     *
     * @param Number $id Notification ID
     */
    public function markAsUnread($id)
    {
        $notification = Notification::find($id);
        if ($notification == null) {
            return $this->formatResponse(null, null, ["error" => Config::get('constants.http_error.e404')], 404);
        }
        Notification::find($id)->update(["status" => false]);
        return $this->formatResponse([$notification], $notification["status"]);
    }

    /**
     * Create a notification for a user
     *
     * @param Request $request Form data with text
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            "text" => "required|string",
        ]);
        $notification = new Notification;
        $notification->user_id = Auth::user()->id;
        $notification->text = $data['text'];
        $notification->status = false;
        $notification->save();
        return $this->formatResponse([$notification], null, "success");
    }

    /**
     * Delete a notification given an id for a user
     *
     * @param Number $id Notification ID
     */
    public function delete($id)
    {
        $notification = Notification::find($id);
        if ($notification == null) {
            return $this->formatResponse(null, null, ["error" => Config::get('constants.http_error.e404')], 404);
        }
        Notification::find($id)->delete();
        return $this->formatResponse([$notification], 1);
    }

    /**
     * Delete all notifications for a user
     */
    public function deleteAll()
    {
        $notifications = Auth::user()->notifications()->get();
        $deletedCount = Auth::user()->notifications()->delete();
        return $this->formatResponse($notifications, $deletedCount, "success");
    }
}
