document.getElementById("LoginForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    try {
        const response = await fetch("api/loginapi.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },

            body: JSON.stringify({
                username: username,
                password: password,
            })
        });

        const data = await response.json();

        // Handle backend response
        const resultDiv = document.getElementById("error");
        if (data.success && data.token) {
        
            //Store JWT. [NOTE dont store in local storage normally as vulnerable to xss]
            localStorage.setItem("jwt", data.token);
            
            // Redirect to corresponding home page
            if (data.role == "climber") {
                window.location.href = "../climberHome.php";
            } else {
                window.location.href = "../instructorHome.php";
            }

        } else {
            resultDiv.textContent = data.message;
            resultDiv.style.color = "red";
        }

    } catch (err) {
        console.error("Fetch error:", err);
        document.getElementById("error").textContent = "Error contacting server.";
    }
})
