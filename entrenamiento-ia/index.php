<?php
$respuesta = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pregunta = $_POST["pregunta"] ?? "";

    // Cargamos el JSON con el conocimiento
    $conocimiento = file_get_contents("conocimiento.json");

    $prompt = "Eres un asistente de atención al cliente. 
    Tienes acceso a esta base de conocimiento en formato JSON: 
    " . $conocimiento . "
    Basándote ÚNICAMENTE en esa información, responde la siguiente pregunta del usuario.
    Si la respuesta no está en el JSON, di que no tienes esa información.
    Pregunta: " . $pregunta;

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
    <title>Chatbot Entrenado con JSON</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 40px auto; background: #0f0f0f; color: #eee; }
        h1 { color: #00d4aa; }
        p.sub { color: #888; font-size: 14px; }
        input[type=text] { width: 100%; padding: 12px; font-size: 15px; border-radius: 8px; border: none; background: #1e1e1e; color: white; margin-top: 10px; }
        button { margin-top: 10px; padding: 12px; width: 100%; background: #00d4aa; color: #0f0f0f; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: bold; }
        button:hover { background: #00b894; }
        .respuesta { margin-top: 25px; background: #1e1e1e; padding: 20px; border-radius: 10px; border-left: 4px solid #00d4aa; white-space: pre-wrap; line-height: 1.7; }
        .conocimiento { margin-top: 30px; background: #1a1a1a; padding: 15px; border-radius: 10px; font-size: 12px; color: #555; }
        .conocimiento h4 { color: #444; margin-top: 0; }
    </style>
</head>
<body>
    <h1>🧠 Chatbot Entrenado</h1>
    <p class="sub">Este asistente solo responde con la información que le hemos enseñado en el JSON.</p>

    <form method="POST">
        <input type="text" name="pregunta" placeholder="Ej: ¿Cuál es el horario de atención?" value="<?= htmlspecialchars($_POST["pregunta"] ?? "") ?>">
        <button type="submit">Preguntar</button>
    </form>

    <?php if ($respuesta): ?>
        <div class="respuesta">
            <strong>🤖 Respuesta:</strong><br><br>
            <?= nl2br(htmlspecialchars($respuesta)) ?>
        </div>
    <?php endif; ?>

    <div class="conocimiento">
        <h4>📚 Base de conocimiento cargada (conocimiento.json)</h4>
        <?php
            $data = json_decode(file_get_contents("conocimiento.json"), true);
            foreach($data as $item) {
                echo "• " . htmlspecialchars($item["pregunta"]) . "<br>";
            }
        ?>
    </div>
</body>
</html>
