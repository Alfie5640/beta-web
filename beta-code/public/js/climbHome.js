async function loadFavourites() {
    try {
        
        const token = localStorage.getItem("jwt");
        const response = await fetch("api/loadFavourite.php", {
            method: "GET",
            headers: {
                "Authorization":  `Bearer ${token}`,
                "Content-type": "application/json"
            }
        });
        
        const data = await response.json();

        const favDiv = document.getElementById("liked_videos");
        
        
        if (data.success) {
            renderVideos(data.favourites, favDiv);
        } else{
            favDiv.textContent = data.message;
        }
        
    } catch(err) {
        console.error(err);
    }
}



function renderVideos(favourites, favDiv) {
    favDiv.innerHTML = "";
    favourites.forEach(favourite => {
        const wrapper = document.createElement("div");
        wrapper.classList.add("video-wrapper");

        const videoEl = document.createElement("video");
        videoEl.src = favourite;
        videoEl.controls = true;
        videoEl.classList.add("video-player");
        
        wrapper.appendChild(videoEl);
        favDiv.appendChild(wrapper);
    });
}

loadFavourites();