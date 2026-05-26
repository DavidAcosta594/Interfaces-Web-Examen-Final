from flask import Flask, request, jsonify
import ollama

app = Flask(__name__)

@app.route("/preguntar", methods=["POST"])
def preguntar():
    datos = request.get_json()
    pregunta = datos.get("pregunta", "")
    
    respuesta = ollama.generate(
        model="llama3.2",
        prompt=f"Responde de forma breve y clara: {pregunta}"
    )
    
    return jsonify({"respuesta": respuesta["response"]})

if __name__ == "__main__":
    print("🐍 Servidor Python escuchando en puerto 5000...")
    app.run(port=5000)
