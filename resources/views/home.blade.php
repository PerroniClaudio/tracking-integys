<x-layouts.app>
    <div class="border-b border-base-200 pb-8 px-4">
        <div class="container mx-auto px-4 flex items-center justify-between ">
            <div>
                <h1 class="text-4xl">{{ __('navbar.home') }}</h1>
            </div>
            <div>
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
                <div class="grid lg:grid-cols-4 gap-2">
                    <div class="card card-border">
                        <div class="card-body">
                            <h4 class="text-lg">Visite totali</h4>
                            <p>{{ $total_visits }}</p>
                        </div>
                    </div>
                    <div class="card card-border">
                        <div class="card-body">
                            <h4 class="text-lg">Utenti unici</h4>
                            <p>{{ $unique_users }}</p>
                        </div>
                    </div>
                    <div class="card card-border">
                        <div class="card-body">
                            <h4 class="text-lg">Visite per pagina</h4>
                            <p>{{ $average_page_view }}</p>
                        </div>
                    </div>
                    <div class="card card-border">
                        <div class="card-body">
                            <h4 class="text-lg">Durata sessione</h4>
                            <p>00:02:45</p>
                        </div>
                    </div>
                </div>

                <div class="grid lg:grid-cols-4 gap-2">
                    <div class="card card-border lg:col-span-3">
                        <div class="card-body">
                            <h4 class="text-lg">Andamento visite nel tempo</h4>
                            <canvas id="andamento" class="w-full"></canvas>
                        </div>
                    </div>

                    <div class="card card-border">
                        <div class="card-body">
                            <h4 class="text-lg">Sorgenti di traffico</h4>
                            <canvas id="provenienza" class="w-full"></canvas>
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-[#411D20] rounded-full"></div>
                                    <span>Organico</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-[#172D3E] rounded-full"></div>
                                    <span>Diretto</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-[#413517] rounded-full"></div>
                                    <span>Social</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-[#1F3431] rounded-full"></div>
                                    <span>Referral</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-[#291A3E] rounded-full"></div>
                                    <span>Email</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid lg:grid-cols-2 gap-2">
                    <div class="card card-border">
                        <div class="card-body">
                            <h4 class="text-lg">Pagine più visitate</h4>
                            <canvas id="piuvisitate" class="w-full"></canvas>
                        </div>
                    </div>
                    <div class="card card-border hidden">
                        <div class="card-body">
                            <h4 class="text-lg">Percorsi di Navigazione degli Utenti</h4>
                            <canvas id="navigationFlowChart" class="w-full"></canvas>
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
        @vite('resources/js/homepage.js')
    @endpush
</x-layouts.app>
