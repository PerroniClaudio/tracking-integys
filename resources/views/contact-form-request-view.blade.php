<x-layouts.app>

    <div class="border-b border-base-200 pb-8 px-4">
        <div class="container mx-auto lg:px-4 flex flex-col gap-2 lg:flex-row lg:items-center justify-between ">
            <div>
                <h1 class="text-4xl">{{ __('navbar.contact_request') }} #{{ $request->id }}</h1>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 pb-16 -mt-8">
        <div class="card card-border bg-base-100 w-full">
            <div class="card-body">
                <div class="grid lg:grid-cols-2 gap-4">
                    <div class="card card-border bg-base-100 w-full">
                        <div class="card-body">
                            <h3 class="text-2xl card-title">{{ __('contact_requests.form_data') }}</h3>
                            <div class="lg:grid lg:grid-cols-2 gap-4">
                                <fieldset class="fieldset">
                                    <legend class="fieldset-legend">{{ __('contact_requests.name') }}</legend>
                                    <input type="text" class="input w-full" value="{{ $request->name }}" disabled />
                                </fieldset>
                                <fieldset class="fieldset">
                                    <legend class="fieldset-legend">{{ __('contact_requests.email') }}</legend>
                                    <input type="text" class="input w-full" value="{{ $request->email }}" disabled />
                                </fieldset>
                                <fieldset class="fieldset">
                                    <legend class="fieldset-legend">{{ __('contact_requests.business_name') }}</legend>
                                    <input type="text" class="input w-full" value="{{ $request->business_name }}"
                                        disabled />
                                </fieldset>
                                <fieldset class="fieldset">
                                    <legend class="fieldset-legend">{{ __('contact_requests.created_at') }}</legend>
                                    <input type="datetime" class="input w-full"
                                        value="{{ $request->created_at->format('d/m/Y H:i') }}" disabled />
                                </fieldset>
                                <fieldset class="fieldset col-span-2">
                                    <legend class="fieldset-legend">{{ __('contact_requests.message') }}</legend>
                                    <textarea class="textarea min-h-[30vh] w-full" disabled>{{ $request->message }}</textarea>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="card card-border bg-base-100 w-full">
                        <div class="card-body">
                            <h3 class="text-2xl card-title">{{ __('contact_requests.tracked_data') }}</h3>
                            <div class="card card-border bg-base-100 w-full">
                                <div class="card-body">
                                    <h4 class="card-title">{{ __('contact_requests.provenance') }}</h4>
                                    <div class="overflow-x-auto">
                                        <table class="table w-full">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('contact_requests.country') }}</th>
                                                    <th>{{ __('contact_requests.city') }}</th>
                                                    <th>{{ __('contact_requests.ip_address') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $request->trackedEvent->ip_country }}</td>
                                                    <td>{{ $request->trackedEvent->ip_city }}</td>
                                                    <td>{{ $request->trackedEvent->ip_address }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-border bg-base-100 w-full">
                                <div class="card-body">
                                    <h4 class="card-title">{{ __('contact_requests.device_used') }}</h4>
                                    <div class="overflow-x-auto">
                                        <table class="table w-full">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('contact_requests.device') }}</th>
                                                    <th>{{ __('contact_requests.browser') }}</th>
                                                    <th>{{ __('contact_requests.os') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ ucfirst($device['name']) }}</td>
                                                    <td>{{ $browser }}</td>
                                                    <td>{{ $device['os'] }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-border bg-base-100 w-full">
                                <div class="card-body">
                                    <h4 class="card-title">{{ __('contact_requests.user_activity') }}</h4>
                                    <div class="overflow-x-auto">
                                        <table class="table w-full">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('contact_requests.page') }}</th>
                                                    <th>{{ __('contact_requests.action') }}</th>
                                                    <th>{{ __('contact_requests.time') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($user_activity as $page)
                                                    <tr>
                                                        <td>{{ $page->url }}</td>
                                                        <td>{{ $page->event_code }}</td>
                                                        <td>{{ $page->created_at->format('d/m/Y H:i') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</x-layouts.app>
