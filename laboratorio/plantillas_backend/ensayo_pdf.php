<?php
// Copiar a backend/ensayo_pdf.php. Ejecutar desde backend: php ensayo_pdf.php
require __DIR__.'/vendor/autoload.php';
$privado = __DIR__.'/storage/app/private/ensayo_pdf';
if (!is_dir($privado) && !mkdir($privado, 0700, true)) {
    throw new RuntimeException('No se puede crear directorio privado.');
}
$opciones = new Dompdf\Options();
$opciones->set('isRemoteEnabled', false);
$opciones->set('isPhpEnabled', false);
$opciones->set('defaultFont', 'DejaVu Sans');
$opciones->set('chroot', $privado);
$pdf = new Dompdf\Dompdf($opciones);
$pdf->loadHtml('<h1>Ensayo educativo FM-WEB-01</h1><p>Documento ficticio para comprobar la dependencia PDF. No contiene firma ni autorizacion real.</p>', 'UTF-8');
$pdf->setPaper('A4');
$pdf->render();
$salida = $privado.'/ensayo.pdf';
if (file_exists($salida)) {
    throw new RuntimeException('Ya existe ensayo.pdf; compruebalo antes de repetir con otro nombre.');
}
if (file_put_contents($salida, $pdf->output(), LOCK_EX) === false) {
    throw new RuntimeException('No se pudo guardar el PDF.');
}
echo 'Archivo privado: '.$salida.PHP_EOL.'SHA256: '.hash_file('sha256', $salida).PHP_EOL;
