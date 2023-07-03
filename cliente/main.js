function lerEmails() {
  const elementos = document.querySelectorAll("a, span.go, p");
  const emailRegex = /\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\b/;
  const emails = [];

  elementos.forEach(elemento => {
    const texto = elemento.textContent.trim();
    const matches = texto.match(emailRegex);
    if (matches) {
      emails.push(...matches);
    }
  });

  return emails;
}

setTimeout(async () => {

  const emails = lerEmails();


  try {
    // Envia uma requisição GET para a API
    const response = await fetch('http://localhost:8000/api.php', {
      method: 'GET'
    });

    const data = await response.json();

    // Compara os emails capturados com os emails da resposta da API
    const suspiciousEmails = emails.filter(email =>
      data.some(item => item.email === email)
    );

    if (suspiciousEmails.length > 0) {
      alert("EMAIL SUSPEITO DETECTADO!!!\n\nEmail(s) suspeito(s): " + suspiciousEmails.join(", "));
    }
  } catch (error) {
    console.error(error);
  }
}, 3000);

//chrome.exe --disable-web-security --user-data-dir="C:\chrome_dev_session"
//php -S localhost:8000 localhost = ip_destino
