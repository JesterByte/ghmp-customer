<?php

namespace App\Controllers;

use App\Helpers\FormatterHelper;
use App\Models\CustomerModel;
use App\Models\BeneficiaryModel;
use App\Models\OwnershipTransferModel;
use CodeIgniter\I18n\Time;
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
// use App\Models\UserModel;


class OwnershipTransferController extends BaseController
{
    protected $emailService;

    public function __construct()
    {
        $this->emailService = \Config\Services::email();
    }

    public function index()
    {

        $data = [
            "pageTitle" => "Request Ownership Transfer"
        ];

        return view("ownership_transfer_request", $data);
        // return view("verify_ownership_transfer_request_otp", $data);
    }

    public function submitRequest()
    {
        $session = session();

        $email = $this->request->getPost("email");

        $beneficiaryModel = new BeneficiaryModel();
        $beneficiary = $beneficiaryModel->where("email_address", $email)->first();

        $mainOwnerStatus = $beneficiaryModel->mainOwnerStatus($email);

        switch ($mainOwnerStatus) {
            case null:
                $session->setFlashdata("flash_message", [
                    "icon" => FormatterHelper::$xIcon,
                    "title" => "Operation Failed",
                    "message" => "Main owner not found."
                ]);
                return redirect()->to(base_url("ownership_transfer/request"));
            case "Active":
                $session->setFlashdata("flash_message", [
                    "icon" => FormatterHelper::$xIcon,
                    "title" => "Operation Failed",
                    "message" => "You cannot claim the ownership of the main account yet."
                ]);
                return redirect()->to(base_url("ownership_transfer/request"));
            case "Transferred Ownership":
                $session->setFlashdata("flash_message", [
                    "icon" => FormatterHelper::$xIcon,
                    "title" => "Operation Failed",
                    "message" => "A beneficiary has already claimed the ownership of the main account."
                ]);
                return redirect()->to(base_url("ownership_transfer/request"));
        }

        if (!$beneficiary) {
            $session->setFlashdata("flash_message", [
                "icon" => FormatterHelper::$xIcon,
                "title" => "Operation Failed",
                "message" => "Email address not found."
            ]);
            return redirect()->to(base_url("ownership_transfer/request"));
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        $expiry = Time::now()->addMinutes(10); // OTP expires in 10 minutes

        // Save transfer request
        $transferModel = new OwnershipTransferModel();
        $transferModel->save([
            "user_id" => $beneficiary["id"],
            "new_owner_email" => $email,
            "otp_code" => $otp,
            "otp_expires_at" => $expiry
        ]);

        // Load CodeIgniter's email service
        // $emailService = \Config\Services::email();

        // Set email configuration based on your Email.php settings
        // $this->emailService->setFrom('ejjose94@gmail.com', 'Green Haven Memorial Park');
        $this->emailService->setTo($email);
        $this->emailService->setSubject('Ownership Transfer OTP');
        $this->emailService->setMessage("
        <div style='font-family: Arial, sans-serif; border: 1px solid #ccc; padding: 20px; max-width: 500px; margin: auto;'>
            <h2 style='color: #2a9d8f; text-align: center;'>Green Haven Memorial Park</h2>
            <hr style='border: none; border-top: 2px solid #2a9d8f; margin-bottom: 20px;' />
            <p>Dear " . htmlspecialchars($beneficiary["first_name"]) . ",</p>
            <p>We received a request to transfer account ownership. To proceed, please use the OTP code below:</p>
            <div style='background-color: #f4f4f4; padding: 15px; margin: 10px 0; text-align: center; border-radius: 5px; font-size: 18px; font-weight: bold; color: #333;'>
                $otp
            </div>
            <p><strong>Note:</strong> This code will expire in <strong>10 minutes</strong>. If you did not request this, please ignore this email.</p>
            <p>Thank you for trusting Green Haven Memorial Park.</p>
            <p style='color: #888; font-size: 12px;'>This is an automated message. Please do not reply to this email.</p>
        </div>
        ");

        // Send email and handle any potential errors
        if ($this->emailService->send()) {
            $data = [
                "pageTitle" => "Request Ownership Transfer",
                "email" => $email
            ];

            $session->setFlashdata("flash_message", [
                "icon" => FormatterHelper::$checkIcon,
                "title" => "Request Received",
                "message" => "Please check your email inbox for the OTP verification."
            ]);

            return view("verify_ownership_transfer_request_otp", $data);
        } else {
            // Log the error message if email sending fails
            log_message('error', $this->emailService->printDebugger());

            $session->setFlashdata("flash_message", [
                "icon" => FormatterHelper::$xIcon,
                "title" => "Operation Failed",
                "message" => "OTP could not be sent. Please try again."
            ]);
            return redirect()->to(base_url("ownership_transfer/request"));

            // Return error message to the user
            // return redirect()->back()->with("error", "OTP could not be sent. Please try again.");
        }
    }

    // public function submitRequest()
    // {
    //     $session = session();

    //     $email = $this->request->getPost("email");

    //     $beneficiaryModel = new BeneficiaryModel();
    //     $beneficiary = $beneficiaryModel->where("email_address", $email)->first();

    //     $mainOwnerStatus = $beneficiaryModel->mainOwnerStatus($email);

    //     switch ($mainOwnerStatus) {
    //         case null:
    //             $session->setFlashdata("flash_message", [
    //                 "icon" => FormatterHelper::$xIcon,
    //                 "title" => "Operation Failed",
    //                 "message" => "Main owner not found."
    //             ]);
    //             return redirect()->to(base_url("ownership_transfer/request"));
    //             // return redirect()->back()->with("error", "Main owner not found.");
    //         case "Active":
    //             $session->setFlashdata("flash_message", [
    //                 "icon" => FormatterHelper::$xIcon,
    //                 "title" => "Operation Failed",
    //                 "message" => "You cannot claim the ownership of the main account yet."
    //             ]);
    //             return redirect()->to(base_url("ownership_transfer/request"));
    //             // return redirect()->back()->with("error", "You cannot claim the ownership of the main account yet.");
    //         case "Transferred Ownership":
    //             $session->setFlashdata("flash_message", [
    //                 "icon" => FormatterHelper::$xIcon,
    //                 "title" => "Operation Failed",
    //                 "message" => "A beneficiary has already claimed the ownership of the main account."
    //             ]);
    //             return redirect()->to(base_url("ownership_transfer/request"));
    //             // return redirect()->back()->with("error", "A beneficiary has already claimed the ownership of the main account.");
    //     }

    //     // if ($mainOwnerStatus == null) {
    //     //     return redirect()->back()->with("error", "Main owner not found.");
    //     // }

    //     if (!$beneficiary) {
    //         return redirect()->back()->with("error", "Email not found.");
    //     }

    //     // Generate OTP
    //     $otp = rand(100000, 999999);
    //     $expiry = Time::now()->addMinutes(10); // OTP expires in 10 minutes

    //     // Save transfer request
    //     $transferModel = new OwnershipTransferModel();
    //     $transferModel->save([
    //         "user_id" => $beneficiary["id"],
    //         "new_owner_email" => $email,
    //         "otp_code" => $otp,
    //         "otp_expires_at" => $expiry
    //     ]);

    //     // Load PHPMailer
    //     require_once APPPATH . '../vendor/autoload.php';
    //     $mail = new PHPMailer(true);

    //     try {
    //         // SMTP Server Configuration
    //         $mail->isSMTP();
    //         $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
    //         $mail->SMTPAuth = true;
    //         $mail->Username = 'ejjose94@gmail.com'; // SMTP username
    //         $mail->Password = 'dzftvwdftttloqat';    // SMTP password
    //         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    //         $mail->Port = 587;  // Adjust based on your SMTP provider

    //         // Sender and Recipient Settings
    //         $mail->setFrom('ejjose94@gmail.com', 'Green Haven Memorial Park');
    //         $mail->addAddress($email);

    //         // Email Content
    //         $mail->isHTML(true);
    //         $mail->Subject = 'Ownership Transfer OTP';
    //         $mail->Body = "
    //             <div style='font-family: Arial, sans-serif; border: 1px solid #ccc; padding: 20px; max-width: 500px; margin: auto;'>
    //                 <h2 style='color: #2a9d8f; text-align: center;'>Green Haven Memorial Park</h2>
    //                 <hr style='border: none; border-top: 2px solid #2a9d8f; margin-bottom: 20px;' />
    //                 <p>Dear Beneficiary,</p>
    //                 <p>We received a request to transfer account ownership. To proceed, please use the OTP code below:</p>
    //                 <div style='background-color: #f4f4f4; padding: 15px; margin: 10px 0; text-align: center; border-radius: 5px; font-size: 18px; font-weight: bold; color: #333;'>
    //                     $otp
    //                 </div>
    //                 <p><strong>Note:</strong> This code will expire in <strong>10 minutes</strong>. If you did not request this, please ignore this email.</p>
    //                 <p>Thank you for trusting Green Haven Memorial Park.</p>
    //                 <p style='color: #888; font-size: 12px;'>This is an automated message. Please do not reply to this email.</p>
    //             </div>
    //         ";

    //         // Send Email
    //         $mail->send();
    //     } catch (Exception $e) {
    //         return redirect()->back()->with("error", "OTP could not be sent. Error: " . $mail->ErrorInfo);
    //     }

    //     $data = [
    //         "pageTitle" => "Request Ownership Transfer",
    //         "email" => $email
    //     ];

    //     $session->setFlashdata("flash_message", [
    //         "icon" => FormatterHelper::$checkIcon,
    //         "title" => "Request Received",
    //         "message" => "Please check your email inbox for the OTP verification."
    //     ]);

    //     return view("verify_ownership_transfer_request_otp", $data);
    // }

    public function verifyOtp()
    {
        $email = $this->request->getPost("email");
        $otp = $this->request->getPost("otp");

        $transferModel = new OwnershipTransferModel();
        $request = $transferModel->where("new_owner_email", $email)
            ->where("otp_code", $otp)
            ->where("otp_expires_at >", Time::now())
            ->orderBy("created_at", "DESC")
            ->first();

        if (!$request) {

            // return redirect()->back()->with("error", "Invalid or expired OTP.");
        }

        $temporaryPassword = bin2hex(random_bytes(6));
        $hashedPassword = password_hash($temporaryPassword, PASSWORD_DEFAULT);

        $beneficiaryModel = new BeneficiaryModel();
        $beneficiary = $beneficiaryModel->where("email_address", $email)->first();
        $beneficiaryModel->update($beneficiary["id"], [
            "status" => "Active",
            "password_hashed" => $hashedPassword
        ]);

        $customerModel = new CustomerModel();
        $customerModel->update($beneficiary["customer_id"], [
            "status" => "Transferred Ownership",
            "active_beneficiary" => $beneficiary["id"]
        ]);

        $transferModel->update($request['id'], ["status" => "Verified"]);

        $this->emailService->setTo($beneficiary["email_address"]);
        $this->emailService->setSubject("Temporary Password for Ownership Transfer");

        // HTML Email Body
        $emailBody = "
        <div style='font-family: Arial, sans-serif; border: 1px solid #ccc; padding: 20px; max-width: 500px; margin: auto;'>
            <h2 style='color: #2a9d8f; text-align: center;'>Green Haven Memorial Park</h2>
            <hr style='border: none; border-top: 2px solid #2a9d8f; margin-bottom: 20px;' />
            <p>Dear " . htmlspecialchars($beneficiary["first_name"]) . ",</p>
            <p>Your ownership transfer has been successfully verified. As a result, we have generated a temporary password for you to access your account.</p>
            <p><strong>Temporary Password: </strong><span style='font-size: 20px; font-weight: bold; color: #333; background-color: #f4f4f4; padding: 10px; border-radius: 5px;'>" . $temporaryPassword . "</span></p>
            <p>Please use this temporary password to log in. For security reasons, it is recommended that you change your password after logging in.</p>
            <p>If you did not request this change, please contact us immediately.</p>
            <p>Thank you for trusting Green Haven Memorial Park.</p>
            <p style='color: #888; font-size: 12px;'>This is an automated message. Please do not reply to this email.</p>
        </div>";

        $this->emailService->setMessage($emailBody);

        $session = session();

        // Send the email
        if (!$this->emailService->send()) {
            $session->setFlashdata("flash_message", [
                "icon" => FormatterHelper::$xIcon,
                "title" => "Email Failed",
                "message" => "Failed to send email with the temporary password.",
            ]);
            // return redirect()->back()->with("error", "Failed to send email with the temporary password.");
            return redirect()->back();
        }

        $session->setFlashdata("flash_message", [
            "icon" => FormatterHelper::$checkIcon,
            "title" => "Ownership Transfer Successful",
            "message" => "You have successfully claimed the ownership of the main account! Please check your email for the temporary password.",
        ]);

        return redirect()->to(base_url("signin"));
        // return view("transfer_complete");
    }
}
