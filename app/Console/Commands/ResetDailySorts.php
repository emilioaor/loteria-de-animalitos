<?php

namespace App\Console\Commands;

use App\DailySort;
use App\Sort;
use Illuminate\Console\Command;
use DB;

class ResetDailySorts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sorts:reset-daily-sort';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cierra todos los sorteos y abre nuevos sorteos diarios para el día actual';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->output->text('Restableciendo sorteos diarios');
        $this->output->text('------------------------------------------------');

        DB::beginTransaction();

            //  Cierra todos los sorteos
            $this->output->text('Cerrando todos los sorteos activos ..');
            DailySort::where('status', DailySort::STATUS_ACTIVE)
                ->update(['status' => DailySort::STATUS_CLOSE])
            ;

            //  Por cada sorteo registro un sorteo diario
            //  con la fecha actual
            $this->output->text('Registrando sorteos del dia : ' . (new \DateTime('now'))->format('d-m-Y') );
            $sorts = Sort::all();

            foreach ($sorts as $sort) {
                $this->output->text('Sorteo: ' . $sort->description);

                $dailySort = new DailySort();
                $dailySort->date_sort = new \DateTime('now');
                $dailySort->sort_id = $sort->id;
                $dailySort->status = DailySort::STATUS_ACTIVE;
                $dailySort->save();
            }

        DB::commit();

        $this->output->text('Proceso terminado');
    }
}
