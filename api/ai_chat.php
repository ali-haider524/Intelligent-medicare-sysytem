<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/Helpers/EmergencyDetector.php';

header('Content-Type: application/json');

// Get message from request
$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? '';

if (empty($message)) {
    echo json_encode(['success' => false, 'error' => 'Message is required']);
    exit;
}

// STEP 1: Check for emergency symptoms FIRST
$emergencyCheck = \App\Helpers\EmergencyDetector::check($message);

if ($emergencyCheck['is_emergency']) {
    // Log the emergency
    $userId = $_SESSION['user_id'] ?? null;
    \App\Helpers\EmergencyDetector::logEmergency($emergencyCheck, $userId);
    
    echo json_encode([
        'success' => true,
        'response' => $emergencyCheck['alert_message'] . "\n\n" . $emergencyCheck['action'],
        'type' => 'emergency',
        'data' => $emergencyCheck,
        'emergency' => true,
        'should_book' => false
    ]);
    exit;
}

if ($emergencyCheck['severity'] === 'URGENT') {
    echo json_encode([
        'success' => true,
        'response' => $emergencyCheck['alert_message'] . "\n\n" . $emergencyCheck['action'],
        'type' => 'urgent',
        'data' => $emergencyCheck,
        'urgent' => true,
        'should_book' => true
    ]);
    exit;
}

// STEP 2: If not emergency, generate helpful AI response
$response = generateAIResponse($message);

echo json_encode([
    'success' => true,
    'response' => $response,
    'type' => 'normal',
    'should_book' => true
]);

function generateAIResponse($message) {
    $message = strtolower($message);
    
    // Appointment booking
    if (preg_match('/\b(book|appointment|schedule|see doctor|consultation)\b/i', $message)) {
        return "I can help you book an appointment! 📅\n\nOur available departments:\n• General Medicine\n• Cardiology\n• Neurology\n• Orthopedics\n• Pediatrics\n\nWould you like to:\n1. Book an appointment now\n2. See available doctors\n3. Check appointment slots\n\nPlease let me know your preference!";
    }
    
    // Symptom analysis - Common conditions
    if (preg_match('/\b(headache|head pain)\b/i', $message)) {
        return "I understand you're experiencing a headache. 🤕\n\nCommon causes:\n• Tension or stress\n• Dehydration\n• Eye strain\n• Lack of sleep\n\nRecommendations:\n• Rest in a quiet, dark room\n• Stay hydrated\n• Take over-the-counter pain relief\n• Apply cold compress\n\nIf headache is severe, sudden, or accompanied by other symptoms, please book an appointment with our Neurology department.";
    }
    
    if (preg_match('/\b(fever|temperature|hot)\b/i', $message)) {
        return "You mentioned having a fever. 🌡️\n\nFever management:\n• Rest and stay hydrated\n• Take fever-reducing medication (acetaminophen/ibuprofen)\n• Use cool compresses\n• Monitor temperature regularly\n\nSeek immediate care if:\n• Fever above 103°F (39.4°C)\n• Fever lasts more than 3 days\n• Accompanied by severe symptoms\n• Difficulty breathing\n\nWould you like to book an appointment?";
    }
    
    if (preg_match('/\b(cough|cold|flu)\b/i', $message)) {
        return "I see you're dealing with cold/flu symptoms. 🤧\n\nHome care tips:\n• Get plenty of rest\n• Drink warm fluids (tea, soup)\n• Use humidifier\n• Gargle with salt water\n• Take vitamin C\n\nSee a doctor if:\n• Symptoms worsen after 3-4 days\n• High fever persists\n• Difficulty breathing\n• Chest pain\n\nOur General Medicine department can help. Would you like to book?";
    }
    
    if (preg_match('/\b(stomach|abdominal pain|belly)\b/i', $message)) {
        return "Stomach discomfort can have various causes. 🏥\n\nCommon reasons:\n• Indigestion\n• Gas or bloating\n• Food intolerance\n• Gastritis\n\nTry:\n• Avoid spicy/fatty foods\n• Eat smaller meals\n• Stay hydrated\n• Rest\n\nConsult a doctor if:\n• Severe or persistent pain\n• Vomiting or diarrhea\n• Blood in stool\n• Fever\n\nShall I help you book an appointment?";
    }
    
    if (preg_match('/\b(back pain|backache)\b/i', $message)) {
        return "Back pain is very common. 🦴\n\nImmediate relief:\n• Apply ice/heat\n• Gentle stretching\n• Over-the-counter pain relief\n• Maintain good posture\n• Avoid heavy lifting\n\nSee our Orthopedics department if:\n• Pain lasts more than a week\n• Pain radiates to legs\n• Numbness or weakness\n• After an injury\n\nWould you like to schedule a consultation?";
    }
    
    // Doctor information
    if (preg_match('/\b(doctor|specialist|physician)\b/i', $message)) {
        return "Our Medical Team 👨‍⚕️👩‍⚕️\n\nWe have experienced doctors in:\n• General Medicine\n• Cardiology (Heart)\n• Neurology (Brain & Nerves)\n• Orthopedics (Bones & Joints)\n• Pediatrics (Children)\n• Dermatology (Skin)\n• ENT (Ear, Nose, Throat)\n\nAll our doctors are board-certified with years of experience.\n\nWould you like to:\n1. See doctor profiles\n2. Book an appointment\n3. Know about a specific department";
    }
    
    // Hospital information
    if (preg_match('/\b(hours|timing|open|available)\b/i', $message)) {
        return "Hospital Hours ⏰\n\n• OPD: 8:00 AM - 8:00 PM (Mon-Sat)\n• Emergency: 24/7 (Always Open)\n• Pharmacy: 24/7\n• Lab Services: 7:00 AM - 10:00 PM\n\nSunday: Emergency services only\n\nFor appointments, you can book online anytime!\n\nNeed help booking?";
    }
    
    if (preg_match('/\b(location|address|where|directions)\b/i', $message)) {
        return "Find Us 📍\n\nIntelligent Medicare Hospital\n123 Healthcare Avenue\nMedical District\nCity, State 12345\n\nContact:\n📞 Phone: +1 (555) 123-4567\n🚨 Emergency: +1 (555) 911-0000\n📧 Email: info@intelligentmedicare.com\n\nParking: Free parking available\nPublic Transport: Bus routes 10, 15, 20\n\nNeed directions?";
    }
    
    // Services
    if (preg_match('/\b(service|facility|treatment)\b/i', $message)) {
        return "Our Services 🏥\n\n✅ Outpatient Department (OPD)\n✅ Inpatient Department (IPD)\n✅ Emergency Care 24/7\n✅ Diagnostic Lab\n✅ Radiology & Imaging\n✅ Pharmacy\n✅ Surgery\n✅ Physiotherapy\n✅ Dental Care\n✅ Maternity Ward\n\nAll services use latest medical technology.\n\nWhich service would you like to know more about?";
    }
    
    // Insurance
    if (preg_match('/\b(insurance|payment|cost|price)\b/i', $message)) {
        return "Payment & Insurance 💳\n\nWe accept:\n✅ All major insurance plans\n✅ Medicare/Medicaid\n✅ Cash payments\n✅ Credit/Debit cards\n✅ Payment plans available\n\nInsurance partners:\n• Blue Cross Blue Shield\n• Aetna\n• Cigna\n• UnitedHealthcare\n• And more...\n\nFor specific coverage questions, please call our billing department at +1 (555) 123-4567 ext. 2";
    }
    
    // Greetings
    if (preg_match('/\b(hello|hi|hey|good morning|good afternoon|good evening)\b/i', $message)) {
        return "Hello! 👋 Welcome to Intelligent Medicare Hospital!\n\nI'm your AI health assistant. I can help you with:\n\n• 🏥 Symptom assessment\n• 📅 Booking appointments\n• 👨‍⚕️ Finding doctors\n• ℹ️ Hospital information\n• 🚨 Emergency guidance\n\nHow can I assist you today?";
    }
    
    // Thank you
    if (preg_match('/\b(thank|thanks|appreciate)\b/i', $message)) {
        return "You're welcome! 😊\n\nI'm here 24/7 if you need any help.\n\nRemember:\n• For emergencies, call 911\n• For appointments, I can help you book\n• For questions, just ask!\n\nStay healthy! 🌟";
    }
    
    // Default response
    return "I'm here to help! 🤖\n\nI can assist you with:\n\n1. 🏥 Symptom assessment and advice\n2. 📅 Booking appointments\n3. 👨‍⚕️ Information about our doctors\n4. ℹ️ Hospital services and facilities\n5. 🚨 Emergency guidance\n\nPlease tell me:\n• What symptoms are you experiencing?\n• Would you like to book an appointment?\n• Do you have questions about our services?\n\nI'm listening! 👂";
}
