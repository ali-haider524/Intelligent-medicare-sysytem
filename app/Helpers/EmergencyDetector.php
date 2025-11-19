<?php
namespace App\Helpers;

/**
 * Emergency Detection System
 * Detects life-threatening symptoms and triggers immediate alerts
 */
class EmergencyDetector {
    
    /**
     * Red flag symptoms that indicate emergency
     * Each primary symptom has associated secondary symptoms
     */
    private static $redFlags = [
        // Cardiac emergencies
        'chest pain' => [
            'keywords' => ['breathless', 'sweating', 'nausea', 'arm pain', 'jaw pain', 'pressure'],
            'department' => 'Emergency/Cardiology',
            'severity' => 'CRITICAL',
            'action' => 'Call 911 immediately - Possible heart attack'
        ],
        
        // Stroke symptoms
        'severe headache' => [
            'keywords' => ['confusion', 'slurred speech', 'vision problems', 'weakness', 'numbness', 'dizziness'],
            'department' => 'Emergency/Neurology',
            'severity' => 'CRITICAL',
            'action' => 'Call 911 immediately - Possible stroke (FAST: Face, Arms, Speech, Time)'
        ],
        
        // Severe bleeding
        'bleeding' => [
            'keywords' => ['severe', 'won\'t stop', 'heavy', 'gushing', 'spurting', 'uncontrolled'],
            'department' => 'Emergency',
            'severity' => 'CRITICAL',
            'action' => 'Apply pressure and call 911 - Severe hemorrhage'
        ],
        
        // Respiratory emergencies
        'breathing' => [
            'keywords' => ['difficulty', 'can\'t breathe', 'choking', 'gasping', 'blue lips', 'wheezing severe'],
            'department' => 'Emergency',
            'severity' => 'CRITICAL',
            'action' => 'Call 911 immediately - Respiratory distress'
        ],
        
        // Anaphylaxis
        'throat swelling' => [
            'keywords' => ['difficulty breathing', 'hives', 'rash', 'allergic', 'tongue swelling'],
            'department' => 'Emergency',
            'severity' => 'CRITICAL',
            'action' => 'Call 911 immediately - Possible anaphylaxis. Use EpiPen if available'
        ],
        
        // Loss of consciousness
        'unconscious' => [
            'keywords' => ['passed out', 'fainted', 'collapsed', 'unresponsive', 'seizure'],
            'department' => 'Emergency',
            'severity' => 'CRITICAL',
            'action' => 'Call 911 immediately - Check breathing and pulse'
        ],
        
        // Severe trauma
        'head injury' => [
            'keywords' => ['unconscious', 'vomiting', 'confusion', 'bleeding', 'severe'],
            'department' => 'Emergency',
            'severity' => 'CRITICAL',
            'action' => 'Call 911 immediately - Do not move patient'
        ],
        
        // Abdominal emergencies
        'severe abdominal pain' => [
            'keywords' => ['rigid', 'vomiting blood', 'black stool', 'pregnant', 'fever high'],
            'department' => 'Emergency',
            'severity' => 'CRITICAL',
            'action' => 'Call 911 immediately - Possible internal emergency'
        ]
    ];
    
    /**
     * High priority symptoms (urgent but not immediately life-threatening)
     */
    private static $urgentSymptoms = [
        'high fever' => ['above 103', '104', '105', 'persistent', 'with rash'],
        'severe pain' => ['unbearable', 'worst ever', 'sudden onset'],
        'vomiting' => ['blood', 'persistent', 'can\'t keep fluids'],
        'pregnancy' => ['bleeding', 'severe pain', 'contractions early']
    ];
    
    /**
     * Check if symptoms indicate an emergency
     * 
     * @param string $symptoms Patient's symptom description
     * @return array Emergency status and details
     */
    public static function check($symptoms) {
        $symptoms = strtolower(trim($symptoms));
        
        // Check for critical red flags
        foreach (self::$redFlags as $primary => $details) {
            if (strpos($symptoms, $primary) !== false) {
                // Check for associated secondary symptoms
                foreach ($details['keywords'] as $keyword) {
                    if (strpos($symptoms, $keyword) !== false) {
                        return [
                            'is_emergency' => true,
                            'severity' => 'CRITICAL',
                            'primary_symptom' => $primary,
                            'secondary_symptom' => $keyword,
                            'department' => $details['department'],
                            'action' => $details['action'],
                            'should_book' => false,
                            'alert_message' => "🚨 EMERGENCY DETECTED: {$details['action']}",
                            'color' => 'red',
                            'timestamp' => date('Y-m-d H:i:s')
                        ];
                    }
                }
            }
        }
        
        // Check for urgent (but not critical) symptoms
        foreach (self::$urgentSymptoms as $symptom => $keywords) {
            if (strpos($symptoms, $symptom) !== false) {
                foreach ($keywords as $keyword) {
                    if (strpos($symptoms, $keyword) !== false) {
                        return [
                            'is_emergency' => false,
                            'severity' => 'URGENT',
                            'primary_symptom' => $symptom,
                            'department' => 'General/Emergency',
                            'action' => 'Seek immediate medical attention within 1-2 hours',
                            'should_book' => true,
                            'alert_message' => "⚠️ URGENT: Please seek medical attention soon",
                            'color' => 'orange',
                            'timestamp' => date('Y-m-d H:i:s')
                        ];
                    }
                }
            }
        }
        
        // No emergency detected
        return [
            'is_emergency' => false,
            'severity' => 'ROUTINE',
            'action' => 'You can book a regular appointment',
            'should_book' => true,
            'alert_message' => null,
            'color' => 'green',
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Get triage level based on symptoms
     * 
     * @param string $symptoms
     * @return string Triage level (1-5)
     */
    public static function getTriageLevel($symptoms) {
        $result = self::check($symptoms);
        
        switch ($result['severity']) {
            case 'CRITICAL':
                return '1 - Immediate (Life-threatening)';
            case 'URGENT':
                return '2 - Urgent (Within 1-2 hours)';
            default:
                return '3 - Routine (Can wait)';
        }
    }
    
    /**
     * Log emergency detection for audit trail
     * 
     * @param array $detection Detection result
     * @param int $userId User who triggered detection
     */
    public static function logEmergency($detection, $userId = null) {
        if (!$detection['is_emergency']) {
            return;
        }
        
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("
                INSERT INTO emergency_logs 
                (user_id, severity, primary_symptom, secondary_symptom, 
                 action_taken, detected_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $userId,
                $detection['severity'],
                $detection['primary_symptom'] ?? null,
                $detection['secondary_symptom'] ?? null,
                $detection['action']
            ]);
            
            // Send alert to admin/emergency team
            self::sendEmergencyAlert($detection, $userId);
            
        } catch (\Exception $e) {
            error_log("Emergency log failed: " . $e->getMessage());
        }
    }
    
    /**
     * Send emergency alert to medical team
     * 
     * @param array $detection
     * @param int $userId
     */
    private static function sendEmergencyAlert($detection, $userId) {
        // In production, this would send SMS/Email/Push notification
        // For now, we'll log it
        
        $message = "EMERGENCY ALERT\n";
        $message .= "Time: " . date('Y-m-d H:i:s') . "\n";
        $message .= "User ID: " . ($userId ?? 'Guest') . "\n";
        $message .= "Severity: " . $detection['severity'] . "\n";
        $message .= "Symptoms: " . $detection['primary_symptom'];
        if (isset($detection['secondary_symptom'])) {
            $message .= " + " . $detection['secondary_symptom'];
        }
        $message .= "\nAction: " . $detection['action'] . "\n";
        
        error_log($message);
        
        // TODO: Implement actual notification system
        // - SMS to on-call doctor
        // - Email to emergency team
        // - Push notification to admin app
        // - Update emergency dashboard
    }
    
    /**
     * Get emergency statistics for admin dashboard
     * 
     * @param string $period 'today', 'week', 'month'
     * @return array Statistics
     */
    public static function getEmergencyStats($period = 'today') {
        try {
            $pdo = getDBConnection();
            
            $dateFilter = match($period) {
                'today' => "DATE(detected_at) = CURDATE()",
                'week' => "detected_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)",
                'month' => "detected_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)",
                default => "DATE(detected_at) = CURDATE()"
            };
            
            $stmt = $pdo->query("
                SELECT 
                    COUNT(*) as total_emergencies,
                    SUM(CASE WHEN severity = 'CRITICAL' THEN 1 ELSE 0 END) as critical_count,
                    SUM(CASE WHEN severity = 'URGENT' THEN 1 ELSE 0 END) as urgent_count
                FROM emergency_logs
                WHERE $dateFilter
            ");
            
            return $stmt->fetch();
            
        } catch (\Exception $e) {
            return [
                'total_emergencies' => 0,
                'critical_count' => 0,
                'urgent_count' => 0
            ];
        }
    }
}
