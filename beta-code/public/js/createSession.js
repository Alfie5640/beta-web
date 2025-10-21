async function submitEvent() {
    try {
        const token = localStorage.getItem("jwt");
        
        const climber = document.getElementById("climberToSend").value;
        const date = document.getElementById("eventDate").value;
        const timeStart = document.getElementById("eventStart").value;
        const timeEnd = document.getElementById("eventEnd").value;
        const title = document.getElementById("eventTitle").value;

        const response = await fetch("api/storeInstructorEvent.php", {
            method: "POST",
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                climber: climber,
                date: date,
                timeStart: timeStart,
                timeEnd: timeEnd,
                title: title
            })
        });

        const data = await response.json();

        if (data.success) {
            alert("Event added");
        } else {
            alert("Problem saving event");
        }

    } catch (err) {
        console.log(err);
    }
}