<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function login(Request $request)
    {
        return view('admin.auth.login');
    }

    public function register(Request $request)
    {
        return view('admin.auth.register');
    }

    public function forgotPassword(Request $request)
    {
        return view('admin.auth.forgot-password');
    }
    /**
     * Admin Dashboard - Overview Page
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        return view('admin.dashboard.index', [
            'stats'        => $this->getStats(),
            'transactions' => $this->getTransactions($request),
            'activities'   => $this->getActivities(),
            'topProducts'  => $this->getTopProducts(),
            'user'         => $user,
        ]);
    }

    // =========================================================
    //  PRIVATE HELPERS (dummy data — replace with DB queries)
    // =========================================================

    /**
     * Stat cards data
     * Replace with: User::count(), Order::sum('total'), etc.
     */
    private function getStats(): array
    {
        return [
            [
                'icon'      => 'fa-solid fa-dollar-sign',
                'value'     => '$84,291',
                'label'     => 'Total Revenue',
                'trend'     => 12.4,
                'sparkline' => $this->sparkline(
                    'M0,28 L15,22 L30,24 L45,14 L60,18 L75,8 L90,12 L105,4 L120,6',
                    '#6366f1', 'g1'
                ),
            ],
            [
                'icon'      => 'fa-solid fa-user-plus',
                'value'     => '2,641',
                'label'     => 'New Users',
                'trend'     => 8.1,
                'sparkline' => $this->sparkline(
                    'M0,24 L15,26 L30,18 L45,20 L60,10 L75,14 L90,6 L105,10 L120,4',
                    '#ec4899', 'g2'
                ),
            ],
            [
                'icon'      => 'fa-solid fa-bag-shopping',
                'value'     => '1,184',
                'label'     => 'Total Orders',
                'trend'     => 5.3,
                'sparkline' => $this->sparkline(
                    'M0,30 L15,24 L30,28 L45,18 L60,22 L75,12 L90,16 L105,6 L120,10',
                    '#10b981', 'g3'
                ),
            ],
            [
                'icon'      => 'fa-solid fa-arrow-trend-down',
                'value'     => '3.24%',
                'label'     => 'Churn Rate',
                'trend'     => -2.7,
                'sparkline' => $this->sparkline(
                    'M0,8 L15,14 L30,10 L45,18 L60,14 L75,22 L90,20 L105,28 L120,26',
                    '#f59e0b', 'g4'
                ),
            ],
        ];
    }

    /**
     * Transactions table with pagination
     * Replace with: Transaction::with('user')->paginate(8)
     */
    private function getTransactions(Request $request): LengthAwarePaginator
    {
        $avatarColors = [
            '#6366f1','#ec4899','#10b981','#f59e0b',
            '#3b82f6','#8b5cf6','#ef4444','#06b6d4',
        ];

        $all = collect([
            ['name' => 'Lena Park',    'email' => 'lena@mail.com',      'date' => 'Apr 30, 2026', 'amount' => '$1,240', 'status' => 'active'],
            ['name' => 'James T.',     'email' => 'james@co.io',         'date' => 'Apr 29, 2026', 'amount' => '$580',   'status' => 'pending'],
            ['name' => 'Mia Chen',     'email' => 'mia.c@gmail.com',     'date' => 'Apr 29, 2026', 'amount' => '$3,100', 'status' => 'active'],
            ['name' => 'Carlos R.',    'email' => 'carlos@biz.net',      'date' => 'Apr 28, 2026', 'amount' => '$749',   'status' => 'inactive'],
            ['name' => 'Sofia Adams',  'email' => 'sofia@mail.org',      'date' => 'Apr 28, 2026', 'amount' => '$2,050', 'status' => 'active'],
            ['name' => 'Noah Blake',   'email' => 'noah@corp.co',        'date' => 'Apr 27, 2026', 'amount' => '$420',   'status' => 'pending'],
            ['name' => 'Aisha M.',     'email' => 'aisha@example.com',   'date' => 'Apr 27, 2026', 'amount' => '$1,890', 'status' => 'active'],
            ['name' => 'Tyler Young',  'email' => 'tyler@web.dev',       'date' => 'Apr 26, 2026', 'amount' => '$660',   'status' => 'active'],
            ['name' => 'Rita Moss',    'email' => 'rmoss@inbox.com',     'date' => 'Apr 26, 2026', 'amount' => '$310',   'status' => 'pending'],
            ['name' => 'Kai Nguyen',   'email' => 'kai.n@studio.io',     'date' => 'Apr 25, 2026', 'amount' => '$4,500', 'status' => 'active'],
            ['name' => 'Elena V.',     'email' => 'elena@vmail.com',     'date' => 'Apr 25, 2026', 'amount' => '$780',   'status' => 'inactive'],
            ['name' => 'Omar S.',      'email' => 'omar.s@corp.net',     'date' => 'Apr 24, 2026', 'amount' => '$2,200', 'status' => 'active'],
            ['name' => 'Pita Havili',  'email' => 'pita@havili.co',      'date' => 'Apr 24, 2026', 'amount' => '$990',   'status' => 'active'],
            ['name' => 'Grace L.',     'email' => 'grace.l@net.io',      'date' => 'Apr 23, 2026', 'amount' => '$460',   'status' => 'pending'],
            ['name' => 'Ben Hart',     'email' => 'bhart@hmail.com',     'date' => 'Apr 23, 2026', 'amount' => '$1,320', 'status' => 'active'],
            ['name' => 'Diana Fox',    'email' => 'diana@foxco.com',     'date' => 'Apr 22, 2026', 'amount' => '$2,870', 'status' => 'active'],
            ['name' => 'Felix W.',     'email' => 'felix.w@webco.io',    'date' => 'Apr 22, 2026', 'amount' => '$540',   'status' => 'pending'],
            ['name' => 'Sara Kim',     'email' => 'sara.k@mailx.net',    'date' => 'Apr 21, 2026', 'amount' => '$1,100', 'status' => 'active'],
            ['name' => 'Luis P.',      'email' => 'luis.p@empresa.mx',   'date' => 'Apr 21, 2026', 'amount' => '$730',   'status' => 'inactive'],
            ['name' => 'Yuki T.',      'email' => 'yuki.t@jp.co',        'date' => 'Apr 20, 2026', 'amount' => '$3,340', 'status' => 'active'],
            ['name' => 'Rachel N.',    'email' => 'rn@node.dev',         'date' => 'Apr 20, 2026', 'amount' => '$290',   'status' => 'pending'],
            ['name' => 'Marcus D.',    'email' => 'marcus@dco.com',      'date' => 'Apr 19, 2026', 'amount' => '$1,800', 'status' => 'active'],
            ['name' => 'Priya S.',     'email' => 'priya.s@inco.in',     'date' => 'Apr 19, 2026', 'amount' => '$640',   'status' => 'active'],
            ['name' => 'Tom B.',       'email' => 'tomb@btl.co.uk',      'date' => 'Apr 18, 2026', 'amount' => '$920',   'status' => 'active'],
        ])->map(function ($row, $index) use ($avatarColors) {
            $row['avatar_color'] = $avatarColors[$index % count($avatarColors)];
            return $row;
        });

        // Filter by status tab
        if ($status = $request->get('status')) {
            $map = ['active' => 'active', 'pending' => 'pending'];
            if (isset($map[$status])) {
                $all = $all->where('status', $map[$status])->values();
            }
        }

        // Manual pagination
        $perPage  = 8;
        $page     = $request->get('page', 1);
        $items    = $all->forPage($page, $perPage);

        return new LengthAwarePaginator(
            $items,
            $all->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    /**
     * Activity feed data
     * Replace with: Activity::latest()->limit(5)->get()
     */
    private function getActivities(): array
    {
        return [
            ['color' => '#6366f1', 'text' => '<strong>Lena Park</strong> placed a new order #8821', 'time' => '2 minutes ago'],
            ['color' => '#10b981', 'text' => 'Payment of <strong>$1,240</strong> confirmed',          'time' => '18 minutes ago'],
            ['color' => '#ec4899', 'text' => '<strong>3 new users</strong> signed up today',           'time' => '1 hour ago'],
            ['color' => '#f59e0b', 'text' => 'Server upgrade scheduled for <strong>May 2nd</strong>',  'time' => '3 hours ago'],
            ['color' => '#64748b', 'text' => 'Q1 report exported by <strong>Alex Kim</strong>',        'time' => 'Yesterday'],
        ];
    }

    /**
     * Top products data
     * Replace with: Product::orderByDesc('revenue')->limit(4)->get()
     */
    private function getTopProducts(): array
    {
        return [
            ['name' => 'Pro Subscription', 'percent' => 88, 'revenue' => '$28.4k', 'color' => 'linear-gradient(90deg,#6366f1,#818cf8)'],
            ['name' => 'API Credits',       'percent' => 67, 'revenue' => '$21.1k', 'color' => 'linear-gradient(90deg,#ec4899,#f472b6)'],
            ['name' => 'Team Plan',         'percent' => 52, 'revenue' => '$16.8k', 'color' => 'linear-gradient(90deg,#10b981,#34d399)'],
            ['name' => 'Add-on Storage',    'percent' => 35, 'revenue' => '$11.2k', 'color' => 'linear-gradient(90deg,#f59e0b,#fbbf24)'],
        ];
    }

    /**
     * SVG sparkline helper
     */
    private function sparkline(string $path, string $color, string $gradientId): string
    {
        $fillPath = $path . ' L120,36 L0,36Z';
        return <<<SVG
            <svg viewBox="0 0 120 36" preserveAspectRatio="none">
                <defs>
                    <linearGradient id="{$gradientId}" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="{$color}" stop-opacity="0.3"/>
                    <stop offset="100%" stop-color="{$color}" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <path d="{$path}" fill="none" stroke="{$color}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="{$fillPath}" fill="url(#{$gradientId})"/>
            </svg>
        SVG;
    }
}
