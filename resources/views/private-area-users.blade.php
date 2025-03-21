<x-layouts.app>

    <div class="border-b border-base-200 pb-8 px-4">
        <div class="container mx-auto lg:px-4 flex flex-col gap-2 lg:flex-row lg:items-center justify-between ">
            <div>
                <h1 class="text-4xl">{{ __('navbar.private_area_users') }}</h1>
            </div>
            <div class="flex flex-col lg:flex-row gap-2">
                <div class="join">
                    <div class="btn btn-primary rounded-l">
                        <x-lucide-globe class="h-6 w-6 text-primary-content" />
                    </div>
                    <select class="select select-md rounded-r w-full" id="websites">
                        @foreach ($domain_list as $domain_item)
                            <option value="{{ $domain_item }}" {{ $domain_item == $domain ? 'selected' : '' }}>
                                {{ $domain_item }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 pb-16 -mt-8">
        <div class="card card-border bg-base-100 w-full">
            <div class="card-body">

            </div>
        </div>
    </div>

</x-layouts.app>
