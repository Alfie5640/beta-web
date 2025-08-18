document.getElementById('registerForm').addEventListener("submit", async function (e) {
    e.preventDefault();
    
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const role = document.querySelector("input[name='role']:checked").value;
    
    try {
        const response = await fetch("api/registerapi.php", {
            method: "POST",
            headers: {
                "Content-type": "application/json"
            },
            
            body: JSON.stringify({
                username: username,
                password: password,
                role: role
            })
        });
        
        const data = await response.json();

        // Handle backend response
        const resultDiv = document.getElementById("error");
        if (data.success) {
            resultDiv.textContent = data.message;
            resultDiv.style.color = "green";
            
             // Redirect to login page after 1 second
            setTimeout(() => {
                window.location.href = "../login.php";
            }, 1000);
            
        } else {
            resultDiv.textContent = data.message;
            resultDiv.style.color = "red";
        }
        
    } catch(err) {
        console.error("Fetch error:", err);
        document.getElementById("error").textContent = "Error contacting server.";
    }
    
});