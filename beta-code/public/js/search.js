document.getElementById("searchForm").addEventListener("submit", async function (e) {
    e.preventDefault();
    
    const climberName = document.getElementById("climberName").value;
    const videoTitle = document.getElementById("videoTitle").value;
    const token = localStorage.getItem("jwt");
    
    
    try {
        const response = await fetch("api/searchapi.php", {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify({
                climberName: climberName,
                videoTtile: videoTitle
            })
        });
        
        const data = await response.json();
        
        //Handle data returned
        if(data.success) {
            
        } else {
            
        }
        
    } catch (err) {
        console.error("Fetch error:", err);
    }
    
    
})