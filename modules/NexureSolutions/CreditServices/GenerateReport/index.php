<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');
require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');


use TCPDF;
use Stripe\Stripe;

$query = "SELECT secretKey FROM nexure_paymentconfig WHERE processorName = 'Stripe' LIMIT 1";

$result = mysqli_query($con, $query);


function formatDateAndAge($dob) {

    if ($dob == 'N/A' || empty($dob)) {

        return 'N/A';

    }


    $dobDate = DateTime::createFromFormat('Y-m-d', $dob);


    if (!$dobDate) {

        return 'Invalid Date';

    }


    $formattedDob = $dobDate->format('F j Y');

    $today = new DateTime();

    $age = $today->diff($dobDate)->y;

    $months = $today->diff($dobDate)->m;

    return "$formattedDob ($age Years $months Months)";

}


function formatDate($date) {

    return !empty($date) ? date('F j Y', strtotime($date)) : 'N/A';

}


if ($result && mysqli_num_rows($result) > 0) {

    $row = mysqli_fetch_assoc($result);

    $secretKey = $row['secretKey'];
    
    
    function calculateCreditScore($user, $ownership, $stripeData) {
        $score = 650;
    
        // Positive factors (increase score)

        if (!empty($user['legalName'])) { $score += 5; }

        if (!empty($ownership['addressline1'])) { $score += 10; }

        if (!empty($user['email'])) { $score += 5; }

        if (!empty($user['mobileNumber'])) { $score += 8; }

        if (!empty($stripeData['ipAddress'])) { $score += 20; }

        if (!empty($stripeData['deviceInfo'])) { $score += 22; }
        
        if (!empty($stripeData['location'])) { $score += 15; }

        if (isset($user['registrationDate']) && strtotime($user['registrationDate']) >= strtotime('-4 years')) { 

            $score += 30; 
            
        }

        if (strcasecmp($user['legalName'], $user['email']) !== 0) { $score += 10; }

        if (!empty($stripeData['authRateEmail']) && $stripeData['authRateEmail'] >= 50) { 

            $score += 22; 

        }

        if (!empty($stripeData['authRateIP']) && $stripeData['authRateIP'] >= 50) { 

            $score += 28; 
            
        }

        if (!empty($stripeData['idVerified']) && $stripeData['idVerified']) { 

            $score += 18; 

        }
        if (!empty($stripeData['declinesPerHour']) && $stripeData['declinesPerHour'] >= 4) { 

            $score += 6; 

        }

        if (!isset($stripeData['freeServiceCustomer']) || !$stripeData['freeServiceCustomer']) { 

            $score += 62; 

        }
    

        // Negative factors (decrease score)

        if (empty($user['legalName'])) { $score -= 15; }

        if (empty($ownership['addressline1'])) { $score -= 5; }

        if (empty($user['email'])) { $score -= 8; }

        if (empty($user['mobileNumber'])) { $score -= 8; }
    
        if (!empty($stripeData['lastPaymentStatus']) && $stripeData['lastPaymentStatus'] == 'failed') {

            if (!empty($stripeData['daysPastDue'])) {

                if ($stripeData['daysPastDue'] >= 30) { $score -= 20; }
                if ($stripeData['daysPastDue'] >= 60) { $score -= 40; }
                if ($stripeData['daysPastDue'] >= 90) { $score -= 50; }
                if ($stripeData['daysPastDue'] >= 120) { $score -= 55; }

            }

        }
    
        if (isset($stripeData['idVerified']) && !$stripeData['idVerified']) { 

            $score -= 62; 

        }
        if (isset($stripeData['ipAddress']) && empty($stripeData['ipAddress'])) { 

            $score -= 5; 

        }
        if (isset($stripeData['location']) && empty($stripeData['location'])) { 
            
            $score -= 29; 

        }
        if (!empty($stripeData['recentCardDeclines']) && $stripeData['recentCardDeclines'] > 5) { 

            $score -= 92; 

        }
        if (!empty($stripeData['billingIpDistanceTooFar']) && $stripeData['billingIpDistanceTooFar']) {

            $score -= 99; 

        }
        if (isset($user['registrationDate']) && strtotime($user['registrationDate']) <= strtotime('-60 days')) { 

            $score -= 23; 

        }
    
        return max(300, min($score, 850));
        
    }
    
    function getStripeData($customerId, $secretKey) {
        Stripe::setApiKey($secretKey);
        
        try {

            $customer = \Stripe\Customer::retrieve($customerId);
    
            $charges = \Stripe\Charge::all(["limit" => 10, "customer" => $customerId]);
    
            $paymentIntents = \Stripe\PaymentIntent::all(["limit" => 10, "customer" => $customerId]);

            $invoices = \Stripe\Invoice::all(["limit" => 100, "customer" => $customerId]);
    
            $lastCharge = $charges->data[0] ?? null;

            $lastPaymentIntent = $paymentIntents->data[0] ?? null;
    
            $failedCharges = count(array_filter($charges->data, fn($c) => $c->status == 'failed'));

            $pastDueTotal = 0;

            $missedPayments = 0;
    
            foreach ($invoices->data as $invoice) {

                if ($invoice->status === 'past_due') {

                    $pastDueTotal += $invoice->amount_due;
                    $missedPayments++;

                }

            }
    
            $billingIpDistanceTooFar = false;
    
            $declinesPerHour = count(array_filter($charges->data, function ($charge) {

                return $charge->status == 'failed' && strtotime($charge->created) >= strtotime('-1 hour');

            }));

            $daysPastDue = 0;

            if ($lastPaymentIntent && $lastPaymentIntent->status == 'failed' && isset($lastPaymentIntent->created)) {
                $daysPastDue = (time() - strtotime($lastPaymentIntent->created)) / (60 * 60 * 24);
            }
    
            return [
                'ipAddress' => $lastCharge->source->client_ip ?? null,
                'deviceInfo' => $lastCharge->source->fingerprint ?? null,
                'location' => $lastCharge->source->country ?? null,
                'idVerified' => isset($customer->verification->status) && $customer->verification->status == 'verified',
                'authRateEmail' => isset($customer->email) ? 80 : 40,
                'authRateIP' => isset($lastCharge->source->client_ip) ? 80 : 40,
                'declinesPerHour' => $declinesPerHour,
                'recentCardDeclines' => $failedCharges,
                'billingIpDistanceTooFar' => $billingIpDistanceTooFar,
                'freeServiceCustomer' => count($charges->data) == 0 || count(array_filter($charges->data, fn($c) => $c->amount == 0)) > 0,
                'lastPaymentStatus' => $lastPaymentIntent ? ($lastPaymentIntent->status ?? 'unknown') : 'unknown',
                'daysPastDue' => $daysPastDue,
                'creditBalance' => number_format($customer->balance / 100, 2, '.', ''),
                'missedPayments' => $missedPayments
            ];
    
        } catch (\Exception $e) {

            redirect("/error/genericSystemError");

        }

    }
    
    function getUserData($accountNumber, $con) {
        $query = "SELECT * FROM nexure_users WHERE accountNumber = '" . $con->real_escape_string($accountNumber) . "'";
        $result = $con->query($query);
        return $result->fetch_assoc();
    }
    
    
    function getOwnershipData($email, $con) {
        $query = "SELECT * FROM nexure_ownershipinformation WHERE emailAddress = '" . $con->real_escape_string($email) . "'";
        $result = $con->query($query);
        return $result->fetch_assoc();
    }


    $accountNumber = isset($_GET['account_number']) ? $con->real_escape_string($_GET['account_number']) : '';

    if (empty($accountNumber)) {

        redirect("/error/genericSystemError");

    }


    
    $result = $con->query("SELECT * FROM nexure_users WHERE accountNumber = '$accountNumber'");

    if ($result->num_rows == 0) {

        redirect("/error/genericSystemError");

    }



    $user = $result->fetch_assoc();

    $ownershipResult = $con->query("SELECT * FROM nexure_ownershipinformation WHERE emailAddress = '{$user['email']}'");

    $ownership = ($ownershipResult->num_rows > 0) ? $ownershipResult->fetch_assoc() : [];



    class CustomPDF extends TCPDF {

        public function Header() {
    
            $logoFile = 'https://nexuresolutions.com/assets/img/logos/NexureLogoSquare.png'; 
            $this->Image($logoFile, 193, 6, 12, 12);
            $this->SetFont('helvetica', 'B', 9);
            $this->SetY(8);
            $this->Cell(0, 4, 'NEXURE CONSUMER REPORT AND SCORE', 0, 1, 'L');
            $this->SetFont('helvetica', '', 8);
            $this->Cell(0, 3, 'P.O. BOX 415 NOTTINGHAM, PA 19362', 0, 1, 'L');
            $this->Cell(0, 3, 'TEL: (855)-537-3591 | WEB: NEXURESOLUTIONS.COM', 0, 1, 'L');
            $this->Ln(0);
    
        }
    
        public function Footer() {
    
            $this->SetY(-15);
            $this->SetFont('helvetica', 'I', 8);
            $this->Cell(0, 10, 'Copyright © 2025 Nexure Credit Services LLC', 0, 0, 'C');
        }
    
    }
    
    
    
    $pdf = new CustomPDF();
    $pdf->SetMargins(5, 25, 5);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 9);
    
    
    
    function addSectionHeading($pdf, $heading) {
        $pdf->Ln(0);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetLineWidth(0);
        $pdf->Cell(190, 12, $heading, 1, 1, 'L', true);
    }
    
    
    
    function generateTable($pdf, $headers, $data, $widths) {
    
        $pdf->SetFillColor(143, 200, 247);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetDrawColor(143, 200, 247);
        $pdf->SetLineWidth(0.1);
    
        foreach ($headers as $key => $header) {
    
            $pdf->Cell($widths[$key], 5, $header, 1, 0, 'L', true);
    
        }
    
        $pdf->Ln();
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetDrawColor(221, 221, 221);
        $pdf->SetLineWidth(0.1);
    
        foreach ($data as $row) {
            $rowHeight = 0;
            $columnTexts = [];
    
            foreach ($row as $key => $column) {
                $columnTexts[$key] = $column;
                $lineCount = substr_count($column, "\n") + 1;
                $rowHeight = max($rowHeight, $lineCount * 5);
            }
    
            foreach ($row as $key => $column) {
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell($widths[$key], $rowHeight, $column, 1, 'L', false);
                $pdf->SetXY($x + $widths[$key], $y);
            }
            $pdf->Ln($rowHeight);
        }
    
    }
    
    
    
    // Consumer Information Table
    
    addSectionHeading($pdf, "Customer Name: ".($ownership['legalName'] ?? 'N/A'));
    
    generateTable($pdf, ['Personal Information', 'Contact Information'], [
        ["Alias Name: ".($user['aliasName'] ?? ''), "Mobile: ".($user['mobileNumber'] ?? 'N/A')],
        ["DOB: ". formatDateAndAge($ownership['dateOfBirth'] ?? 'N/A'), "Email: ".($user['email'] ?? 'N/A')]
    ], [100, 100]);
    
    $pdf->Ln(3);
    
    
    
    // Consumer Address Table
    
    addSectionHeading($pdf, 'Address Information');
    
    generateTable($pdf, ['Type', 'Address', 'Postal Code', 'Country'], [
        [
            "Permanent",
            trim(($ownership['addressline1'] ?? '') . ' ' . ($ownership['addressline2'] ?? '') . ', ' . ($ownership['city'] ?? '') . ', ' . ($ownership['state'] ?? '')),
            ($ownership['postalcode'] ?? 'N/A'),
            ($ownership['country'] ?? 'N/A')
        ]
    ], [50, 100, 25, 25]);
    
    $pdf->Ln(3);
    
    
    
    // Nexure Score Section

    $ownership = getOwnershipData($user['email'], $con);

    $stripeData = getStripeData($user['stripeID'], $secretKey);

    $creditScore = calculateCreditScore($user, $ownership, $stripeData);
    
    addSectionHeading($pdf, 'Nexure Score(s)');
    
    $scoringElements = [];

    if (!empty($user['legalName'])) $scoringElements[] = "1. Legal name is provided";
    if (!empty($ownership['addressline1'])) $scoringElements[] = "2. Address is provided";
    if (!empty($user['email'])) $scoringElements[] = "3. Email is provided";
    if (!empty($user['mobileNumber'])) $scoringElements[] = "4. Mobile number is provided";
    if (!empty($stripeData['ipAddress'])) $scoringElements[] = "5. IP address is provided";
    if (!empty($stripeData['deviceInfo'])) $scoringElements[] = "6. Device information is available";
    if (!empty($stripeData['location'])) $scoringElements[] = "7. Location is detected";
    if (!empty($stripeData['idVerified']) && $stripeData['idVerified']) $scoringElements[] = "8. ID is verified";
    if (!empty($stripeData['recentCardDeclines']) && $stripeData['recentCardDeclines'] > 5) $scoringElements[] = "9. Recent card declines detected";
    if (!empty($stripeData['billingIpDistanceTooFar']) && $stripeData['billingIpDistanceTooFar']) $scoringElements[] = "10. Billing IP is too far from usual location";
    if (!isset($stripeData['freeServiceCustomer']) || !$stripeData['freeServiceCustomer']) $scoringElements[] = "11. User is a paid customer";

    $scoringElementsText = implode("\n", $scoringElements);

    
    generateTable($pdf, ['Score Name', 'Score', 'Scoring Elements'], [
        ["Nexure Risk Score 1.0", $creditScore, $scoringElementsText]
    ], [60, 40, 100], true);

    $pdf->Ln(3);
            
    
    // Account Section
    
    addSectionHeading($pdf, 'Account Information');
    
    generateTable($pdf, ['Account Number', 'Balance', 'Date Opened', 'Missed Payments'],
        [[($user['accountNumber'] ?? 'N/A'), "$" . ($stripeData['creditBalance'] ?? '0.00'), (formatDate($user['registrationDate'] ?? 'N/A')), $stripeData['missedPayments'] ?? '0'], 
    ], [50, 50, 50, 50]);

    
    
    $pdf->Output('consumer_report_'.$accountNumber.'.pdf', 'I');

}

?>