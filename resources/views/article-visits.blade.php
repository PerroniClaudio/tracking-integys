<x-layouts.app>
    <div class="border-b border-base-200 pb-8 px-4">
        <div class="container mx-auto lg:px-4 flex flex-col gap-2 lg:flex-row lg:items-center justify-between ">
            <div class="flex-1">
                <h1 class="text-4xl">{{ __('navbar.article_visits') }}</h1>
            </div>
            <div class="flex-1 flex flex-col lg:flex-row gap-2">
                <div class="join flex-1">
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
                <div class="join flex-1">
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
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>{{ __('article_visits.article') }}</th>
                                <th></th>
                            </tr>
                        <tbody>
                            @foreach ($articles as $article)
                                <tr>
                                    <td>{{ $article->url }}</td>
                                    <td>
                                        <a href="{{ route('article-visit.view', [
                                            'url' => base64_encode($article->url),
                                        ]) }}"
                                            class="btn btn-sm btn-primary">
                                            <x-lucide-arrow-right class="w-4 h-4 mr-1" />
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </thead>
                    </table>
                </div>

                {{ $articles->links() }}
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
        @vite('resources/js/articlesviews.js')
    @endpush

</x-layouts.app>
