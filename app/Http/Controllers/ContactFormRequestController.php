<?php

namespace App\Http\Controllers;

use App\Models\ContactFormRequest;
use Illuminate\Http\Request;
use Reefki\DeviceDetector\Device;
use DeviceDetector\Parser\Client\Browser;
use App\Models\TrackedEvents;

class ContactFormRequestController extends Controller {
    //

    public function index() {

        $requests = ContactFormRequest::orderBy('created_at', 'desc')->paginate(10);

        return view('contact-form-requests', [
            'requests' => $requests,
        ]);
    }

    public function show($id) {

        $contactFormRequest = ContactFormRequest::findOrFail($id);
        $device = Device::detect($contactFormRequest->trackedEvent->user_agent);
        $browser = Browser::getBrowserFamily($device->getClient('name'));

        $os = $device->getOs();
        if ($os["version"] === "") {
            $os_name = $os["name"];
        } else {
            $os_name = $os["name"] . " " . $os["version"];
        }

        $user_activity = TrackedEvents::where('session_id', $contactFormRequest->trackedEvent->session_id)
            ->where('url', '!=', $contactFormRequest->trackedEvent->url)
            ->orderBy('created_at', 'desc')
            ->get();


        return view('contact-form-request-view', [
            'request' => $contactFormRequest,
            'device' => [
                "name" => ucfirst($device->getDeviceName()),
                "os" => $os_name,
            ],
            'browser' => $browser,
            'user_activity' => $user_activity,
        ]);
    }
}
