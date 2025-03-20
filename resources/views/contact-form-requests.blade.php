<x-layouts.app>

    <div class="border-b border-base-200 pb-8 px-4">
        <div class="container mx-auto lg:px-4 flex flex-col gap-2 lg:flex-row lg:items-center justify-between ">
            <div>
                <h1 class="text-4xl">{{ __('navbar.contact_requests') }}</h1>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 pb-16 -mt-8">
        <div class="card card-border bg-base-100 w-full">
            <div class="card-body">
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>{{ __('contact_requests.name') }}</th>
                                <th>{{ __('contact_requests.email') }}</th>
                                <th>{{ __('contact_requests.business_name') }}</th>
                                <th>{{ __('contact_requests.created_at') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $request)
                                <tr>
                                    <td>{{ $request->name }}</td>
                                    <td>{{ $request->email }}</td>
                                    <td>{{ $request->business_name }}</td>
                                    <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                    <th>
                                        <a href="{{ route('contact-form-request.view', $request->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <x-lucide-arrow-right class="w-4 h-4 mr-1" />
                                        </a>
                                    </th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $requests->links() }}
            </div>
        </div>
    </div>

</x-layouts.app>
