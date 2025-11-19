<?php

namespace App\Services;

use App\Models\AiChatSession;
use App\Models\User;
use App\Models\Department;
use App\Models\DoctorProfile;
use App\Models\Medicine;
use App\Models\Appointment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatService
{
    private $apiKey;
    private $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key') ?? env('OPENAI_API_KEY');
        $this->apiUrl = 'https://api.openai.com/v1/chat/completions';
    }

    public function processMessage($sessionId, $message, $userId = null)
    {
        try {
            $session = AiChatSession::where('session_id', $sessionId)->first();
            
            if (!$session) {
                $session = $this->createNewSession($sessionId, $userId);
            }

            // Add user message to conversation
            $session->addMessage('user', $message);

            // Determine chat type if not set
            if ($session->chat_type === null) {
                $session->chat_type = $this->determineChatType($message);
                $session->save();
            }

            // Process based on chat type
            $response = $this->generateResponse($session, $message);

            // Add AI response to conversation
            $session->addMessage('assistant', $response['content'], $response['metadata'] ?? []);

            return [
                'success' => true,
                'response' => $response['content'],
                'session_id' => $sessionId,
                'chat_type' => $session->chat_type,
                'metadata' => $response['metadata'] ?? [],
            ];

        } catch (\Exception $e) {
            Log::error('AI Chat Service Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'response' => 'I apologize, but I\'m experiencing technical difficulties. Please try again or contact our support team.',
                'error' => $e->getMessage(),
            ];
        }
    }

    private function createNewSession($sessionId, $userId = null)
    {
        return AiChatSession::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'status' => 'active',
            'conversation_history' => [],
        ]);
    }

    private function determineChatType($message)
    {
        $message = strtolower($message);
        
        if (strpos($message, 'appointment') !== false || strpos($message, 'book') !== false) {
            return 'appointment_booking';
        }
        
        if (strpos($message, 'emergency') !== false || strpos($message, 'urgent') !== false || 
            strpos($message, 'heart attack') !== false || strpos($message, 'chest pain') !== false) {
            return 'emergency_help';
        }
        
        if (strpos($message, 'symptom') !== false || strpos($message, 'pain') !== false || 
            strpos($message, 'fever') !== false || strpos($message, 'headache') !== false) {
            return 'symptom_checker';
        }
        
        return 'general_inquiry';
    }

    private function generateResponse($session, $message)
    {
        switch ($session->chat_type) {
            case 'symptom_checker':
                return $this->handleSymptomChecker($session, $message);
            
            case 'appointment_booking':
                return $this->handleAppointmentBooking($session, $message);
            
            case 'emergency_help':
                return $this->handleEmergencyHelp($session, $message);
            
            default:
                return $this->handleGeneralInquiry($session, $message);
        }
    }

    private function handleSymptomChecker($session, $message)
    {
        // Extract symptoms using AI
        $symptoms = $this->extractSymptomsFromMessage($message);
        
        if (!empty($symptoms)) {
            $session->extractSymptoms($symptoms);
        }

        // Get relevant doctors and medicines
        $recommendations = $this->getRecommendationsForSymptoms($symptoms);

        $prompt = $this->buildSymptomCheckerPrompt($session, $message, $recommendations);
        $aiResponse = $this->callAiApi($prompt);

        return [
            'content' => $aiResponse,
            'metadata' => [
                'symptoms' => $symptoms,
                'recommendations' => $recommendations,
            ],
        ];
    }

    private function handleAppointmentBooking($session, $message)
    {
        // Check if user wants to book appointment
        if (strpos(strtolower($message), 'book') !== false || 
            strpos(strtolower($message), 'appointment') !== false) {
            
            $availableDoctors = $this->getAvailableDoctors();
            $availableSlots = $this->getAvailableSlots();

            $prompt = $this->buildAppointmentBookingPrompt($session, $message, $availableDoctors, $availableSlots);
            $aiResponse = $this->callAiApi($prompt);

            return [
                'content' => $aiResponse,
                'metadata' => [
                    'available_doctors' => $availableDoctors,
                    'available_slots' => $availableSlots,
                ],
            ];
        }

        return $this->handleGeneralInquiry($session, $message);
    }

    private function handleEmergencyHelp($session, $message)
    {
        $emergencyType = $this->identifyEmergencyType($message);
        $firstAidInstructions = $this->getFirstAidInstructions($emergencyType);

        $prompt = $this->buildEmergencyHelpPrompt($session, $message, $emergencyType, $firstAidInstructions);
        $aiResponse = $this->callAiApi($prompt);

        return [
            'content' => $aiResponse,
            'metadata' => [
                'emergency_type' => $emergencyType,
                'first_aid' => $firstAidInstructions,
                'is_emergency' => true,
            ],
        ];
    }

    private function handleGeneralInquiry($session, $message)
    {
        $prompt = $this->buildGeneralInquiryPrompt($session, $message);
        $aiResponse = $this->callAiApi($prompt);

        return [
            'content' => $aiResponse,
        ];
    }

    private function extractSymptomsFromMessage($message)
    {
        // Simple keyword extraction - can be enhanced with NLP
        $commonSymptoms = [
            'fever', 'headache', 'cough', 'sore throat', 'nausea', 'vomiting',
            'diarrhea', 'chest pain', 'shortness of breath', 'dizziness',
            'fatigue', 'muscle pain', 'joint pain', 'rash', 'abdominal pain'
        ];

        $extractedSymptoms = [];
        $message = strtolower($message);

        foreach ($commonSymptoms as $symptom) {
            if (strpos($message, $symptom) !== false) {
                $extractedSymptoms[] = $symptom;
            }
        }

        return $extractedSymptoms;
    }

    private function getRecommendationsForSymptoms($symptoms)
    {
        $recommendations = [];

        // Get relevant departments and doctors
        if (in_array('chest pain', $symptoms) || in_array('shortness of breath', $symptoms)) {
            $recommendations['departments'] = ['Cardiology', 'Emergency'];
        } elseif (in_array('headache', $symptoms) || in_array('dizziness', $symptoms)) {
            $recommendations['departments'] = ['Neurology', 'General Medicine'];
        } else {
            $recommendations['departments'] = ['General Medicine'];
        }

        // Get available doctors from recommended departments
        $doctors = DoctorProfile::whereHas('department', function($query) use ($recommendations) {
            $query->whereIn('name', $recommendations['departments']);
        })->with(['user', 'department'])->available()->take(3)->get();

        $recommendations['doctors'] = $doctors->map(function($doctor) {
            return [
                'name' => $doctor->user->name,
                'specialization' => $doctor->specialization,
                'department' => $doctor->department->name,
                'fee' => $doctor->consultation_fee,
            ];
        });

        return $recommendations;
    }

    private function callAiApi($prompt)
    {
        if (!$this->apiKey) {
            return "I'm currently unable to process your request. Please contact our support team for assistance.";
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful medical assistant for a healthcare system. Provide helpful, accurate information while always recommending users consult with healthcare professionals for serious concerns.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 500,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? 'I apologize, but I couldn\'t generate a proper response.';
            }

            return "I'm experiencing technical difficulties. Please try again later.";

        } catch (\Exception $e) {
            Log::error('AI API Error: ' . $e->getMessage());
            return "I'm currently unavailable. Please contact our support team.";
        }
    }

    private function buildSymptomCheckerPrompt($session, $message, $recommendations)
    {
        $symptomsText = implode(', ', $session->extracted_symptoms ?? []);
        $doctorsText = collect($recommendations['doctors'] ?? [])->pluck('name')->implode(', ');

        return "User is experiencing symptoms: {$symptomsText}. 
                User message: {$message}
                Available doctors: {$doctorsText}
                
                Please provide helpful medical guidance, suggest appropriate specialists, and recommend booking an appointment if necessary. 
                Always remind the user to seek immediate medical attention for serious symptoms.";
    }

    private function buildAppointmentBookingPrompt($session, $message, $doctors, $slots)
    {
        return "User wants to book an appointment. 
                User message: {$message}
                Available doctors: " . json_encode($doctors) . "
                Available slots: " . json_encode($slots) . "
                
                Help the user book an appointment by showing available options and guiding them through the process.";
    }

    private function buildEmergencyHelpPrompt($session, $message, $emergencyType, $firstAid)
    {
        return "EMERGENCY SITUATION: {$emergencyType}
                User message: {$message}
                First aid instructions: {$firstAid}
                
                Provide immediate first aid guidance and strongly recommend calling emergency services. Be clear and concise.";
    }

    private function buildGeneralInquiryPrompt($session, $message)
    {
        return "User inquiry: {$message}
                
                Provide helpful information about our healthcare services, general health advice, or direct them to appropriate resources.";
    }

    private function getAvailableDoctors()
    {
        return DoctorProfile::with(['user', 'department'])
            ->available()
            ->take(5)
            ->get()
            ->map(function($doctor) {
                return [
                    'id' => $doctor->user_id,
                    'name' => $doctor->user->name,
                    'specialization' => $doctor->specialization,
                    'department' => $doctor->department->name,
                    'fee' => $doctor->consultation_fee,
                ];
            });
    }

    private function getAvailableSlots()
    {
        // Return next 7 days with sample time slots
        $slots = [];
        for ($i = 1; $i <= 7; $i++) {
            $date = now()->addDays($i);
            $slots[$date->format('Y-m-d')] = [
                '09:00', '10:00', '11:00', '14:00', '15:00', '16:00'
            ];
        }
        return $slots;
    }

    private function identifyEmergencyType($message)
    {
        $message = strtolower($message);
        
        if (strpos($message, 'heart attack') !== false || strpos($message, 'chest pain') !== false) {
            return 'heart_attack';
        } elseif (strpos($message, 'stroke') !== false) {
            return 'stroke';
        } elseif (strpos($message, 'choking') !== false) {
            return 'choking';
        } elseif (strpos($message, 'bleeding') !== false) {
            return 'bleeding';
        }
        
        return 'general_emergency';
    }

    private function getFirstAidInstructions($emergencyType)
    {
        $instructions = [
            'heart_attack' => 'Call emergency services immediately. Have the person sit down and rest. If they have prescribed nitroglycerin, help them take it. If unconscious and not breathing, begin CPR.',
            'stroke' => 'Call emergency services immediately. Note the time symptoms started. Keep the person calm and lying down with head slightly elevated.',
            'choking' => 'If conscious, encourage coughing. If unable to cough, perform back blows and abdominal thrusts (Heimlich maneuver).',
            'bleeding' => 'Apply direct pressure to the wound with a clean cloth. Elevate the injured area above heart level if possible.',
            'general_emergency' => 'Call emergency services immediately. Keep the person calm and comfortable until help arrives.',
        ];

        return $instructions[$emergencyType] ?? $instructions['general_emergency'];
    }
}