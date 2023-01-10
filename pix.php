<?php

/* valores a serem configurados */
$chavepix = ''; //trocar
$nomebeneficiario = ''; //trocar
$cidadebeneficiario = ''; //trocar
$identificador = ''; //trocar
$valor = ''; //trocar

$valor = number_format($valor, 2, '.', '');
$nomebeneficiario = substr($nomebeneficiario, 0, 25);

  function crcChecksum($str) {
    // The PHP version of the JS str.charCodeAt(i)
    function charCodeAt($str, $i) {
      return ord(substr($str, $i, 1));
    }
    $crc = 0xFFFF;
    $strlen = strlen($str);
    for($c = 0; $c < $strlen; $c++) {
    $crc ^= charCodeAt($str, $c) << 8;
      for($i = 0; $i < 8; $i++) {
        if($crc & 0x8000) {
          $crc = ($crc << 1) ^ 0x1021;
        } else {
          $crc = $crc << 1;
        }
     }       
  }
  $hex = $crc & 0xFFFF;
  $hex = dechex($hex);
  $hex = strtoupper($hex);
        
  return str_pad($hex, 4, '0', STR_PAD_LEFT);
}

$pix = '000201'. //Payload Format Indicator
       '26'.(strlen($chavepix)+22). //Merchant Account Information
          '0014BR.GOV.BCB.PIX'. //Globally Unique Identifier
          '01'.str_pad(strlen($chavepix), 2, '0', STR_PAD_LEFT).$chavepix. //Chave PIX
       '52040000'. //Merchant Category Code
       '5303986'. //Transaction Currency
       '54'.str_pad(strlen($valor), 2, '0', STR_PAD_LEFT).$valor. //Transaction Amount
       '5802BR'. //Country Code
       '59'.str_pad(strlen($nomebeneficiario), 2, '0', STR_PAD_LEFT).$nomebeneficiario. //Merchant Name
       '60'.str_pad(strlen($cidadebeneficiario), 2, '0', STR_PAD_LEFT).$cidadebeneficiario. //Merchant City
       '62'.(str_pad(strlen($identificador), 2, '0', STR_PAD_LEFT)+4). //Additional Data Field Template
         '05'.str_pad(strlen($identificador), 2, '0', STR_PAD_LEFT).$identificador. //Reference Label
       '6304'; //cabeçalho CRC ele é adicionado abaixo
$pix = $pix.crcChecksum($pix);
$qrcode = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl='.rawurlencode($pix);
?>

<p>
<script type="text/javascript">function copy() {  let textarea = document.getElementById("pixcc");  textarea.select();  document.execCommand("copy");}</script>
Para proceder o pagamento seguir as instruções abaixo:
<br /><br />
PIX CHAVE: <br />
<?= $chavepix ?>
<br /><br />
PIX COPY COLA:<br />
<textarea id='pixcc' readonly><?= $pix ?></textarea><br />
<button onclick="copy()">Copiar</button>
<br /><br />
PIX QR-CODE:<br />
<img src=<?= $qrcode ?> />
<br />
O prazo para PIX é de 3 dias úteis, após esse prazo o pedido será cancelado automaticamente.
<br />
</p>
