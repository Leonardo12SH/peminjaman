<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Create upload directory if not exists
    createUploadDir();
    
    // Validate required fields
    $requiredFields = [
        'full_name', 'email', 'phone', 'birth_date', 'gender', 'position',
        'education', 'experience_years', 'address', 'work_vision', 'work_mission', 'motivation'
    ];
    
    $errors = [];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = "Field $field wajib diisi";
        }
    }
    
    // Validate email
    if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid";
    }
    
    // Check if email already exists
    if (!empty($_POST['email'])) {
        $stmt = $pdo->prepare("SELECT id FROM applications WHERE email = ?");
        $stmt->execute([$_POST['email']]);
        if ($stmt->fetch()) {
            $errors[] = "Email sudah terdaftar sebelumnya";
        }
    }
    
    // Validate required files
    if (empty($_FILES['cv_file']['name'])) {
        $errors[] = "CV/Resume wajib diupload";
    }
    
    if (empty($_FILES['photo_file']['name'])) {
        $errors[] = "Foto 3x4 wajib diupload";
    }
    
    // Validate position-specific requirements
    $position = $_POST['position'] ?? '';
    if ($position === 'Driver' && empty($_FILES['sim_file']['name'])) {
        $errors[] = "SIM A/C wajib untuk posisi Driver";
    }
    
    $technicalPositions = ['Teknisi FOT', 'Teknisi FOC', 'Teknisi Jointer'];
    if (in_array($position, $technicalPositions) && empty($_FILES['certificate_file']['name'])) {
        $errors[] = "Sertifikat K3 wajib untuk posisi teknis";
    }
    
    if (!empty($errors)) {
        echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
        exit;
    }
    
    // Process file uploads
    $uploadedFiles = [];
    $fileFields = ['cv_file', 'photo_file', 'certificate_file', 'sim_file'];
    
    foreach ($fileFields as $field) {
        if (!empty($_FILES[$field]['name'])) {
            $uploadResult = uploadFile($_FILES[$field], $field);
            if ($uploadResult['success']) {
                $uploadedFiles[$field] = $uploadResult['filename'];
            } else {
                // Clean up already uploaded files
                foreach ($uploadedFiles as $uploadedFile) {
                    if (file_exists(UPLOAD_DIR . $uploadedFile)) {
                        unlink(UPLOAD_DIR . $uploadedFile);
                    }
                }
                echo json_encode(['success' => false, 'message' => $uploadResult['message']]);
                exit;
            }
        }
    }
    
    // Prepare data for database
    $data = [
        'full_name' => sanitize($_POST['full_name']),
        'email' => sanitize($_POST['email']),
        'phone' => sanitize($_POST['phone']),
        'position' => sanitize($_POST['position']),
        'education' => sanitize($_POST['education']),
        'experience_years' => (int)$_POST['experience_years'],
        'address' => sanitize($_POST['address']),
        'birth_date' => $_POST['birth_date'],
        'gender' => sanitize($_POST['gender']),
        'cv_file' => $uploadedFiles['cv_file'] ?? null,
        'photo_file' => $uploadedFiles['photo_file'] ?? null,
        'certificate_file' => $uploadedFiles['certificate_file'] ?? null,
        'sim_file' => $uploadedFiles['sim_file'] ?? null,
        'fiber_optic_knowledge' => sanitize($_POST['fiber_optic_knowledge'] ?? ''),
        'otdr_experience' => sanitize($_POST['otdr_experience'] ?? 'Tidak'),
        'jointing_experience' => sanitize($_POST['jointing_experience'] ?? 'Tidak'),
        'tower_climbing_experience' => sanitize($_POST['tower_climbing_experience'] ?? 'Tidak'),
        'k3_certificate' => sanitize($_POST['k3_certificate'] ?? 'Tidak'),
        'work_vision' => sanitize($_POST['work_vision']),
        'work_mission' => sanitize($_POST['work_mission']),
        'motivation' => sanitize($_POST['motivation']),
        'application_status' => 'Pending'
    ];
    
    // Insert into database
    $sql = "INSERT INTO applications (
        full_name, email, phone, position, education, experience_years, address, birth_date, gender,
        cv_file, photo_file, certificate_file, sim_file,
        fiber_optic_knowledge, otdr_experience, jointing_experience, tower_climbing_experience, k3_certificate,
        work_vision, work_mission, motivation, application_status
    ) VALUES (
        :full_name, :email, :phone, :position, :education, :experience_years, :address, :birth_date, :gender,
        :cv_file, :photo_file, :certificate_file, :sim_file,
        :fiber_optic_knowledge, :otdr_experience, :jointing_experience, :tower_climbing_experience, :k3_certificate,
        :work_vision, :work_mission, :motivation, :application_status
    )";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($data);
    
    if ($result) {
        $applicationId = $pdo->lastInsertId();
        
        // Send confirmation email (optional)
        sendConfirmationEmail($data['email'], $data['full_name'], $applicationId);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Lamaran berhasil dikirim!',
            'application_id' => $applicationId
        ]);
    } else {
        // Clean up uploaded files if database insert failed
        foreach ($uploadedFiles as $uploadedFile) {
            if (file_exists(UPLOAD_DIR . $uploadedFile)) {
                unlink(UPLOAD_DIR . $uploadedFile);
            }
        }
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data lamaran']);
    }
    
} catch (Exception $e) {
    error_log("Application submission error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server. Silakan coba lagi.']);
}

function uploadFile($file, $fieldName) {
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => "Error uploading $fieldName"];
    }
    
    // Check file size
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => "File $fieldName terlalu besar (maksimal 5MB)"];
    }
    
    // Check file extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_EXTENSIONS)) {
        return ['success' => false, 'message' => "Format file $fieldName tidak didukung"];
    }
    
    // Validate file type based on field
    $allowedTypes = [];
    switch ($fieldName) {
        case 'cv_file':
            $allowedTypes = ['pdf', 'doc', 'docx'];
            break;
        case 'photo_file':
            $allowedTypes = ['jpg', 'jpeg', 'png'];
            break;
        case 'certificate_file':
        case 'sim_file':
            $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png'];
            break;
    }
    
    if (!in_array($extension, $allowedTypes)) {
        return ['success' => false, 'message' => "Format file $fieldName tidak sesuai"];
    }
    
    // Generate unique filename
    $filename = generateFileName($file['name']);
    $uploadPath = UPLOAD_DIR . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        // Set proper permissions
        chmod($uploadPath, 0644);
        return ['success' => true, 'filename' => $filename];
    } else {
        return ['success' => false, 'message' => "Gagal menyimpan file $fieldName"];
    }
}

function sendConfirmationEmail($email, $fullName, $applicationId) {
    // Simple email confirmation (you can implement with PHPMailer for better functionality)
    $subject = "Konfirmasi Lamaran - PT. Visdat Teknik Utama";
    $message = "
    Yth. $fullName,
    
    Terima kasih telah mengirimkan lamaran kerja ke PT. Visdat Teknik Utama.
    
    ID Lamaran Anda: $applicationId
    
    Lamaran Anda sedang dalam proses review. Kami akan menghubungi Anda jika ada perkembangan lebih lanjut.
    
    Hormat kami,
    Tim HR PT. Visdat Teknik Utama
    ";
    
    $headers = "From: noreply@visdat.com\r\n";
    $headers .= "Reply-To: hr@visdat.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    // Uncomment to send email
    // mail($email, $subject, $message, $headers);
}
?>