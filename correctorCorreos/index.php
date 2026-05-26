<?php
$correoCorregido = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correoOriginal = $_POST["correo"] ?? "";

    $prompt = "Eres un experto en comunicación profesional. 
    Corrige el siguiente correo electrónico: mejora la ortografía, 
    el tono profesional y la estructura. 
    Devuelve SOLO el correo corregido, sin explicaciones.
    Correo original: " . $correoOriginal;

    $data = json_encode([
        "model" => "llama3.2",
        "prompt" => $prompt,
        "stream" => false
    ]);

    $ch = curl_init("http://localhost:11434/api/generate");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    $resultado = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($resultado, true);
    $correoCorregido = $json["response"] ?? "Error al obtener respuesta";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Corrector de Correos</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 40px auto; background: #f0f4f8; color: #333; }
        h1 { color: #2c5282; }
        .columnas { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px; }
        .caja { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .caja h3 { margin-top: 0; color: #2c5282; }
        textarea { width: 100%; padding: 10px; font-size: 14px; border-radius: 8px; border: 1px solid #cbd5e0; height: 250px; resize: vertical; }
        button { margin-top: 15px; padding: 12px 30px; background: #2c5282; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; width: 100%; }
        button:hover { background: #2a4365; }
        .resultado { white-space: pre-wrap; line-height: 1.7; font-size: 14px; min-height: 250px; }
        .badge-original { color: #e53e3e; font-size: 12px; }
        .badge-corregido { color: #38a169; font-size: 12px; }
    </style>
</head>
<body>
    <h1>✉️ Corrector de Correos con IA</h1>
    <p>Pega tu correo original y la IA lo corregirá y profesionalizará automáticamente.</p>

    <form method="POST">
        <div class="columnas">
            <div class="caja">
                <h3>📝 Correo Original <span class="badge-original">(sin corregir)</span></h3>
                <textarea name="correo" placeholder="Pega aquí tu correo mal escrito..."><?= htmlspecialchars($_POST["correo"] ?? "") ?></textarea>
                <button type="submit">✨ Corregir con IA</button>
            </div>
            <div class="caja">
                <h3>✅ Correo Corregido <span class="badge-corregido">(versión profesional)</span></h3>
                <div class="resultado">
                    <?php if ($correoCorregido): ?>
                        <?= nl2br(htmlspecialchars($correoCorregido)) ?>
                    <?php else: ?>
                        <span style="color:#aaa">Aquí aparecerá el correo corregido...</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>
</body>
</html>
