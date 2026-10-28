<?php
// Allow requests from your GitHub Pages frontend
header('Access-Control-Allow-Origin: https://c2ceducation.github.io');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
    exit;
}

$sanitize = fn($v) => htmlspecialchars(trim($v ?? ''), ENT_QUOTES, 'UTF-8');
$fullName = $sanitize($data['fullName'] ?? '');
$dob = $sanitize($data['dob'] ?? '');
$email = $sanitize($data['email'] ?? '');
$whatsapp = $sanitize($data['whatsapp'] ?? '');
$address = $sanitize($data['address'] ?? '');
$qualification = $sanitize($data['qualification'] ?? '');
$college = $sanitize($data['college'] ?? '');
$specialization = $sanitize($data['specialization'] ?? '');
$internshipArea = $sanitize($data['internshipArea'] ?? '');
$duration = $sanitize($data['duration'] ?? '');

if (!$fullName || !$email || !$whatsapp || !$qualification || !$college || !$specialization || !$internshipArea || !$duration) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

$appId = 'APP-' . date('YmdHis');
$receivedDate = date('d M Y, h:i A');
$dobFormatted = date('d M Y', strtotime($dob));
$whatsappLink = preg_replace('/[^0-9]/', '', $whatsapp);

$emailBody = "<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<style>
body{font-family:Segoe UI,Tahoma,Geneva,Verdana,sans-serif;color:#333;background:#f5f5f5}
.container{max-width:700px;margin:0 auto;background:#fff;box-shadow:0 0 20px rgba(0,0,0,0.1)}
.header{background:linear-gradient(135deg,#104cba 0%,#0d3a8f 100%);color:#fff;padding:40px 20px;text-align:center;border-bottom:5px solid #ff7133}
.header h1{font-size:28px;margin:0 0 10px 0;font-weight:700}
.logo{text-align:center;padding:20px;background:#f9f9f9;border-bottom:1px solid #e0e0e0}
.meta{background:#f0f0f0;padding:15px 20px;font-size:12px;color:#666;border-top:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;margin-bottom:20px}
.content{padding:30px 20px}
.section{margin-bottom:25px;background:#f9f9f9;padding:20px;border-left:4px solid #104cba;border-radius:4px}
.section h3{color:#104cba;font-size:16px;font-weight:700;margin:0 0 15px 0;text-transform:uppercase;letter-spacing:0.5px}
.field{display:flex;margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid #e0e0e0}
.field:last-child{border-bottom:none;margin-bottom:0;padding-bottom:0}
.label{font-weight:700;color:#104cba;width:40%;font-size:13px;text-transform:uppercase}
.value{width:60%;color:#333;font-size:14px;word-break:break-word}
.highlight{background:linear-gradient(135deg,#fff3e0 0%,#ffe0b2 100%);border-left:4px solid #ff7133}
.highlight h3{color:#ff7133}
.note{background:#e3f2fd;border-left:4px solid #104cba;padding:15px;margin-bottom:20px;border-radius:4px;font-size:13px;color:#1565c0}
.contact{background:#f5f5f5;padding:15px;border-radius:4px;margin-top:15px;font-size:12px}
.footer{background:#f9f9f9;padding:25px 20px;text-align:center;border-top:1px solid #e0e0e0;font-size:12px;color:#666}
.footer p{margin:8px 0;line-height:1.8}
a{color:#104cba;text-decoration:none}
@media(max-width:600px){.field{flex-direction:column}.label,.value{width:100%}.label{margin-bottom:5px}}
</style>
</head>
<body>
<div class='container'>
<div class='header'>
<h1>📋 New Internship Application</h1>
<p>C2C Venture Pvt Ltd - Internship Portal</p>
</div>
<div class='logo'>
<p><strong>C2C Venture Pvt Ltd</strong></p>
<p>One Stop Business Solutions</p>
</div>
<div class='meta'>
<div><strong>Application ID:</strong> $appId</div>
<div><strong>Received Date:</strong> $receivedDate</div>
<div><strong>Status:</strong> <span style='color:#4caf50;font-weight:bold'>Received</span></div>
</div>
<div class='content'>
<div class='note'>✓ A new internship application has been received and is ready for review.</div>
<div class='section'>
<h3>👤 Personal Details</h3>
<div class='field'><div class='label'>Full Name</div><div class='value'>$fullName</div></div>
<div class='field'><div class='label'>Date of Birth</div><div class='value'>$dobFormatted</div></div>
</div>
<div class='section'>
<h3>📞 Contact Details</h3>
<div class='field'><div class='label'>Email</div><div class='value'><a href='mailto:$email'>$email</a></div></div>
<div class='field'><div class='label'>WhatsApp</div><div class='value'><a href='https://wa.me/$whatsappLink'>$whatsapp</a></div></div>
<div class='field'><div class='label'>Address</div><div class='value'>" . nl2br($address) . "</div></div>
</div>
<div class='section'>
<h3>🎓 Education</h3>
<div class='field'><div class='label'>Qualification</div><div class='value'>$qualification</div></div>
<div class='field'><div class='label'>College</div><div class='value'>$college</div></div>
<div class='field'><div class='label'>Specialization</div><div class='value'><strong>$specialization</strong></div></div>
</div>
<div class='section highlight'>
<h3>💼 Internship Details</h3>
<div class='field'><div class='label'>Area</div><div class='value'><strong>$internshipArea</strong></div></div>
<div class='field'><div class='label'>Duration</div><div class='value'><strong>$duration</strong></div></div>
</div>
<div class='contact'>
<strong>📧 Contact Applicant:</strong><br>
Email: <a href='mailto:$email'>$email</a><br>
WhatsApp: <a href='https://wa.me/$whatsappLink'>$whatsapp</a>
</div>
</div>
<div class='footer'>
<div><strong>C2C Venture Pvt Ltd</strong></div>
<p>One Stop Business Solutions<br>Digital Marketing | E-Commerce | GST Accounting</p>
<p style='font-size:11px;margin-top:15px'>This is an automated email. Please do not reply. Use contact details above to reach the applicant.</p>
<p style='font-size:10px;color:#999;margin-top:15px'>© 2024 C2C Venture Pvt Ltd. All rights reserved.</p>
</div>
</div>
</body>
</html>";

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "From: C2C Internship Portal <enquiry@c2c.business>\r\n";
$headers .= "Reply-To: $email\r\n";

$mailSent = @mail('hr@c2c.business', 'New Internship Application - ' . $fullName, $emailBody, $headers);

echo json_encode([
    'success' => $mailSent,
    'message' => $mailSent ? 'Application submitted successfully' : 'Failed to send email. Please check server SMTP setup.'
]);
?>