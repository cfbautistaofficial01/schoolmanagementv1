<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
use App\Models\PaymentModel;
use App\Models\QualificationModel;
use App\Models\UserModel;
use CodeIgniter\I18n\Time;

class DashboardController extends BaseController
{
    public function getDashboardData()
    {
        $db = \Config\Database::connect();

        // --- 1. STATS ---
        $userModel = new UserModel();
        $qualificationModel = new QualificationModel();
        $enrollmentModel = new EnrollmentModel();

        $stats = [
            'totalTrainees' => $userModel->where('role', 'trainee')->countAllResults(),
            'activeQualifications' => $qualificationModel->where('is_active', true)->countAllResults(),
            'pendingPayments' => 0,
            'completedTrainings' => $enrollmentModel->where('status', 'completed')->countAllResults(),
            'traineesChange' => 0,
            'completedChange' => 0,
        ];

        // --- 2. RECENT ACTIVITY (Corrected with LEFT JOIN) ---
        $recentTrainees = $db->table('users')
            ->select('users.id, users.created_at, trainee_details.first_name, trainee_details.last_name')
            // Using a LEFT JOIN is more robust
            ->join('trainee_details', 'trainee_details.user_id = users.id', 'left') 
            ->where('users.role', 'trainee')
            ->orderBy('users.created_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();
        
        $recentActivity = array_map(function($trainee) {
            $trainee['time_ago'] = Time::parse($trainee['created_at'])->humanize();
            return $trainee;
        }, $recentTrainees);


        // --- 3. COURSE POPULARITY ---
        $popularityData = $db->table('enrollments')
            ->select('qualifications.qualification_name, COUNT(enrollments.id) as enrollment_count')
            ->join('qualifications', 'qualifications.id = enrollments.qualification_id')
            ->groupBy('qualifications.qualification_name')
            ->orderBy('enrollment_count', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        $coursePopularity = [
            'labels' => array_column($popularityData, 'qualification_name'),
            'data' => array_map('intval', array_column($popularityData, 'enrollment_count')),
        ];

        // --- FINAL RESPONSE ---
        return $this->response->setJSON([
            'stats' => $stats,
            'recentActivity' => $recentActivity,
            'coursePopularity' => $coursePopularity,
        ]);
    }
}