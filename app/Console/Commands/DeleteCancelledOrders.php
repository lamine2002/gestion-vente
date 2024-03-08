<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DeleteCancelledOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-cancelled-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Date actuelle moins 30 jours
        $thirtyDaysAgo = Carbon::now()->subDays(1);

        // Récupérer les commandes annulées de plus de 30 jours
        $cancelledOrders = Order::where('status', 'Annulée')
            ->where('created_at', '<=', $thirtyDaysAgo)
            ->get();

        // Supprimer les commandes annulées de plus de 1 jours
        foreach ($cancelledOrders as $cancelledOrder) {
            $cancelledOrder->delete();
            $this->info("Commande annulée #$cancelledOrder->id supprimée.");
        }

        $this->info('Suppression des commandes annulées terminée.');
    }
}
