const params = new URLSearchParams(window.location.search);
const videoId = params.get("id");
let clickX, clickY;

async function loadVideo() {
    try {
        const token = localStorage.getItem("jwt");
        const response = await fetch(`api/loadViewVid.php?id=${videoId}`, {
            method: "GET",
            headers: {
                "Authorization": `Bearer ${token}`
            }
        });

        const data = await response.json();
        const vidDiv = document.getElementById("selectedVid");

        if (data.success) {
            
            //Load Labels HERE ------------------------------------------------------------------------
            
            const container = document.createElement("div");
            container.id = "videoContainer";

            const titleEl = document.createElement("h1");
            titleEl.textContent = data.title;

            const videoEl = document.createElement("video");
            videoEl.src = data.path;
            videoEl.controls = true;
            videoEl.classList.add("video-player");

            container.appendChild(titleEl);
            container.appendChild(videoEl);
            vidDiv.appendChild(container);

            const labelContainer = document.createElement("div");
            labelContainer.id = "labelContainer";
            container.appendChild(labelContainer);

            // Label selector menu (hidden initially)
            const labelSelector = document.createElement("div");
            labelSelector.id = "labelSelector";
            labelSelector.classList.add("hidden");
            labelSelector.innerHTML = `
                <button data-type="start">Start</button>
                <button data-type="crux">Crux</button>
                <button data-type="top">Top</button>
                <input type="text" id="customLabelInput" placeholder="Custom" />`;
            
            container.appendChild(labelSelector);

            let isPausedByClick = false;

            videoEl.addEventListener("click", (e) => {
                const rect = videoEl.getBoundingClientRect();
                clickX = e.clientX - rect.left;
                clickY = e.clientY - rect.top;
                e.preventDefault();
                e.stopPropagation();

                videoEl.pause();
                isPausedByClick = true;
                labelSelector.classList.remove("hidden");
            });

            videoEl.addEventListener("play", () => {
                if (isPausedByClick) {
                    videoEl.pause();
                }
            });

            labelSelector.addEventListener("click", (e) => {
                const labelType = e.target.getAttribute("data-type");

                if (labelType) {
                    confirmLabel(labelType);
                }
            });

            document.getElementById("customLabelInput").addEventListener("keydown", (e) => {
                if (e.key === "Enter") {
                    const customText = e.target.value.trim();
                    if (customText) {
                        confirmLabel(customText);
                    }
                }
            });

            async function confirmLabel(labelType) {
                
                const token = localStorage.getItem("jwt");
                const response = await fetch(`api/storeLabels.php?id=${videoId}`, {
                    method: "POST",
                    headers: {
                        "Authorization": `Bearer ${token}`,
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        labelType: labelType,
                        xPos: clickX,
                        yPos: clickY,
                        timestamp: videoEl.currentTime,
                    })
                });

                const data = await response.json();
                const resultDiv = document.getElementById("commentsMade");
                
                if (data.success) {
                    resultDiv.textContent = data.message;
                } else {
                    resultDiv.textContent = data.message;
                }
                
                labelSelector.classList.add("hidden");
                isPausedByClick = false;
                videoEl.play();
            }
        } else {
            vidDiv.textContent = "Cannot find selected video";
        }

    } catch (err) {
        console.log(err);
    }
}

loadVideo();
