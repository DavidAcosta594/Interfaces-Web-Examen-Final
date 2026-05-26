<?php
$respuesta = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pregunta = $_POST["pregunta"] ?? "";
    
    // Aquí le damos un rol a la IA — esto es tu variación personal
    $prompt = "Eres un asistente experto en tecnología. Responde de forma clara y concisa. Pregunta: " . $pregunta;

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
    $respuesta = $json["response"] ?? "Error al obtener respuesta";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pregunta y Respuesta IA</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 40px auto; background: #1a1a2e; color: #eee; }
        h1 { color: #e94560; }
        input[type=text] { width: 100%; padding: 12px; font-size: 16px; border-radius: 8px; border: none; background: #16213e; color: white; }
        button { margin-top: 10px; padding: 10px 25px; background: #e94560; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; }
        button:hover { background: #c73652; }
        .respuesta { margin-top: 20px; background: #16213e; padding: 20px; border-radius: 8px; border-left: 4px solid #e94560; white-space: pre-wrap; line-height: 1.6; }
        .badge { display: inline-block; background: #e94560; padding: 3px 10px; border-radius: 20px; font-size: 12px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <h1>💬 Pregunta y Respuesta</h1>
    <span class="badge">🤖 Experto en tecnología</span>
    <form method="POST">
        <input type="text" name="pregunta" placeholder="¿Cuál es tu pregunta?" value="<?= htmlspecialchars($_POST["pregunta"] ?? "") ?>">
        <button type="submit">Preguntar</button>
    </form>

    <?php if ($respuesta): ?>
        <div class="respuesta">
            <strong>Respuesta:</strong><br><br>
            <?= nl2br(htmlspecialchars($respuesta)) ?>
        </div>
    <?php endif; ?>
</body>
</html>
