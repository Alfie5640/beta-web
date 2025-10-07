const params = new URLSearchParams(window.location.search);
const videoId = params.get("id");
let clickX, clickY;


//async function loadComments() {
    
//}

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
            const container = document.createElement("div");
            container.id = "videoContainer";
            container.style.position = "relative"; // important for positioning labels

            const titleEl = document.createElement("h1");
            titleEl.textContent = data.title;

            const videoWrapper = document.createElement("div");
            videoWrapper.style.position = "relative"; // ensures labels match video size

            const videoEl = document.createElement("video");
            videoEl.src = data.path;
            videoEl.controls = true;
            videoEl.classList.add("video-player");

            videoWrapper.appendChild(videoEl);
            container.appendChild(titleEl);
            container.appendChild(videoWrapper);
            vidDiv.appendChild(container);

            // LABEL CONTAINER
            const labelContainer = document.createElement("div");
            labelContainer.id = "labelContainer";
            labelContainer.style.position = "absolute";
            labelContainer.style.top = "0";
            labelContainer.style.left = "0";
            labelContainer.style.width = "100%";
            labelContainer.style.height = "100%";
            labelContainer.style.pointerEvents = "none";
            labelContainer.style.zIndex = "10";
            videoWrapper.appendChild(labelContainer);

            const labelResponse = await fetch(`api/loadLabels.php?id=${videoId}`, {
                method: "GET",
                headers: {
                    "Authorization": `Bearer ${token}`
                }
            });

            const labelData = await labelResponse.json();

            if (labelData.success && labelData.labelText.length > 0) {
                for (let i = 0; i < labelData.labelText.length; i++) {
                    const label = document.createElement("div");
                    const text = labelData.labelText[i];

                    let color = "white";
                    if (text === "start") color = "green";
                    else if (text === "crux") color = "orange";
                    else if (text === "top") color = "blue";
                    else color = "#D90166";

                    const labelTextEl = document.createElement("h1");
                    labelTextEl.textContent = text;
                    labelTextEl.style.margin = "0";
                    labelTextEl.style.fontSize = "16px";

                    label.appendChild(labelTextEl);

                    label.classList.add("video-label");

                    label.style.position = "absolute";
                    label.style.left = `${labelData.xPos[i] * 100}%`;
                    label.style.top = `${labelData.yPos[i] * 100}%`;
                    label.style.transform = "translate(-50%, -50%)";
                    label.style.background = color;
                    label.style.color = "white";
                    label.style.borderRadius = "50%";
                    label.style.padding = "5px 8px";
                    label.style.fontSize = "10px";
                    label.style.pointerEvents = "none";
                    label.style.zIndex = "20";

                    labelContainer.appendChild(label);
                }
            }

            // Label selector menu
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
                clickX = (e.clientX - rect.left) / rect.width;
                clickY = (e.clientY - rect.top) / rect.height;
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

                resultDiv.textContent = data.message;
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

//    loadComments();
    
}




document.getElementById("comSub").addEventListener("submit", async function (e) {
    e.preventDefault();

    const token = localStorage.getItem("jwt");
    const comment = document.getElementById("commentArea").value;

    try {
        const response = await fetch(`api/submitComment.php?id=${videoId}`, {
            method: "POST",
            headers: {
                "Content-type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify({
                comment: comment
            })
        });

        const data = await response.json();
        const resultDiv = document.getElementById("commentsMade");
        
        if(data.success) {
            document.getElementById("commentArea").value = "";
            resultDiv.textContent = data.message;
        } else {
            document.getElementById("commentArea").value = "";
            resultDiv.textContent = data.message;
        }

    } catch (err) {
        console.log(err);
    }
});

loadVideo();
