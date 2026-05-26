<?php
$respuesta = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pregunta = $_POST["pregunta"] ?? "";

    // PHP le manda la pregunta al servidor Python
    $data = json_encode(["pregunta" => $pregunta]);

    $ch = curl_init("http://localhost:5000/preguntar");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    $resultado = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($resultado, true);
    $respuesta = $json["respuesta"] ?? "Error: ¿está corriendo el servidor Python?";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Stack PHP + Python</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 40px auto; background: #1e1e2e; color: #cdd6f4; }
        h1 { color: #cba6f7; }
        .stack { display: flex; gap: 10px; margin-bottom: 20px; }
        .badge { padding: 5px 15px; border-radius: 20px; font-size: 13px; font-weight: bold; }
        .php { background: #7c3aed; color: white; }
        .python { background: #2563eb; color: white; }
        .arrow { color: #a6e3a1; font-size: 20px; align-self: center; }
        input[type=text] { width: 100%; padding: 12px; font-size: 15px; border-radius: 8px; border: none; background: #313244; color: #cdd6f4; }
        button { margin-top: 10px; width: 100%; padding: 12px; background: #cba6f7; color: #1e1e2e; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: bold; }
        button:hover { background: #b48ee8; }
        .respuesta { margin-top: 25px; background: #313244; padding: 20px; border-radius: 10px; border-left: 4px solid #a6e3a1; white-space: pre-wrap; line-height: 1.7; }
        .flujo { margin-top: 30px; background: #181825; padding: 15px; border-radius: 10px; font-size: 12px; color: #6c7086; text-align: center; }
    </style>
</head>
<body>
    <h1>⚡ Stack PHP + Python</h1>

    <div class="stack">
        <span class="badge php">PHP (Frontend)</span>
        <span class="arrow">→</span>
        <span class="badge python">Python Flask (Backend)</span>
        <span class="arrow">→</span>
        <span class="badge php">Ollama IA</span>
    </div>

    <form method="POST">
        <input type="text" name="pregunta" placeholder="Escribe tu pregunta..." value="<?= htmlspecialchars($_POST["pregunta"] ?? "") ?>">
        <button type="submit">Enviar al servidor Python</button>
    </form>

    <?php if ($respuesta): ?>
        <div class="respuesta">
            <strong>🤖 Respuesta desde Python:</strong><br><br>
            <?= nl2br(htmlspecialchars($respuesta)) ?>
        </div>
    <?php endif; ?>

    <div class="flujo">
        📡 Flujo: Navegador → PHP (puerto 80) → Python Flask (puerto 5000) → Ollama (puerto 11434)
    </div>
</body>
</html>
