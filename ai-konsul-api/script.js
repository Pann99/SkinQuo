async function sendMessage(){

    const input = document.getElementById("userInput");
    const chatBox = document.getElementById("chatBox");

    const query = input.value;

    // tampilkan chat user
    chatBox.innerHTML += `
        <div class="message user">
            ${query}
        </div>
    `;

    input.value = "";

    // request ke backend
    const response = await fetch("http://127.0.0.1:8000/api/recommend",{
        method:"POST",
        headers:{
            "Content-Type":"application/json"
        },
        body:JSON.stringify({
            query:query
        })
    });

    const data = await response.json();

    let botHTML = `
        <div class="message bot">
            <div class="bot-text">
                Berikut rekomendasi skincare untuk kamu:
            </div>
    `;

    data.recommendations.forEach(item => {

        botHTML += `
            <div class="product-card">

                <h3>${item.product_name}</h3>

                <p><b>Brand:</b> ${item.brand}</p>

                <p><b>Kategori:</b> ${item.category}</p>

                <p><b>Similarity:</b> ${item.similarity_score}</p>

                <a href="${item.link_produk}" target="_blank">
                    Lihat Produk
                </a>

            </div>
        `;
    });

    botHTML += `</div>`;

    chatBox.innerHTML += botHTML;

    chatBox.scrollTop = chatBox.scrollHeight;
}