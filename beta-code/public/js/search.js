document.getElementById("searchForm").addEventListener("submit", async function (e) {
    e.preventDefault();
    
    const climberName = document.getElementById("climberName").value;
    const videoTitle = document.getElementById("videoTitle").value;
    const token = localStorage.getItem("jwt");
    
    
    try {
        const response = await fetch("api/searchapi.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify({
                climberName: climberName,
                videoTitle: videoTitle
            })
        });
        
        const data = await response.json();
        
        if (data.success && data.vidId) {
            window.location.href = `video.php?id=${data.vidId}`;
        } else {
            alert(data.message || "Unable to find video.");
        }
    } catch (err) {
        console.error("Fetch error:", err);
        alert("Something went wrong. Please try again later.");
  }   
})