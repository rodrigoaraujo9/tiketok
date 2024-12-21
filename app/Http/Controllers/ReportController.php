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

    public function allReports(Request $request)
    {
        // Fetch reports with pagination
        $reports = Report::with(['event', 'user'])->paginate(10);
    
        // For AJAX requests, return only the table HTML
        if ($request->ajax()) {
            $html = view('partials.admin_reports_table', compact('reports'))->render();
            return response()->json(['html' => $html]);
        }
    
        // For regular requests, return the full view
        return view('admin.reports', compact('reports'));
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

        // Allow admins to edit any report
        if ($rep->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $rep->reason = $request->input('reason');
        if(Auth::user()->isAdmin()) {
            $rep->r_status = $request->input('status');
        }

        $rep->save();

        return redirect()->route('showReport', $report_id)->with('success', 'Report updated successfully.');
    } 

    public function deleteReport($report_id)
    {
        $report = Report::findOrFail($report_id);

        $report->delete();

        if (Auth::user()->isAdmin()) {
            return redirect()->route('allReports')->with('success', 'Report deleted successfully.');
        } else {
            return redirect()->route('userReports')->with('success', 'Report deleted successfully.');
        }
    }

    public function userReports(Request $request)
    {
        if (!Auth::check()) return redirect('/login');
    
        $user = Auth::user();
    
        // Paginate reports
        $reports = Report::where('user_id', $user->user_id)
            ->with('event')
            ->paginate(10);
    
        // For AJAX requests, return only the table HTML
        if ($request->ajax()) {
            $html = view('partials.reports_table', compact('reports'))->render();
            return response()->json(['html' => $html]);
        }
    
        // For regular requests, return the full view
        return view('user.userReports', compact('reports'));
    }
    
}