async function loadVideos() {

    try {
        const token = localStorage.getItem("jwt");
        const response = await fetch("api/getVideos.php", {
            method: "GET",
            headers: {
                "Authorization": `Bearer ${token}`
            }
        });
        
        const data = await response.json();
        
        //Output data returned
        const libraryDiv = document.getElementById("videoLibrary");
        
        
        if (data.success && data.videos.length > 0) {
            
            for (let i = 0; i < data.videos.length; i++) {
                const wrapper = document.createElement("div");
                wrapper.classList.add("video-wrapper");

                // Title from titles[]
                const titleEl = document.createElement("h3");
                titleEl.textContent = data.titles[i] || "Untitled";

                // Video from videos[]
                const videoEl = document.createElement("video");
                videoEl.src = data.videos[i];
                videoEl.controls = true;
                videoEl.classList.add("video-player");

                // Add to wrapper
                wrapper.appendChild(titleEl);
                wrapper.appendChild(videoEl);

                libraryDiv.appendChild(wrapper);
            }
            
        } else if (data.success && data.videos.length == 0) {
            libraryDiv.textContent = "You haven’t uploaded any videos yet.";
            
        } else {
            libraryDiv.textContent = data.message;
            
        }
        
    } catch (err) {
        console.error("Fetch error:", err);
        document.getElementById("videoLibrary").textContent = "Error contacting server.";
    }
}

loadVideos();