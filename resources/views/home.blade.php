<x-layouts.app>
    <div class="border-b border-base-200 pb-8 px-4">
        <div class="container mx-auto px-4 flex items-center justify-between ">
            <div>
                <h1 class="text-4xl">{{ __('navbar.home') }}</h1>
            </div>
            <div>
                <div class="join">
                    <div class="btn btn-primary rounded-l">
                        <x-lucide-calendar class="h-6 w-6 text-primary-content" />
                    </div>
                    <select class="select select-md rounded-r">
                        <option disabled selected>Oggi</option>
                        <option>Ultimi 7 giorni</option>
                        <option>Mese corrente</option>
                        <option>Ultimi 6 mesi</option>
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
                            <p>15.487</p>
                        </div>
                    </div>
                    <div class="card card-border">
                        <div class="card-body">
                            <h4 class="text-lg">Utenti unici</h4>
                            <p>9.253</p>
                        </div>
                    </div>
                    <div class="card card-border">
                        <div class="card-body">
                            <h4 class="text-lg">Visite per pagina</h4>
                            <p>2.5</p>
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
                    <div class="card card-border">
                        <div class="card-body">
                            <h4 class="text-lg">Percorsi di Navigazione degli Utenti</h4>
                            <canvas id="navigationFlowChart" class="w-full"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-layouts.app>
