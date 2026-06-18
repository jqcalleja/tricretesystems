<?php

namespace App\Controllers;

use App\Models\EmployeeModel;
use App\Models\AttendanceModel;
use App\Models\ProjectModel;
use App\Models\ProjectAssignmentModel;

class Dashboard extends BaseController
{
    /**
     * Display the main dashboard with HR summary stats and recent activity.
     *
     * @return string Rendered dashboard view with:
     *                 @var int    $totalActive
     *                 @var int    $totalInactive
     *                 @var int    $presentToday
     *                 @var int    $absentToday
     *                 @var int    $activeProjects
     *                 @var int    $totalManpower
     *                 @var array  $recentAttendance
     *                 @var array  $projectSummary
     *                 @var array  $recentEmployees
     *                 @var string $trendDays
     *                 @var string $trendPresent
     *                 @var string $trendAbsent
     */

    public function index(): string
    {   
        $employeeModel   = new EmployeeModel();
        $attendanceModel = new AttendanceModel();
        $projectModel    = new ProjectModel();
        $assignmentModel = new ProjectAssignmentModel();

        $today = date('Y-m-d');

        // Employee counts
        $totalActive   = $employeeModel->where('is_active', 1)->countAllResults();
        $totalInactive = $employeeModel->where('is_active', 0)->countAllResults();

        // Today's attendance
        $presentToday = $attendanceModel
            ->where('date', $today)
            ->where('is_absent', 0)
            ->countAllResults();

        $absentToday = $attendanceModel
            ->where('date', $today)
            ->where('is_absent', 1)
            ->countAllResults();

        // Projects
        $activeProjects = $projectModel
            ->where('status', 'Active')
            ->countAllResults();

        // Active manpower
        $totalManpower = $assignmentModel
            ->where('is_active', 1)
            ->countAllResults();

        // Today's attendance log (latest 10)
        $recentAttendance = $attendanceModel
            ->select('attendance.*,
                      employees.first_name,
                      employees.last_name,
                      employees.employee_no,
                      projects.project_name')
            ->join('employees', 'employees.id = attendance.employee_id', 'left')
            ->join('projects',  'projects.id  = attendance.project_id',  'left')
            ->where('attendance.date', $today)
            ->orderBy('attendance.created_at', 'DESC')
            ->limit(10)
            ->find();

        // Active projects with manpower count
        $projectSummary = $projectModel
            ->select('projects.id,
                      projects.project_name,
                      projects.location,
                      projects.status,
                      COUNT(project_assignments.id) AS manpower_count')
            ->join(
                'project_assignments',
                'project_assignments.project_id = projects.id
                    AND project_assignments.is_active = 1',
                'left'
            )
            ->where('projects.status', 'Active')
            ->groupBy('projects.id')
            ->orderBy('manpower_count', 'DESC')
            ->findAll();

        // Recently added employees (last 5)
        $recentEmployees = $employeeModel
            ->select('id, employee_no, first_name, last_name,
                      date_hired, employment_status, is_active')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->find();

        // Attendance trend — last 7 days
        $trendDays    = [];
        $trendPresent = [];
        $trendAbsent  = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = date('Y-m-d', strtotime("-{$i} days"));

            $trendDays[]    = date('D d', strtotime($day));
            $trendPresent[] = $attendanceModel
                ->where('date', $day)->where('is_absent', 0)->countAllResults();
            $trendAbsent[]  = $attendanceModel
                ->where('date', $day)->where('is_absent', 1)->countAllResults();
        }

        return view('dashboard/index', [
            'pageTitle'        => 'Dashboard',
            'totalActive'      => $totalActive,
            'totalInactive'    => $totalInactive,
            'presentToday'     => $presentToday,
            'absentToday'      => $absentToday,
            'activeProjects'   => $activeProjects,
            'totalManpower'    => $totalManpower,
            'recentAttendance' => $recentAttendance,
            'projectSummary'   => $projectSummary,
            'recentEmployees'  => $recentEmployees,
            'trendDays'        => json_encode($trendDays),
            'trendPresent'     => json_encode($trendPresent),
            'trendAbsent'      => json_encode($trendAbsent),
        ]);
    }
}
