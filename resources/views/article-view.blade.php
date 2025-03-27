<x-layouts.app>
    <div class="border-b border-base-200 pb-8 px-4">
        <div class="container mx-auto lg:px-4 flex flex-col gap-2 lg:flex-row lg:items-center justify-between ">
            <div class="flex-1">
                <h1 class="text-4xl">{{ __('navbar.article_visit') }}</h1>

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

    <div class="container mx-auto px-4 pb-16 -mt-8">
        <div class="card card-border bg-base-100 w-full">

            <input type="hidden" name="domain" id="domain" value="{{ $url }}">

            <div class="card-body">

                <div class="grid lg:grid-cols-2 items-center gap-4">

                    <div class="card card-border">
                        <div class="card-body">
                            <h4 class="text-lg">{{ __('article_visits.total_visits') }}</h4>
                            <p class="text-4xl">{{ $articleViews->count() }}</p>
                        </div>
                    </div>

                    <div class="card card-border">
                        <div class="card-body">
                            <h4 class="text-lg">{{ __('article_visits.avg_scroll_percentage') }}</h4>
                            <p class="text-4xl">{{ $average_scroll }}%</p>
                        </div>
                    </div>

                </div>

                <a href="{{ $url }}" target="_blank" class="btn btn-primary">
                    {{ __('article_visits.visit_original_article') }}
                </a>

                <div class="grid lg:grid-cols-3 gap-4">
                    <div class="card card-border lg:col-span-2">
                        <div class="card-body">

                            <h4 class="text-lg">{{ __('article_visits.visits_over_time') }}</h4>
                            <canvas id="andamento" class="w-full"></canvas>
                        </div>
                    </div>

                    <div class="card card-border">
                        <div class="card-body">
                            <h4 class="text-lg">{{ __('article_visits.visits_by_country') }}</h4>
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
        @vite('resources/js/articleviews.js')
    @endpush
</x-layouts.app>
