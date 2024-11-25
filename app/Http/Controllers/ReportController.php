<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Report;
use App\Models\Users;
use App\Models\Event;

class ReportController extends Controller
{
    public function showReport($report_id)
    {
        $report = Report::find($report_id);
            
        return view('admin.report', ['report' => $report]);
    }

    public function eventReports($event_id)
    {
        if(!Auth::check()){ return redirect('/login'); }

        $event = Event::find($event_id);
        $reports = $event->reports->sortBy('report_id');

        return view('admin.events', ['event' => $event, 'reports' => $reports]);
    }

    public function allReports() {
        if(!Auth::check()){ return redirect('/login'); }

        if(Auth::user()->isAdmin()) {
            $reports = Report::with(['event', 'user'])->get();
            
            return view('admin.reports', ['reports' => $reports, 'isAdmin' => true]);
        } else {
            return redirect('/');
        }
    }

    public function createReportForm($event_id)
    {
        $event = Event::findOrFail($event_id);
        return view('user.newReport', compact('event'));
    }

    public function createReport(Request $request)
    {
        $report = new Report();
        $report->event_id = $request->input('event_id');
        $report->user_id = $request->input('user_id');
        $report->reason = $request->input('reason');
        $report->save();

        return redirect()->route('userReports')->with('success', 'Report created successfully.');
    }

    public function updateReportForm($report_id)
    {
        $report = Report::findOrFail($report_id);
        $event = Event::findOrFail($report->event_id);
        return view('user.editReport', compact('report', 'event'));
    }

    public function updateReport(Request $request, $report_id)
    {
        $rep = Report::findOrFail($report_id);

        if ($rep->user_id !== Auth::id() || !Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if(Auth::user()->isAdmin()) {
            $rep->reason = $request->input('reason');
            $rep->r_status = $request->status->input('status');
        } else {
            $rep->reason = $request->input('reason');
        }

        $rep->save();

        return redirect()->back()->with('success', 'Report updated successfully');
    } 

    public function deleteReport($report_id)
    {
        $report = Report::findOrFail($report_id);
        $report->delete();

        return redirect()->route('userReports')->with('success', 'Report deleted successfully.');
    }

    public function userReports()
    {
        if (!Auth::check()) return redirect('/login');

        $user = Auth::user();
        $reports = Report::where('user_id', $user->user_id)->with('event')->get();
        return view('user.userReports', compact('reports'));
    }
}