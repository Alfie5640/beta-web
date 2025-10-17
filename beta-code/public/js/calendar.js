function showEventForm() {
    const form = document.getElementById("hiddenForm");
    if (form.style.display == "flex") {
        form.style.display = "none";
    } else {
        form.style.display = "flex";
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