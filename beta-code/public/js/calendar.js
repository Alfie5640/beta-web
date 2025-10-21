function showEventForm() {
    const form = document.getElementById("hiddenForm");
    if (form.style.display == "flex") {
        form.style.display = "none";
    } else {
        form.style.display = "flex";
    }
}

async function deletePastEvents(eventIds, startTimes, endTimes, eventDates) {
    const token = localStorage.getItem("jwt");

    const now = new Date();
    const currentDate = now.toISOString().split("T")[0]; // "YYYY-MM-DD"
    const currentTime = now.toTimeString().split(" ")[0]; // "HH:MM:SS"

    for (let i = 0; i < eventIds.length; i++) { 
        const eventDate = eventDates[i];
        const eventEnd = endTimes[i];

        const isPast = eventDate < currentDate || (eventDate === currentDate && eventEnd < currentTime);

        if (isPast) {
            try {
                const response = await fetch("api/deleteEvent.php", {
                    method: "POST",
                    headers: {
                        "Authorization": `Bearer ${token}`,
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ eventId: eventIds[i] })
                });

                const data = await response.json();

                if (!data.success) {
                    console.warn(`Failed to delete event: ${data.message}`);
                } else {
                    console.log(`Deleted past event`);
                }

            } catch (err) {
                console.error(`Error deleting event:`, err);
            }
        }
    }
}


async function loadEvents() {
    try {
        const token = localStorage.getItem("jwt");
    
        const response = await fetch("api/loadEvents.php", {
            method: "GET",
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json"
            }
        });

        const data = await response.json();
        const maincontent = document.querySelector(".maincontent");

        if (data.success && data.title.length > 0) {
            
            await deletePastEvents(data.eventIds, data.startTime, data.endTime, data.eventDate)

            for (let i = 0; i < data.title.length; i++) {
                const title = data.title[i];
                const start = data.startTime[i];
                const end = data.endTime[i];
                const date = data.eventDate[i];

                const eventDiv = document.createElement("div");
                eventDiv.classList.add("calendarElement", "eventItem");
                eventDiv.innerHTML = `
                    <h1>${title}</h1> <br>
                    <p>Date: ${date}</p>
                    <p>Time: ${start} - ${end}</p>
                `;

                maincontent.appendChild(eventDiv);
            }
        } 
    } catch (err) {
        console.error("Error loading events:", err);
    }
}

async function submitEvent() {
    try {
        const token = localStorage.getItem("jwt");
    
        const date = document.getElementById("eventDate").value;
        const timeStart = document.getElementById("eventStart").value;
        const timeEnd = document.getElementById("eventEnd").value;
        const title = document.getElementById("eventTitle").value;
    
        const response = await fetch("api/storeEvent.php", {
            method: "POST",
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                date: date,
                timeStart: timeStart,
                timeEnd: timeEnd,
                title: title
            })
        });
    
        const data = await response.json();
    
    if (data.success) {
        alert("Event saved successfully");
        document.getElementById("hiddenForm").style.display = "none";
    } else {
        alert("Problem saving event")
    }
        
    } catch(err) {
        console.log(err);
    }
}

loadEvents();