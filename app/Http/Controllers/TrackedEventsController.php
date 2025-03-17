<?php

namespace App\Http\Controllers;

use App\Models\TrackedEvents;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TrackedEventsController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        //

        if (isset($request->precision)) {
            switch ($request->precision) {
                case "today":
                    return view('home', [
                        'total_visits' => $this->getTotalVisits(Carbon::now()->startOfDay(), Carbon::now()->endOfDay()),
                        'unique_users' => $this->getUniqueUsers(Carbon::now()->startOfDay(), Carbon::now()->endOfDay()),
                        'average_page_view' => $this->calculateAveragePageView(Carbon::now()->startOfDay(), Carbon::now()->endOfDay()),
                        'precision' => $request->precision
                    ]);
                    break;
                case "week":
                    return view('home', [
                        'total_visits' => $this->getTotalVisits(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()),
                        'unique_users' => $this->getUniqueUsers(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()),
                        'average_page_view' => $this->calculateAveragePageView(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()),
                        'precision' => $request->precision
                    ]);
                    break;
                case "month":
                    return view('home', [
                        'total_visits' => $this->getTotalVisits(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()),
                        'unique_users' => $this->getUniqueUsers(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()),
                        'average_page_view' => $this->calculateAveragePageView(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()),
                        'precision' => $request->precision
                    ]);
                    break;
                case "sixmonths":
                    return view('home', [
                        'total_visits' => $this->getTotalVisits(Carbon::now()->startOfMonth()->subMonths(6), Carbon::now()->endOfMonth()),
                        'unique_users' => $this->getUniqueUsers(Carbon::now()->startOfMonth()->subMonths(6), Carbon::now()->endOfMonth()),
                        'average_page_view' => $this->calculateAveragePageView(Carbon::now()->startOfMonth()->subMonths(6), Carbon::now()->endOfMonth()),
                        'precision' => $request->precision
                    ]);
                    break;
                case "fullyear":
                    return view('home', [
                        'total_visits' => $this->getTotalVisits(Carbon::now()->startOfYear(), Carbon::now()->endOfYear()),
                        'unique_users' => $this->getUniqueUsers(Carbon::now()->startOfYear(), Carbon::now()->endOfYear()),
                        'average_page_view' => $this->calculateAveragePageView(Carbon::now()->startOfYear(), Carbon::now()->endOfYear()),
                        'precision' => $request->precision
                    ]);
                    break;
                case "custom":
                    return view('home', [
                        'total_visits' => $this->getTotalVisits($request->start_date, $request->end_date),
                        'unique_users' => $this->getUniqueUsers($request->start_date, $request->end_date),
                        'average_page_view' => $this->calculateAveragePageView($request->start_date, $request->end_date),
                        'precision' => $request->precision
                    ]);
                    break;
                default:
                    return view('home', [
                        'total_visits' => $this->getTotalVisits(Carbon::now()->startOfDay(), Carbon::now()->endOfDay()),
                        'unique_users' => $this->getUniqueUsers(Carbon::now()->startOfDay(), Carbon::now()->endOfDay()),
                        'average_page_view' => $this->calculateAveragePageView(Carbon::now()->startOfDay(), Carbon::now()->endOfDay()),
                        'precision' => "today"
                    ]);
                    break;
            }
        } else {
            return view('home', [
                'total_visits' => $this->getTotalVisits(Carbon::now()->startOfDay(), Carbon::now()->endOfDay()),
                'unique_users' => $this->getUniqueUsers(Carbon::now()->startOfDay(), Carbon::now()->endOfDay()),
                'average_page_view' => $this->calculateAveragePageView(Carbon::now()->startOfDay(), Carbon::now()->endOfDay()),
                'precision' => "today"
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TrackedEvents $trackedEvents) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TrackedEvents $trackedEvents) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TrackedEvents $trackedEvents) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrackedEvents $trackedEvents) {
        //
    }

    private function getTotalVisits(Carbon $start_date, Carbon $end_date) {

        $events = TrackedEvents::where('created_at', '>=', $start_date->startOfDay())
            ->where('created_at', '<=', $end_date->endOfDay())
            ->whereIn('event_code', [
                "PAGE_VIEW",
                "ARTICLE_VIEW"
            ])
            ->get();

        return $events->count();
    }

    private function getUniqueUsers(Carbon $start_date, Carbon $end_date) {
        $uniqueUsersCount = TrackedEvents::where('created_at', '>=', $start_date->startOfDay())
            ->where('created_at', '<=', $end_date->endOfDay())
            ->whereIn('event_code', [
                "PAGE_VIEW",
                "ARTICLE_VIEW"
            ])
            ->select('ip_address', 'session_id')
            ->distinct()
            ->count();
        return $uniqueUsersCount;
    }

    private function calculateAveragePageView(Carbon $start_date, Carbon $end_date) {

        $events = TrackedEvents::where('created_at', '>=', $start_date->startOfDay())
            ->where('created_at', '<=', $end_date->endOfDay())
            ->whereIn('event_code', [
                "PAGE_VIEW",
                "ARTICLE_VIEW"
            ])
            ->get();

        $total_page_views = $events->count();
        $unique_users = TrackedEvents::where('created_at', '>=', $start_date->startOfDay())
            ->where('created_at', '<=', $end_date->endOfDay())
            ->whereIn('event_code', [
                "PAGE_VIEW",
                "ARTICLE_VIEW"
            ])
            ->distinct('ip_address', 'session_id')
            ->get()
            ->count();

        if ($unique_users === 0) {
            return 0;
        }

        return $total_page_views / $unique_users;
    }

    /**
     * La struttura dati è la seguente:
     * [
     *  {
     *     "date": data della visita,
     *      "visits": numero di visite
     *  }
     * ]
     * 
     * La sensibilità cambia a seconda della precisione scelta:
     * - se è su un giorno solo allora mostra il numero di visite per ogni ora
     * - se è su una settimana allora mostra il numero di visite per ogni giorno
     * - se è su un mese allora mostra il numero di visite per ogni giorno
     * - se è su sei mesi allora mostra il numero di visite per ogni mese
     * - se è su un anno allora mostra il numero di visite per ogni mese
     * - se è custom allora va calcolato quanto tempo è passato tra la data di inizio e la data di fine e seguire i criteri precedenti
     */


    public function visits(Request $request) {

        $visits = [];

        switch ($request->precision) {
            case "today":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfDay())
                    ->where('created_at', '<=', Carbon::now()->endOfDay())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->orderBy('created_at')
                    ->get();

                $visits = $events->groupBy(function ($event) {
                    return $event->created_at->format('H');
                })->map(function ($group) {
                    return [
                        'date' => $group->first()->created_at->format('H'),
                        'visits' => $group->count()
                    ];
                });


                break;
            case "week":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfWeek())
                    ->where('created_at', '<=', Carbon::now()->endOfWeek())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->orderBy('created_at')
                    ->get();

                $visits = $events->groupBy(function ($event) {
                    return $event->created_at->format('d/m');
                })->map(function ($group) {
                    return [
                        'date' => $group->first()->created_at->format('d/m'),
                        'visits' => $group->count()
                    ];
                });

                break;
            case "month":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfMonth())
                    ->where('created_at', '<=', Carbon::now()->endOfMonth())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->orderBy('created_at')
                    ->get();

                $visits = $events->groupBy(function ($event) {
                    return $event->created_at->format('d/m');
                })->map(function ($group) {
                    return [
                        'date' => $group->first()->created_at->format('d/m'),
                        'visits' => $group->count()
                    ];
                });

                break;
            case "sixmonths":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfMonth()->subMonths(6))
                    ->where('created_at', '<=', Carbon::now()->endOfMonth())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->orderBy('created_at')
                    ->get();

                $visits = $events->groupBy(function ($event) {
                    return $event->created_at->format('m/Y');
                })->map(function ($group) {
                    return [
                        'date' => $group->first()->created_at->format('m/Y'),
                        'visits' => $group->count()
                    ];
                });

                break;
            case "fullyear":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfYear())
                    ->where('created_at', '<=', Carbon::now()->endOfYear())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->orderBy('created_at')
                    ->get();

                $visits = $events->groupBy(function ($event) {
                    return $event->created_at->format('F');
                })->map(function ($group) {
                    return [
                        'date' => ucfirst($group->first()->created_at->locale('it_IT')->monthName),
                        'visits' => $group->count()
                    ];
                });


                break;
            case "custom":

                $start_date_carbon = Carbon::createFromFormat('Y-m-d', $request->start_date);
                $end_date_carbon = Carbon::createFromFormat('Y-m-d', $request->end_date);

                $events = TrackedEvents::where('created_at', '>=', $request->start_date)
                    ->where('created_at', '<=', $request->end_date)
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->orderBy('created_at')
                    ->get();

                $diff = $start_date_carbon->diffInDays($end_date_carbon);

                if ($diff === 0) {
                    $visits = $events->groupBy(function ($event) {
                        return $event->created_at->format('H');
                    })->map(function ($group) {
                        return [
                            'date' => $group->first()->created_at->format('H'),
                            'visits' => $group->count()
                        ];
                    });
                } else if ($diff <= 7) {
                    $visits = $events->groupBy(function ($event) {
                        return $event->created_at->format('d/m');
                    })->map(function ($group) {
                        return [
                            'date' => $group->first()->created_at->format('d/m'),
                            'visits' => $group->count()
                        ];
                    });
                } else if ($diff <= 30) {
                    $visits = $events->groupBy(function ($event) {
                        return $event->created_at->format('d/m');
                    })->map(function ($group) {
                        return [
                            'date' => $group->first()->created_at->format('d/m'),
                            'visits' => $group->count()
                        ];
                    });
                } else if ($diff <= 180) {
                    $visits = $events->groupBy(function ($event) {
                        return $event->created_at->format('m/Y');
                    })->map(function ($group) {
                        return [
                            'date' => $group->first()->created_at->format('m/Y'),
                            'visits' => $group->count()
                        ];
                    });
                } else {
                    $visits = $events->groupBy(function ($event) {
                        return $event->created_at->format('F');
                    })->map(function ($group) {
                        return [
                            'date' => ucfirst($group->first()->created_at->locale('it_IT')->monthName),
                            'visits' => $group->count()
                        ];
                    });
                }

                break;
            default:

                response()->json([
                    'message' => 'Scegli una precision tra: today, week, month, sixmonths, fullyear, custom'
                ]);

                break;
        }

        $visits = $visits->values()->all();


        return response()->json($visits);
    }

    /**
     * La struttura dei dati è la seguente:
     * [
     *  {source: "email", value: 12}
     * ]
     * 
     */

    public function referers(Request $request) {
        switch ($request->precision) {
            case "today":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfDay())
                    ->where('created_at', '<=', Carbon::now()->endOfDay())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->orderBy('created_at')
                    ->get();

                $referers = $events->groupBy('referer')->map(function ($group) {
                    return [
                        'source' => $group->first()->referer,
                        'value' => $group->count()
                    ];
                });

                break;
            case "week":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfWeek())
                    ->where('created_at', '<=', Carbon::now()->endOfWeek())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->orderBy('created_at')
                    ->get();

                $referers = $events->groupBy('referer')->map(function ($group) {
                    return [
                        'source' => $group->first()->referer,
                        'value' => $group->count()
                    ];
                });

                break;
            case "month":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfMonth())
                    ->where('created_at', '<=', Carbon::now()->endOfMonth())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->orderBy('created_at')
                    ->get();

                $referers = $events->groupBy('referer')->map(function ($group) {
                    return [
                        'source' => $group->first()->referer,
                        'value' => $group->count()
                    ];
                });

                break;
            case "sixmonths":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfMonth()->subMonths(6))
                    ->where('created_at', '<=', Carbon::now()->endOfMonth())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->orderBy('created_at')
                    ->get();

                $referers = $events->groupBy('referer')->map(function ($group) {
                    return [
                        'source' => $group->first()->referer,
                        'value' => $group->count()
                    ];
                });

                break;
            case "fullyear":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfYear())
                    ->where('created_at', '<=', Carbon::now()->endOfYear())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->orderBy('created_at')
                    ->get();

                $referers = $events->groupBy('referer')->map(function ($group) {
                    return [
                        'source' => $group->first()->referer,
                        'value' => $group->count()
                    ];
                });

                break;
            case "custom":

                $events = TrackedEvents::where('created_at', '>=', $request->start_date)
                    ->where('created_at', '<=', $request->end_date)
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->orderBy('created_at')
                    ->get();

                $referers = $events->groupBy('referer')->map(function ($group) {
                    return [
                        'source' => $group->first()->referer,
                        'value' => $group->count()
                    ];
                });

                break;
            default:

                response()->json([
                    'message' => 'Scegli una precision tra: today, week, month, sixmonths, fullyear, custom'
                ]);

                break;
        }

        $referers = $referers->values()->all();

        return response()->json($referers);
    }

    public function mostVisited(Request $request) {
        switch ($request->precision) {
            case "today":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfDay())
                    ->where('created_at', '<=', Carbon::now()->endOfDay())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->orderBy('created_at')
                    ->get();

                $mostVisited = $events->groupBy('url')->map(function ($group) {
                    return [
                        'url' => $group->first()->url,
                        'value' => $group->count()
                    ];
                });

                break;
            case "week":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfWeek())
                    ->where('created_at', '<=', Carbon::now()->endOfWeek())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->orderBy('created_at')
                    ->get();

                $mostVisited = $events->groupBy('url')->map(function ($group) {
                    return [
                        'url' => $group->first()->url,
                        'value' => $group->count()
                    ];
                });

                break;
            case "month":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfMonth())
                    ->where('created_at', '<=', Carbon::now()->endOfMonth())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->orderBy('created_at')
                    ->get();

                $mostVisited = $events->groupBy('url')->map(function ($group) {
                    return [
                        'url' => $group->first()->url,
                        'value' => $group->count()
                    ];
                });

                break;
            case "sixmonths":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfMonth()->subMonths(6))
                    ->where('created_at', '<=', Carbon::now()->endOfMonth())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->orderBy('created_at')
                    ->get();

                $mostVisited = $events->groupBy('url')->map(function ($group) {
                    return [
                        'url' => $group->first()->url,
                        'value' => $group->count()
                    ];
                });

                break;

            case "fullyear":


                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfYear())
                    ->where('created_at', '<=', Carbon::now()->endOfYear())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->orderBy('created_at')
                    ->get();

                $mostVisited = $events->groupBy('url')->map(function ($group) {
                    return [
                        'url' => $group->first()->url,
                        'value' => $group->count()
                    ];
                });

                break;
            case "custom":

                $events = TrackedEvents::where('created_at', '>=', $request->start_date)
                    ->where('created_at', '<=', $request->end_date)
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->orderBy('created_at')
                    ->get();

                $mostVisited = $events->groupBy('url')->map(function ($group) {
                    return [
                        'url' => $group->first()->url,
                        'value' => $group->count()
                    ];
                });

                break;

            default:

                response()->json([
                    'message' => 'Scegli una precision tra: today, week, month, sixmonths, fullyear, custom'
                ]);

                break;
        }

        $mostVisited = $mostVisited->values()->all();

        return response()->json($mostVisited);
    }
}
