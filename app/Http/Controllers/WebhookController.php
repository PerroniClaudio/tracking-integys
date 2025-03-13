<?php

namespace App\Http\Controllers;

use App\Jobs\RegisterArticleViewEvent;
use App\Jobs\RegisterButtonPress;
use App\Jobs\RegisterFormSubmission;
use Illuminate\Http\Request;
use App\Jobs\RegisterPageViewEvent;
use App\Jobs\RegisterRegistrationComplete;
use App\Jobs\RegisterScrollUpdate;

class WebhookController extends Controller {
    //

    public function handle(Request $request) {

        switch ($request->header('X-Tracker-Webhook-Event')) {
            case "PAGE_VIEW":
                dispatch(new RegisterPageViewEvent($request->all()));
                break;
            case "ARTICLE_VIEW":
                dispatch(new RegisterArticleViewEvent($request->all()));
                break;
            case "DID_VISIT_FORM":
                dispatch(new RegisterPageViewEvent($request->all()));
                break;
            case "SCROLL_UPDATE":
                dispatch(new RegisterScrollUpdate($request->all()));
                break;
            case "DID_PRESS_BUTTON":
                dispatch(new RegisterButtonPress($request->all()));
                break;
            case "DID_SUBMIT_FORM":
                dispatch(new RegisterFormSubmission($request->all()));
                break;
            case "DID_REGISTRATION_COMPLETE":
                dispatch(new RegisterRegistrationComplete($request->all()));
                break;
        }
    }
}
