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

document.getElementById("uploadForm").addEventListener("submit", async function (e) {
    e.preventDefault();
    
    const form = document.getElementById("uploadForm");
    const formData = new FormData(form);
    
    const token = localStorage.getItem("jwt");
    
    
    
    try {
        const response = await fetch("api/uploadapi.php", {
            method: "POST",
            headers: {
                "Authorization": `Bearer ${token}`
            },
            body: formData
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