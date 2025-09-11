document.getElementById("addForm").addEventListener("submit", async function (e) {
    e.preventDefault();
    
    const climberName = document.getElementById("climberUsername").value;
    const token = localStorage.getItem("jwt");
    
    
    
    try {
        const response = await fetch("api/connectClimbers.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify({
                climberName: climberName,
            })
        });
        
        const data = await response.json();
        
        //Handle data returned
        const resultDiv = document.getElementById("error");
        if(data.success) {
            resultDiv.textContent = data.message;
            resultDiv.style.color = "green";
        } else {
            resultDiv.textContent = data.message;
            resultDiv.style.color = "red";
        }
        
    } catch (err) {
        console.error("Fetch error:", err);
        document.getElementById("error").textContent = "Error contacting server.";
    }
    
    
})