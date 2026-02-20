<?php
session_start();
require_once 'vendor/autoload.php';
require_once 'config/db.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    die("Accès refusé.");
}

$ref = $_GET['ref'] ?? '';
if (!$ref) {
    die("Référence manquante.");
}

// Fetch order
$stmt = $pdo->prepare("SELECT o.*, u.username, u.email FROM orders o JOIN users u ON o.user_id = u.id WHERE o.payment_reference = ?");
$stmt->execute([$ref]);
$order = $stmt->fetch();

if (!$order || ($order['user_id'] != $_SESSION['user_id'] && !isAdmin())) {
    die("Commande non trouvée ou accès non autorisé.");
}

// Fetch items
$stmt = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt->execute([$order['id']]);
$items = $stmt->fetchAll();

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Header
$pdf->Cell(0, 10, utf8_decode('Crochet art by Orly'), 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode('Facture - Réf: ' . $order['payment_reference']), 0, 1, 'C');
$pdf->Ln(10);

// Client info
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Client :'), 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 7, utf8_decode('Nom : ' . $order['username']), 0, 1);
$pdf->Cell(0, 7, utf8_decode('Email : ' . $order['email']), 0, 1);
$pdf->Cell(0, 7, utf8_decode('Date : ' . $order['created_at']), 0, 1);
$pdf->Ln(10);

// Table Header
$pdf->SetFillColor(200, 200, 200);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(80, 10, utf8_decode('Produit'), 1, 0, 'C', true);
$pdf->Cell(30, 10, utf8_decode('Quantité'), 1, 0, 'C', true);
$pdf->Cell(40, 10, utf8_decode('Prix Unitaire'), 1, 0, 'C', true);
$pdf->Cell(40, 10, utf8_decode('Total'), 1, 1, 'C', true);

// Table Body
$pdf->SetFont('Arial', '', 10);
foreach ($items as $item) {
    $line_total = $item['price'] * $item['quantity'];
    $pdf->Cell(80, 8, utf8_decode($item['name']), 1);
    $pdf->Cell(30, 8, $item['quantity'], 1, 0, 'C');
    $pdf->Cell(40, 8, number_format($item['price'], 0, ',', ' ') . ' CFA', 1, 0, 'R');
    $pdf->Cell(40, 8, number_format($line_total, 0, ',', ' ') . ' CFA', 1, 1, 'R');
}

// Total
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(150, 10, 'TOTAL', 0, 0, 'R');
$pdf->Cell(40, 10, number_format($order['total_amount'], 0, ',', ' ') . ' CFA', 1, 1, 'R');

// Footer
$pdf->Ln(20);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, utf8_decode('Merci pour votre confiance ! Orly Crochet - 2025'), 0, 1, 'C');

$pdf->Output('D', 'Facture_Orly_' . $order['payment_reference'] . '.pdf');
?>