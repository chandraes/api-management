<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use App\Models\ApiEndpoint;

class SyncApiEndpoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:api-endpoints';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi route API ke tabel api_endpoints';

    /**
     * Execute the console command.
     */
    public function handle()
    {

         $routes = collect(Route::getRoutes())
            ->filter(fn($route) => str_starts_with($route->uri(), 'api/v1/')) // sesuaikan prefix kalau perlu
            ->flatMap(function ($route) {
                return collect($route->methods())
                    ->filter(fn($m) => !in_array($m, ['HEAD', 'OPTIONS']))
                    ->map(fn($m) => [
                        'uri' => $route->uri(),
                        'method' => $m,
                        'name' => $route->getName() ?? $route->uri() . '-' . strtolower($m),
                    ]);
            });

        $existing = ApiEndpoint::all();

        $added = 0;
        $updated = 0;
        $deleted = 0;

        // Proses penambahan dan update
        foreach ($routes as $route) {
            $endpoint = ApiEndpoint::where('uri', $route['uri'])
                ->where('method', $route['method'])
                ->first();

            if ($endpoint) {
                // update jika ada perbedaan nama
                if ($endpoint->name !== $route['name']) {
                    $endpoint->update(['name' => $route['name']]);
                    $updated++;
                }
            } else {
                ApiEndpoint::create([
                    'name' => $route['name'],
                    'uri' => $route['uri'],
                    'method' => $route['method'],
                ]);
                $added++;
            }
        }

        // Hapus endpoint yang tidak ada lagi di route
        $currentKeys = $routes->map(fn($r) => $r['method'].'-'.$r['uri'])->toArray();
        foreach ($existing as $endpoint) {
            $key = $endpoint->method . '-' . $endpoint->uri;
            if (!in_array($key, $currentKeys)) {
                $endpoint->delete();
                $deleted++;
            }
        }

        $this->info("Sinkronisasi selesai. Ditambahkan: $added | Diupdate: $updated | Dihapus: $deleted");
    }
}
