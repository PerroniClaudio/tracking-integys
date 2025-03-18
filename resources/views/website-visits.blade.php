<x-layouts.app>
    <div class="border-b border-base-200 pb-8 px-4">
        <div class="container mx-auto px-4 flex items-center justify-between ">
            <div>
                <h1 class="text-4xl">{{ __('navbar.website_visits') }}</h1>
            </div>
            <div>
                <div class="join">
                    <div class="btn btn-primary rounded-l">
                        <x-lucide-globe class="h-6 w-6 text-primary-content" />
                    </div>
                    <select class="select select-md rounded-r" id="websites">
                        @foreach ($domain_list as $domain_item)
                            <option value="{{ $domain_item }}" {{ $domain_item == $domain ? 'selected' : '' }}>
                                {{ $domain_item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="join">
                    <div class="btn btn-primary rounded-l" onclick="choose_date_modal.showModal()">
                        <x-lucide-calendar class="h-6 w-6 text-primary-content" />
                    </div>
                    <select class="select select-md rounded-r" id="dateRange">
                        <option value="today" {{ $precision == 'today' ? 'selected' : '' }}>Oggi</option>
                        <option value="week" {{ $precision == 'week' ? 'selected' : '' }}>Ultimi 7 giorni</option>
                        <option value="month" {{ $precision == 'month' ? 'selected' : '' }}>Mese corrente</option>
                        <option value="sixmonths" {{ $precision == 'sixmonths' ? 'selected' : '' }}>Ultimi 6 mesi
                        </option>
                        <option value="fullyear" {{ $precision == 'fullyear' ? 'selected' : '' }}>Ultimo anno</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 pb-16 -mt-8">
        <div class="card card-border bg-base-100 w-full">
            <div class="card-body">
                <div class="tabs tabs-lift">
                    <input type="radio" name="my_tabs_3" class="tab"
                        aria-label="{{ __('website_visits.unique_visitors') }}" checked="checked" />
                    <div class="tab-content bg-base-100 border-base-300 p-6">
                        <canvas id="unique_visitors" class="w-full"></canvas>
                    </div>

                    <input type="radio" name="my_tabs_3" class="tab"
                        aria-label="{{ __('website_visits.page_views') }}" />
                    <div class="tab-content bg-base-100 border-base-300 p-6">

                        <canvas id="visits_total" class="w-full "></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/webvisits.js')
    @endpush
</x-layouts.app>
