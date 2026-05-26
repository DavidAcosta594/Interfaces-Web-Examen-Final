<?php
$respuesta = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pregunta = $_POST["pregunta"] ?? "";

    $data = json_encode([
        "model" => "llama3.2",
        "prompt" => $pregunta,
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
    $respuesta = $json["response"] ?? "Error al obtener respuesta";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MicroChat IA</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 40px auto; background: #f4f4f4; }
        h1 { color: #333; }
        textarea { width: 100%; padding: 10px; font-size: 16px; border-radius: 8px; border: 1px solid #ccc; }
        button { margin-top: 10px; padding: 10px 20px; background: #4a90e2; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; }
        button:hover { background: #357abd; }
        .respuesta { margin-top: 20px; background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #4a90e2; white-space: pre-wrap; }
    </style>
</head>
<body>
    <h1>🤖 MicroChat IA</h1>
    <form method="POST">
        <textarea name="pregunta" rows="4" placeholder="Escribe tu pregunta aquí..."><?= htmlspecialchars($_POST["pregunta"] ?? "") ?></textarea>
        <button type="submit">Enviar</button>
    </form>

    <?php if ($respuesta): ?>
        <div class="respuesta">
            <strong>Respuesta:</strong><br><br>
            <?= nl2br(htmlspecialchars($respuesta)) ?>
        </div>
    <?php endif; ?>
</body>
</html>
