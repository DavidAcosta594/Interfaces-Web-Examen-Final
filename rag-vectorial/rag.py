import chromadb
import ollama
import os

# Inicializar ChromaDB
client = chromadb.Client()
coleccion = client.create_collection("conocimiento")

# Cargar el archivo de texto y dividir en fragmentos
with open("datos.txt", "r", encoding="utf-8") as f:
    lineas = [l.strip() for l in f.readlines() if l.strip()]

# Indexar cada línea en ChromaDB
coleccion.add(
    documents=lineas,
    ids=[f"id{i}" for i in range(len(lineas))]
)

print("✅ Base de datos vectorial creada con", len(lineas), "fragmentos\n")

# Bucle de preguntas
while True:
    pregunta = input("Tu pregunta (o 'salir'): ")
    if pregunta.lower() == "salir":
        break

    # Buscar los fragmentos más relevantes
    resultados = coleccion.query(query_texts=[pregunta], n_results=2)
    contexto = "\n".join(resultados["documents"][0])

    # Enviar a Ollama con el contexto
    prompt = f"""Usa únicamente este contexto para responder:
{contexto}

Pregunta: {pregunta}
Responde de forma concisa."""

    respuesta = ollama.generate(model="llama3.2", prompt=prompt)
    print("\n🤖 Respuesta:", respuesta["response"], "\n")
