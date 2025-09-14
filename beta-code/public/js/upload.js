const fileInput = document.getElementById("fileToUpload");
const videoPreview = document.getElementById("videoPreview");

fileInput.addEventListener("change", function () {
    const file = this.files[0];
    if (file) {
        const fileURL = URL.createObjectURL(file); // temporary local URL
        videoPreview.src = fileURL;
        videoPreview.style.display = "block"; // show preview
    } else {
        videoPreview.style.display = "none"; // hide if no file
    }
});

document.getElementById("addForm").addEventListener("submit", async function (e) {
    e.preventDefault();
    
    const video = document.getElementById("fileToUpload").value;
    const token = localStorage.getItem("jwt");
    
    
    
    try {
        const response = await fetch("api/connectClimbers.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify({
                video: video,
            })
        });
        
        const data = await response.json();
        
        //Handle data returned
        
    } catch (err) {
        console.error("Fetch error:", err);
        document.getElementById("error").textContent = "Error contacting server.";
    }
    
    
})