function showEventForm() {
    const form = document.getElementById("hiddenForm");
    if (form.style.display == "flex") {
        form.style.display = "none";
    } else {
        form.style.display = "flex";
    }
}