let allVideos = [];

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
        
        
        if (data.success) {
             allVideos = data.videos.map((path, i) => ({
                path: path,
                title: data.titles[i] || "Untitled",
                grade: data.grades ? data.grades[i] : "N/A",
                favorite: data.favorites[i] || false,
                id: data.videoIds[i]
            }));
            renderVideos(allVideos);
        } else {
            libraryDiv.textContent = data.message;
            
        }
        
    } catch (err) {
        console.error("Fetch error:", err);
        document.getElementById("videoLibrary").textContent = "Error contacting server.";
    }
}

document.getElementById("gradeFilter").addEventListener("change", (e) => {
    const selected = e.target.value;

    if (selected === "all") {
        renderVideos(allVideos);
    } else {
        const filtered = allVideos.filter(v => v.grade === selected);
        renderVideos(filtered);
    }
});

function renderVideos(videos) {
    const libraryDiv = document.getElementById("videoLibrary");
    libraryDiv.innerHTML = "";

    videos.forEach(video => {
        const wrapper = document.createElement("div");
        wrapper.classList.add("video-wrapper");

        const titleEl = document.createElement("h3");
        const link = document.createElement("a");
        link.href = `video.php?id=${video.id}`; //Passes the id of the selected video in the URL
        link.textContent = video.title;
        titleEl.appendChild(link);
        
        const videoEl = document.createElement("video");
        videoEl.src = video.path;
        videoEl.controls = true;
        videoEl.classList.add("video-player");
        
        const favButton = document.createElement("button");
        favButton.classList.add("favorite");
        
        if (video.favorite) {
            favButton.classList.add("addedFavorite");
            favButton.textContent = 'Unfavorite ★';
            favButton.onclick = () => unfavoriteVid(video.id, favButton);
        } else {
            favButton.textContent = 'Favourite ☆';
            favButton.onclick = () => addToFavorites(video.id, favButton);
        }
        
        wrapper.appendChild(titleEl);
        wrapper.appendChild(videoEl);
        wrapper.appendChild(favButton);
        libraryDiv.appendChild(wrapper);
    });
}

async function addToFavorites(videoId, button) {
    try {
        const token = localStorage.getItem("jwt");
        const response = await fetch("api/addFavorite.php", {
            method: "POST",
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json"
            },
            
            body: JSON.stringify({
                videoId: videoId
            })
            
        });
        
        const data = await response.json();
        
        if (data.success) {
            button.classList.add("addedFavorite");
            button.textContent = "Unfavourite ★";
            button.onclick = () => unfavoriteVid(videoId, button);
        }
        
    } catch(err) {
        console.error("Error adding favorite:", err);
    }
}

async function unfavoriteVid(videoId, button) {
    try {
        const token = localStorage.getItem("jwt");
        const response = await fetch("api/unfavoriteVid.php", {
            method: "POST",
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json"
            },
            
            body: JSON.stringify({
                videoId: videoId
            })
            
        });
        
        const data = await response.json();
        
        if(data.success) {
            button.classList.remove("addedFavorite");
            button.textContent = "Favorite ☆";
            button.onclick = () => addToFavorites(videoId, button);
            button.addEventListener("click", () => addToFavorites(videoId, button));
        }
        
    } catch (err) {
        console.error("Error adding favorite:", err);
    }
}


document.getElementById("searchFilter").addEventListener("input", (e) => {
    const searchTerm = e.target.value.toLowerCase();

    const filtered = allVideos.filter(video =>
        video.title.toLowerCase().includes(searchTerm) ||
        video.grade.toLowerCase().includes(searchTerm)
    );

    renderVideos(filtered);
});

loadVideos();