const token = localStorage.getItem("jwt");

async function loadClimbers() {
 try {
        const response = await fetch("api/getList.php", {
            method: "GET",
            headers: {
                "Authorization": `Bearer ${token}`
            }
        });
        
        const data = await response.json();
        
        //Handle data returned
        const errorDiv = document.getElementById("error");
        const resultDiv = document.getElementById("cList");
     
        if (data.success) {
            resultDiv.innerHTML = "";
            for (let i=0; i < ((data.climbers).length); i++) {
                
                const wrapperDiv = document.createElement("div");
                wrapperDiv.classList.add("climber_item"); // Adds a class for styling

                const h1 = document.createElement("h1");
                h1.textContent = data.climbers[i];

                wrapperDiv.appendChild(h1);
                resultDiv.appendChild(wrapperDiv);
                
                
            }
        } else {
            errorDiv.textContent = data.message;
        }
        
        
    } catch (err) {
        console.error("Fetch error:", err);
        document.getElementById("error").textContent = "Error contacting server.";
    }
}

loadClimbers();