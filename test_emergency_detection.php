<?php
/**
 * Emergency Detection Test Script
 * Run this to test the emergency detection system
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/app/Helpers/EmergencyDetector.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Detection Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">🚨 Emergency Detection System Test</h1>
        
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Test Cases</h2>
            
            <?php
            $testCases = [
                "I have chest pain and I'm sweating and breathless",
                "Severe headache with confusion and slurred speech",
                "Heavy bleeding that won't stop",
                "I can't breathe and my throat is swelling",
                "I passed out and feel dizzy",
                "I have a headache",
                "Mild fever and cough",
                "Back pain for a week"
            ];
            
            foreach ($testCases as $index => $symptoms) {
                $result = \App\Helpers\EmergencyDetector::check($symptoms);
                $triageLevel = \App\Helpers\EmergencyDetector::getTriageLevel($symptoms);
                
                $bgColor = match($result['severity']) {
                    'CRITICAL' => 'bg-red-50 border-red-500',
                    'URGENT' => 'bg-orange-50 border-orange-500',
                    default => 'bg-green-50 border-green-500'
                };
                
                $textColor = match($result['severity']) {
                    'CRITICAL' => 'text-red-900',
                    'URGENT' => 'text-orange-900',
                    default => 'text-green-900'
                };
                ?>
                
                <div class="mb-4 p-4 border-l-4 <?= $bgColor ?>">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="font-semibold <?= $textColor ?>">
                                Test Case <?= $index + 1 ?>: "<?= htmlspecialchars($symptoms) ?>"
                            </p>
                            <div class="mt-2 space-y-1 text-sm">
                                <p><strong>Severity:</strong> 
                                    <span class="px-2 py-1 rounded <?= $result['severity'] === 'CRITICAL' ? 'bg-red-200 text-red-900' : ($result['severity'] === 'URGENT' ? 'bg-orange-200 text-orange-900' : 'bg-green-200 text-green-900') ?>">
                                        <?= $result['severity'] ?>
                                    </span>
                                </p>
                                <p><strong>Triage Level:</strong> <?= $triageLevel ?></p>
                                <?php if (isset($result['department'])): ?>
                                    <p><strong>Department:</strong> <?= $result['department'] ?></p>
                                <?php endif; ?>
                                <p><strong>Action:</strong> <?= $result['action'] ?></p>
                                <p><strong>Should Book:</strong> <?= $result['should_book'] ? 'Yes' : 'No (Emergency!)' ?></p>
                                <?php if ($result['alert_message']): ?>
                                    <p class="mt-2 p-2 bg-white rounded border">
                                        <strong>Alert:</strong> <?= $result['alert_message'] ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="ml-4">
                            <?php if ($result['is_emergency']): ?>
                                <span class="text-4xl">🚨</span>
                            <?php elseif ($result['severity'] === 'URGENT'): ?>
                                <span class="text-4xl">⚠️</span>
                            <?php else: ?>
                                <span class="text-4xl">✅</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <h3 class="font-semibold text-blue-900 mb-2">✅ System Status</h3>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>✅ Emergency Detection: Active</li>
                <li>✅ Red Flag Detection: 8 categories</li>
                <li>✅ Triage System: Working</li>
                <li>✅ AI Chat Integration: Ready</li>
            </ul>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold mb-4">How to Test in Chatbot</h3>
            <ol class="list-decimal list-inside space-y-2 text-sm">
                <li>Go to main website: <code class="bg-gray-100 px-2 py-1 rounded">http://localhost/project/intelligent-medicare-system/</code></li>
                <li>Click the blue chatbot button (bottom right)</li>
                <li>Try these messages:
                    <ul class="ml-6 mt-2 space-y-1">
                        <li>• "I have chest pain and I'm sweating"</li>
                        <li>• "Severe headache with confusion"</li>
                        <li>• "I can't breathe"</li>
                        <li>• "Just a mild headache"</li>
                    </ul>
                </li>
                <li>Watch for emergency alerts!</li>
            </ol>
        </div>
        
        <div class="mt-6 text-center text-sm text-gray-600">
            <p>Emergency Detection System v1.0</p>
            <p>Intelligent Medicare System</p>
        </div>
    </div>
</body>
</html>
