const params = new URLSearchParams(window.location.search);
const videoId = params.get("id");

async function loadVideo() {
    try {
        
        const token = localStorage.getItem("jwt");
        const response = await fetch (`api/loadViewVid.php?id=${videoId}`, {
           method: "GET",
           headers: {
            "Authorization":  `Bearer ${token}`
           }
        });
        
        
        const data = await response.json();
        const vidDiv = document.getElementById("selectedVid");
        
        if (data.success) {
            
            const videoEl = document.createElement("video");
            videoEl.src = data.path;
            videoEl.controls = true;
            videoEl.classList.add("video-player");
            
            const titleEl = document.createElement("h1");
            titleEl.textContent = data.title;
            
            vidDiv.appendChild(titleEl);
            vidDiv.appendChild(videoEl);
            
        } else {
            vidDiv.textContent = "Cannot find selected video";
        }
        
        
    } catch(err) {
        console.log(err);
    }
}

loadVideo();