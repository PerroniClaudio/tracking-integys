<?php

namespace App\Http\Controllers;

use App\Models\TrackedEvents;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Reefki\DeviceDetector\Device;
use DeviceDetector\Parser\Client\Browser;
use App\Config\UrlMapping;
use Illuminate\Support\Facades\DB;

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

                    $start_date = Carbon::createFromFormat('Y-m-d', $request->start_date);
                    $end_date = Carbon::createFromFormat('Y-m-d', $request->end_date);

                    return view('home', [
                        'total_visits' => $this->getTotalVisits($start_date, $end_date),
                        'unique_users' => $this->getUniqueUsers($start_date, $end_date),
                        'average_page_view' => $this->calculateAveragePageView($start_date, $end_date),
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

    public function websiteVisits(Request $request) {

        $different_domains = TrackedEvents::select('url')->distinct()->get();
        $domain_list = [];

        foreach ($different_domains as $domain) {
            if (!in_array($domain->domain(), $domain_list)) {
                $domain_list[] = $domain->domain();
            }
        }

        return view('website-visits', [
            'domain' => $request->domain ?? $domain_list[0],
            'domain_list' => $domain_list,
            'precision' => $request->precision ?? "today"
        ]);
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
        $events = TrackedEvents::where('created_at', '>=', $start_date->startOfDay())
            ->where('created_at', '<=', $end_date->endOfDay())
            ->whereIn('event_code', [
                "PAGE_VIEW",
                "ARTICLE_VIEW"
            ])
            ->get();
    
        $uniqueUsers = $events->unique(function ($item) {
            return $item['ip_address'] . $item['session_id'];
        });
    
        return $uniqueUsers->count();
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
        $domain = $request->domain ?? "";

        switch ($request->precision) {
            case "today":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfDay())
                    ->where('created_at', '<=', Carbon::now()->endOfDay())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->where('url', 'like', '%' . $domain . '%')
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
                    ->where('url', 'like', '%' . $domain . '%')
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
                    ->where('url', 'like', '%' . $domain . '%')
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
                    ->where('url', 'like', '%' . $domain . '%')
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
                    ->where('url', 'like', '%' . $domain . '%')
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
                    ->where('url', 'like', '%' . $domain . '%')
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

    public function getUniqueUsersDomain(Request $request) {
        $visits = [];
        $domain = $request->domain ?? "";

        switch ($request->precision) {
            case "today":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfDay())
                    ->where('created_at', '<=', Carbon::now()->endOfDay())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->where('url', 'like', '%' . $domain . '%')
                    ->groupBy('ip_address', 'session_id')
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
                    ->where('url', 'like', '%' . $domain . '%')
                    ->groupBy('ip_address', 'session_id')
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
                    ->where('url', 'like', '%' . $domain . '%')
                    ->groupBy('ip_address', 'session_id')
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
                    ->where('url', 'like', '%' . $domain . '%')
                    ->groupBy('ip_address', 'session_id')
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
                    ->where('url', 'like', '%' . $domain . '%')
                    ->groupBy('ip_address', 'session_id')
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
                    ->where('url', 'like', '%' . $domain . '%')
                    ->groupBy('ip_address', 'session_id')
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
        $domain = $request->domain ?? "";
        switch ($request->precision) {
            case "today":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfDay())
                    ->where('created_at', '<=', Carbon::now()->endOfDay())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->where('url', 'like', '%' . $domain . '%')
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
                    ->where('url', 'like', '%' . $domain . '%')
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
                    ->where('url', 'like', '%' . $domain . '%')
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
                    ->where('url', 'like', '%' . $domain . '%')
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
                    ->where('url', 'like', '%' . $domain . '%')
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
                    ->where('url', 'like', '%' . $domain . '%')
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
        $domain = $request->domain ?? "";

        switch ($request->precision) {
            case "today":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfDay())
                    ->where('created_at', '<=', Carbon::now()->endOfDay())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->where('url', 'like', '%' . $domain . '%')
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
                    ->where('url', 'like', '%' . $domain . '%')
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
                    ->where('url', 'like', '%' . $domain . '%')
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
                    ->where('url', 'like', '%' . $domain . '%')
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
                    ->where('url', 'like', '%' . $domain . '%')
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
                    ->where('url', 'like', '%' . $domain . '%')
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

    public function calculateBounceRate(Request $request) {
        $domain = $request->domain ?? "";

        switch ($request->precision) {
            case "today":
            case "week":
                $start_date = Carbon::now()->startOfDay();
                $end_date = Carbon::now()->endOfDay();
                $groupByFormat = 'Y-m-d';
                break;
            case "month":
                $start_date = Carbon::now()->startOfMonth();
                $end_date = Carbon::now()->endOfMonth();
                $groupByFormat = 'Y-W'; // Group by week
                break;
            case "sixmonths":
                $start_date = Carbon::now()->startOfMonth()->subMonths(6);
                $end_date = Carbon::now()->endOfMonth();
                $groupByFormat = 'Y-m';
                break;
            case "fullyear":
                $start_date = Carbon::now()->startOfYear();
                $end_date = Carbon::now()->endOfYear();
                $groupByFormat = 'Y-m';
                break;
            case "custom":
                $start_date = Carbon::createFromFormat('Y-m-d', $request->start_date);
                $end_date = Carbon::createFromFormat('Y-m-d', $request->end_date);
                $diffInDays = $start_date->diffInDays($end_date);
                if ($diffInDays <= 7) {
                    $groupByFormat = 'Y-m-d';
                } elseif ($diffInDays <= 30) {
                    $groupByFormat = 'Y-W'; // Group by week
                } else {
                    $groupByFormat = 'Y-m';
                }
                break;
            default:
                return response()->json([
                    'message' => 'Scegli una precision tra: today, week, month, sixmonths, fullyear, custom'
                ]);
        }

        // Get total visits grouped by date
        $totalVisits = TrackedEvents::where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->where('url', 'like', '%' . $domain . '%')
            ->get()
            ->groupBy(function ($date) use ($groupByFormat) {
                return Carbon::parse($date->created_at)->format($groupByFormat);
            });

        // Get bounce visits grouped by date
        $bounceVisits = TrackedEvents::where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->where('url', 'like', '%' . $domain . '%')
            ->get()
            ->groupBy(function ($date) use ($groupByFormat) {
                return Carbon::parse($date->created_at)->format($groupByFormat);
            })
            ->map(function ($group) {
                return $group->groupBy('session_id')->filter(function ($session) {
                    return $session->count() === 1;
                })->count();
            });

        $bounceRateData = [];

        // Calculate bounce rate per date interval
        foreach ($totalVisits as $date => $data) {
            $bounceVisitsCount = $bounceVisits[$date] ?? 0;
            $totalVisitsCount = $data->count();
            $bounceRate = $totalVisitsCount > 0 ? ($bounceVisitsCount / $totalVisitsCount) * 100 : 0;
            $bounceRateData[] = [
                "date" => $date,
                "rate" => round($bounceRate, 2)
            ];
        }

        return response()->json($bounceRateData);
    }

    public function devices(Request $request) {

        $domain = $request->domain ?? "";
        $devices_cache = [];
        $devices = [];
        $operating_systems = [];
        $browsers = [];

        switch ($request->precision) {
            case "today":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfDay())
                    ->where('created_at', '<=', Carbon::now()->endOfDay())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->where('url', 'like', '%' . $domain . '%')
                    ->orderBy('created_at')
                    ->get();

                break;
            case "week":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfWeek())
                    ->where('created_at', '<=', Carbon::now()->endOfWeek())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->where('url', 'like', '%' . $domain . '%')
                    ->orderBy('created_at')
                    ->get();



                break;
            case "month":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfMonth())
                    ->where('created_at', '<=', Carbon::now()->endOfMonth())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->where('url', 'like', '%' . $domain . '%')
                    ->orderBy('created_at')
                    ->get();



                break;
            case "sixmonths":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfMonth()->subMonths(6))
                    ->where('created_at', '<=', Carbon::now()->endOfMonth())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->where('url', 'like', '%' . $domain . '%')
                    ->orderBy('created_at')
                    ->get();


                break;
            case "fullyear":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfYear())
                    ->where('created_at', '<=', Carbon::now()->endOfYear())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->where('url', 'like', '%' . $domain . '%')
                    ->orderBy('created_at')
                    ->get();

                break;
            case "custom":

                $events = TrackedEvents::where('created_at', '>=', $request->start_date)
                    ->where('created_at', '<=', $request->end_date)
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->where('url', 'like', '%' . $domain . '%')
                    ->orderBy('created_at')
                    ->get();

                break;
            default:
                return response()->json([
                    'message' => 'Scegli una precision tra: today, week, month, sixmonths, fullyear, custom'
                ]);
                break;
        }

        foreach ($events as $event) {

            if (!isset($devices_cache[$event->user_agent])) {
                $device = Device::detect($event->user_agent);
                $device->parse();

                if ($device->isBot()) {
                    continue;
                }

                $os = $device->getOs();

                if ($os["version"] === "") {
                    $os_name = $os["name"];
                } else {
                    $os_name = $os["name"] . " " . $os["version"];
                }

                $devices_cache[$event->user_agent] = [
                    'device' => ucfirst($device->getDeviceName()),
                    'os' => $os_name,
                    'browser' => Browser::getBrowserFamily($device->getClient('name'))
                ];

                $device = $devices_cache[$event->user_agent];
            } else {
                $device = $devices_cache[$event->user_agent];
            }


            if (!isset($devices[$device['device']])) {
                $devices[$device['device']] = 0;
            } else {
                $devices[$device['device']]++;
            }

            if (!isset($operating_systems[$device['os']])) {
                $operating_systems[$device['os']] = 0;
            } else {
                $operating_systems[$device['os']]++;
            }

            if (!isset($browsers[$device['browser']])) {
                $browsers[$device['browser']] = 0;
            } else {
                $browsers[$device['browser']]++;
            }
        }

        arsort($devices);
        arsort($operating_systems);
        arsort($browsers);

        $devices = array_slice($devices, 0, 5, true);
        $operating_systems = array_slice($operating_systems, 0, 5, true);
        $browsers = array_slice($browsers, 0, 5, true);

        return response()->json([
            'devices' => $devices,
            'operating_systems' => $operating_systems,
            'browsers' => $browsers
        ]);
    }

    public function provenance(Request $request) {
        $domain = $request->domain ?? "";
        $nations = [];
        $cities = [];

        switch ($request->precision) {
            case "today":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfDay())
                    ->where('created_at', '<=', Carbon::now()->endOfDay())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->where('url', 'like', '%' . $domain . '%')
                    ->orderBy('created_at')
                    ->get();

                break;
            case "week":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfWeek())
                    ->where('created_at', '<=', Carbon::now()->endOfWeek())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->where('url', 'like', '%' . $domain . '%')
                    ->orderBy('created_at')
                    ->get();



                break;
            case "month":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfMonth())
                    ->where('created_at', '<=', Carbon::now()->endOfMonth())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->where('url', 'like', '%' . $domain . '%')
                    ->orderBy('created_at')
                    ->get();



                break;
            case "sixmonths":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfMonth()->subMonths(6))
                    ->where('created_at', '<=', Carbon::now()->endOfMonth())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->where('url', 'like', '%' . $domain . '%')
                    ->orderBy('created_at')
                    ->get();


                break;
            case "fullyear":

                $events = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfYear())
                    ->where('created_at', '<=', Carbon::now()->endOfYear())
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->where('url', 'like', '%' . $domain . '%')
                    ->orderBy('created_at')
                    ->get();

                break;
            case "custom":

                $events = TrackedEvents::where('created_at', '>=', $request->start_date)
                    ->where('created_at', '<=', $request->end_date)
                    ->whereIn('event_code', [
                        "PAGE_VIEW",
                        "ARTICLE_VIEW"
                    ])
                    ->where('url', 'like', '%' . $domain . '%')
                    ->orderBy('created_at')
                    ->get();

                break;
            default:
                return response()->json([
                    'message' => 'Scegli una precision tra: today, week, month, sixmonths, fullyear, custom'
                ]);
                break;
        }

        foreach ($events as $event) {
            if (!isset($nations[$event->ip_country])) {
                $nations[$event->ip_country] = 0;
            } else {
                $nations[$event->ip_country]++;
            }

            if (!isset($cities[$event->ip_city])) {
                $cities[$event->ip_city] = 0;
            } else {
                $cities[$event->ip_city]++;
            }
        }

        arsort($nations);
        arsort($cities);

        $nations = array_slice($nations, 0, 5, true);
        $cities = array_slice($cities, 0, 5, true);

        return response()->json([
            'nations' => $nations,
            'cities' => $cities,
        ]);
    }

    public function privateAreaUsers(Request $request) {

        $different_domains = TrackedEvents::select('url')->distinct()->get();
        $domain_list = [];

        foreach ($different_domains as $domain) {
            if (!in_array($domain->domain(), $domain_list)) {
                $domain_list[] = $domain->domain();
            }
        }

        $selected_domain = $request->domain ?? $domain_list[0];
        $users = $this->getUsersFromDomain($selected_domain);

        return view('private-area-users', [
            'domain_list' => $domain_list,
            'domain' => $selected_domain
        ]);
    }

    private function getUsersFromDomain($domain) {
        $envVariable = UrlMapping::getPrefix($domain);

        $connection = DB::connection($envVariable);

        return [];
    }

    public function test() {
        $uniqueUsersCount = TrackedEvents::where('created_at', '>=', Carbon::now()->startOfMonth()->subMonths(6))
            ->where('created_at', '<=', Carbon::now()->endOfMonth())
            ->whereIn('event_code', [
                "PAGE_VIEW",
                "ARTICLE_VIEW"
            ])
            ->groupBy('ip_address', 'session_id')
            ->orderBy('created_at');

        dd($uniqueUsersCount->get());
    }
}
