<x-layouts.app>
    <div class="border-b border-base-200 pb-8 px-4">
        <div class="container mx-auto lg:px-4 flex flex-col gap-2 lg:flex-row lg:items-center justify-between ">
            <div>
                <h1 class="text-4xl">{{ __('navbar.website_visits') }}</h1>
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
                <div class="join">
                    <div class="btn btn-primary rounded-l" onclick="choose_date_modal.showModal()">
                        <x-lucide-calendar class="h-6 w-6 text-primary-content" />
                    </div>
                    <select class="select select-md rounded-r w-full" id="dateRange">
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

                    <input type="radio" name="my_tabs_3" class="tab"
                        aria-label="{{ __('website_visits.bounce_rate') }}" />
                    <div class="tab-content bg-base-100 border-base-300 p-6">

                        <canvas id="bounce_rate" class="w-full "></canvas>
                    </div>
                </div>

                <div class="grid lg:grid-cols-3 gap-2 ">
                    <div class="card card-border bg-base-100 w-full lg:col-span-2">
                        <div class="card-body">
                            <h4 class="text-lg">{{ __('website_visits.most_visited_pages') }}</h4>
                            <canvas id="piuvisitate" class="w-full"></canvas>
                        </div>
                    </div>
                    <div class="card card-border bg-base-100 w-full">
                        <div class="card-body">
                            <h4 class="text-lg">{{ __('website_visits.most_visited_pages') }}</h4>
                            <canvas id="provenienza" class="w-full"></canvas>
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-[#411D20] rounded-full"></div>
                                    <span>{{ __('website_visits.organic') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-[#172D3E] rounded-full"></div>
                                    <span>{{ __('website_visits.direct') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-[#413517] rounded-full"></div>
                                    <span>{{ __('website_visits.social') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-[#1F3431] rounded-full"></div>
                                    <span>{{ __('website_visits.referral') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-[#291A3E] rounded-full"></div>
                                    <span>{{ __('website_visits.email') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="tabs tabs-lift">
                    <input type="radio" name="my_tabs_4" class="tab"
                        aria-label="{{ __('website_visits.devices') }}" checked="checked" />
                    <div class="tab-content bg-base-100 border-base-300 p-6">
                        <canvas id="devices" class="w-full"></canvas>
                    </div>

                    <input type="radio" name="my_tabs_4" class="tab"
                        aria-label="{{ __('website_visits.browsers') }}" />
                    <div class="tab-content bg-base-100 border-base-300 p-6"><canvas id="browsers"
                            class="w-full"></canvas></div>

                    <input type="radio" name="my_tabs_4" class="tab"
                        aria-label="{{ __('website_visits.operating_systems') }}" />
                    <div class="tab-content bg-base-100 border-base-300 p-6"><canvas id="os"
                            class="w-full"></canvas></div>
                </div>

                <div class="grid lg:grid-cols-2 gap-2 mb-16">
                    <div class="card card-border bg-base-100 w-full">
                        <div class="card-body">
                            <h4 class="text-lg">{{ __('website_visits.nations') }}</h4>
                            <canvas id="nations" class="w-full"></canvas>
                        </div>
                    </div>
                    <div class="card card-border bg-base-100 w-full">
                        <div class="card-body">
                            <h4 class="text-lg">{{ __('website_visits.cities') }}</h4>
                            <canvas id="cities" class="w-full"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <dialog id="choose_date_modal" class="modal">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="text-lg font-bold">Seleziona date</h3>

            <div class="grid grid-cols-2 gap-2">
                <input type="date" class="input input-bordered" id="start_date" value="{{ $start_date ?? '' }}">
                <input type="date" class="input input-bordered" id="end_date" value="{{ $end_date ?? '' }}">
            </div>

            <x-dismissable-alert type="error" id="choose-date-modal-error-box" message="" />

            <div class="flex justify-end gap-2 mt-4">
                <button class="btn btn-primary" id="custom-date-filter">Filtra</button>
            </div>
        </div>
    </dialog>

    @push('scripts')
        @vite('resources/js/webvisits.js')
    @endpush
</x-layouts.app>
