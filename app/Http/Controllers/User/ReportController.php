<?php

namespace App\Http\Controllers\User;

use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;

class ReportController extends Controller
{

    /**
     * Carga vista principal para reporte diario
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        return view('user.report.daily.index');
    }


    /**
     * Genera reporte diario en pdf
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateDailyReport(Request $request) {

        if (empty($request->date_start) || empty($request->date_end)) {
            $this->sessionMessages('Debe especificar fecha de inicio y fin', 'alert-danger');
            return redirect()->route('user.report');
        }

        set_time_limit(0);
        $dateStart = new \DateTime($request->date_start);
        $dateEnd = new \DateTime($request->date_end);

        $dateStart->setTime(00,00,00);
        $dateEnd->setTime(23,59,59);

        $report = Ticket::where('created_at', '>=', $dateStart)
            ->where('created_at', '<=', $dateEnd)
            ->where('status', '<>', Ticket::STATUS_NULL)
            ->orderBy('created_at', 'DESC')
            ->with([
                'dailySorts',
                'animals',
                'user',
            ])
        ;

        if (Auth::user()->level == User::LEVEL_USER) {
            $report->where('user_id', Auth::user()->id);
        }

        $tickets = $report->get();
        $totalAmount = 0;
        $totalGainAmount = 0;

        foreach ($tickets as $ticket) {
            $totalAmount += $ticket->amount();
            $totalGainAmount += $ticket->payToGain();
        }

        // Por el momento cargo una vista normal mientras resuelvo problemas con rendimiento

        return view('pdf.report', [
            'tickets' => $tickets,
            'start' => $dateStart,
            'end' => $dateEnd,
            'totalAmount' => $totalAmount,
            'totalGainAmount' => $totalGainAmount,
        ]);

        $pdf = PDF::loadView('pdf.dailyReport', [
            'tickets' => $tickets,
            'start' => $dateStart,
            'end' => $dateEnd,
            'totalAmount' => $totalAmount,
            'totalGainAmount' => $totalGainAmount,
        ])
            ->setPaper('a4', 'landscape');

        return $pdf->stream();
    }
}
