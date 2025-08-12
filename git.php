
<?php
// Ambil data dari request
$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

// Verifikasi webhook (opsional)
$hub_signature = $_SERVER['HTTP_X_HUB_SIGNATURE'];
$secret = '0123456789';
$hub_signature_parts = explode('=', $hub_signature);
$hash = hash_hmac('sha1', $request_body, $secret);

if (hash_equals($hash, $hub_signature_parts[1])) {
    // Jalankan git pull
    chdir('/home/crudwork/website2');
    $output = shell_exec('git pull origin main 2>&1');
    echo "<pre>$output</pre>";
    // Log aktivitas (opsional)
    file_put_contents('git_pull.log', date('Y-m-d H:i:s') . " - " . $output . "\n", FILE_APPEND);

} else {
    // Verifikasi gagal
    file_put_contents('git_pull.log', date('Y-m-d H:i:s') . " - Gagal Pull \n", FILE_APPEND);
    http_response_code(403);
    echo "Verifikasi gagal";
}
?>  