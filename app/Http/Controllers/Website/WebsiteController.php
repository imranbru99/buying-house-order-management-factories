<?php

namespace App\Http\Controllers\Website;

use App\Domain\Modules\ModuleRegistry;
use App\Http\Controllers\Controller;
use Database\Seeders\DemoSeeder;
use Illuminate\Http\Response;
use Illuminate\View\View;

class WebsiteController extends Controller
{
    public function home(): View
    {
        return view('website.home');
    }

    public function staticPage(string $view, string $title, ?string $lead = null): View
    {
        return view($view, compact('title', 'lead'));
    }

    public function about(): View
    {
        return $this->staticPage('website.about', 'About TradeFlow', 'Built for buying houses coordinating global apparel supply chains.');
    }

    public function platform(): View
    {
        return $this->staticPage('website.platform', 'Platform', 'Multi-tenant SaaS core with modular activation per buying house.');
    }

    public function features(): View
    {
        return view('website.features', [
            'title' => 'Features',
            'modules' => ModuleRegistry::definitions(),
        ]);
    }

    public function modules(): View
    {
        return view('website.modules.index', [
            'title' => 'Modules',
            'modules' => ModuleRegistry::definitions(),
        ]);
    }

    public function moduleShow(string $key): View
    {
        $module = ModuleRegistry::get($key);

        abort_if($module === null, 404);

        return view('website.modules.show', [
            'title' => $module['name'],
            'module' => $module,
        ]);
    }

    public function pricing(): View
    {
        return $this->staticPage('website.pricing', 'Pricing', 'Plans scale with modules, users, and shipment volume.');
    }

    public function security(): View
    {
        return $this->staticPage('website.security', 'Security', 'Tenant isolation, audit trails, and role-based access.');
    }

    public function integrations(): View
    {
        return $this->staticPage('website.integrations', 'Integrations', 'REST API, webhooks, and AI-assisted workflows.');
    }

    public function faq(): View
    {
        return view('website.faq', ['title' => 'FAQ']);
    }

    public function contact(): View
    {
        return view('website.contact', ['title' => 'Contact']);
    }

    public function requestDemo(): View
    {
        return view('website.request-demo', ['title' => 'Request a demo']);
    }

    public function blog(): View
    {
        return view('website.blog', ['title' => 'Blog']);
    }

    public function documentation(): View
    {
        return view('website.documentation', ['title' => 'Documentation']);
    }

    public function featurePage(string $slug): View
    {
        $titles = [
            'buying-house-management' => 'Buying House Management',
            'buyer-management' => 'Buyer Management',
            'factory-management' => 'Factory Management',
            'order-management' => 'Order Management',
            'factory-allocation' => 'Factory Allocation',
            'merchandising-management' => 'Merchandising Management',
            'costing-management' => 'Costing Management',
            'sample-management' => 'Sample Management',
            'production-monitoring' => 'Production Monitoring',
            'tna-management' => 'TNA Management',
            'quality-management' => 'Quality Management',
            'compliance-management' => 'Compliance Management',
            'shipment-management' => 'Shipment Management',
            'commercial-documentation' => 'Commercial Documentation',
            'lc-management' => 'LC Management',
            'finance-management' => 'Finance Management',
            'ai-business-assistant' => 'AI Business Assistant',
            'buyer-portal' => 'Buyer Portal',
            'factory-portal' => 'Factory Portal',
            'supplier-portal' => 'Supplier Portal',
        ];

        abort_unless(isset($titles[$slug]), 404);

        return view('website.feature-page', [
            'title' => $titles[$slug],
            'slug' => $slug,
            'lead' => 'Operational capability for buying houses coordinating buyers, factories, and shipments.',
        ]);
    }

    public function demoAccess(): View
    {
        abort_unless(config('tradeflow.demo.enabled'), 404);

        $credentials = [
            ['email' => 'owner@demo.tradeflow.test', 'role' => 'Company Owner'],
            ['email' => 'merchandiser@demo.tradeflow.test', 'role' => 'Merchandiser'],
            ['email' => 'production@demo.tradeflow.test', 'role' => 'Production'],
            ['email' => 'quality@demo.tradeflow.test', 'role' => 'Quality'],
            ['email' => 'finance@demo.tradeflow.test', 'role' => 'Finance'],
            ['email' => 'buyer@demo.tradeflow.test', 'role' => 'Buyer portal'],
            ['email' => 'factory@demo.tradeflow.test', 'role' => 'Factory portal'],
        ];

        return view('website.demo-access', [
            'title' => 'Demo access',
            'showPassword' => config('tradeflow.demo.show_credentials_on_website', true),
            'password' => DemoSeeder::DEMO_PASSWORD,
            'credentials' => $credentials,
            'loginUrl' => url('/app/login'),
        ]);
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /app',
            'Disallow: /platform',
            'Disallow: /admin',
            'Sitemap: '.url('/sitemap.xml'),
        ];

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain']);
    }

    public function sitemap(): Response
    {
        $paths = [
            '/', '/about', '/platform', '/features', '/modules', '/pricing',
            '/security', '/integrations', '/faq', '/contact', '/request-demo',
            '/blog', '/documentation', '/demo-access',
        ];

        foreach (ModuleRegistry::keys() as $key) {
            $paths[] = '/modules/'.$key;
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($paths as $path) {
            $xml .= '<url><loc>'.e(url($path)).'</loc><changefreq>weekly</changefreq></url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
