<x-layouts.app>
    <div class="border-b border-base-200 pb-8 px-4">
        <div class="container mx-auto lg:px-4 flex flex-col gap-2 lg:flex-row lg:items-center justify-between ">
            <div class="flex-1">
                <h1 class="text-4xl">{{ __('navbar.user_activity') }}</h1>
            </div>
        </div>
    </div>

    <div class="container mx-auto flex flex-col gap-4 px-4 pb-16 -mt-8">
        <div class="card card-border bg-base-100 w-full">
            <div class="card-body">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">{{ __('user_activity.user_activity_search') }}</legend>

                    <label for="search-box" class="input w-full">
                        <x-lucide-search class="w-4 h-4" />
                        <input type="text" id="search-box"
                            placeholder="{{ __('user_activity.user_activity_search_placeholder') }}" />
                    </label>
                </fieldset>
            </div>
        </div>

        <section id="results-container" class="w-full grid lg:grid-cols-2 gap-4">

        </section>
    </div>

    <template id="results-card-template-skeleton">
        <div class="card card-border bg-base-100 w-full">
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <div class="skeleton h-16 w-16 shrink-0 rounded-full"></div>
                    <div class="flex flex-col gap-4">
                        <div class="skeleton h-4 w-28"></div>
                        <div class="skeleton h-4 w-20"></div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="results-card-template">
        <a href="/user-activity-log/@email" class="card card-border bg-base-100 w-full">
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <div
                        class="h-16 w-16 shrink-0 rounded-full flex flex-col items-center justify-center bg-primary font-bold text-3xl">
                        <span data-placeholder="initials">
                            @initials
                        </span>
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="">
                            <span data-placeholder="email">
                                @email
                            </span>
                        </div>
                        <div class="flex w-full gap-1 flex-wrap" data-placeholder="badges">
                            @domains
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </template>

    @push('scripts')
        @vite('resources/js/useractivity.js')
    @endpush
</x-layouts.app>
