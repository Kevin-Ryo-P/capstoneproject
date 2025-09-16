const daysTag = document.querySelector(".days"),
      currentDateTag = document.querySelector(".current-date"),
      prevNextIcon = document.querySelectorAll(".icons span"),
      table = document.getElementById("reservationTable"),
      todayTag = document.getElementById("today");

let date = new Date(),
    currYear = date.getFullYear(),
    currMonth = date.getMonth();

const months = ["January", "February", "March", "April", "May", "June", "July",
              "August", "September", "October", "November", "December"];

const renderCalendar = () => {
    let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(),
        lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(),
        lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(),
        lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate();
    let liTag = "";

    for (let i = firstDayofMonth; i > 0; i--) {
        liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
    }

    for (let i = 1; i <= lastDateofMonth; i++) {
        let isToday = i === date.getDate() && currMonth === new Date().getMonth() 
                     && currYear === new Date().getFullYear() ? "active" : "";
        liTag += `<li class="${isToday}" data-date="${i}">${i}</li>`;
    }

    for (let i = lastDayofMonth; i < 6; i++) {
        liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`
    }
    currentDateTag.innerText = `${months[currMonth]} ${currYear}`;
    daysTag.innerHTML = liTag;
}



renderCalendar();
updateTable(eventsData);

prevNextIcon.forEach(icon => {
    icon.addEventListener("click", () => {
        if (icon.id === "prevcal") {
            currMonth--;
            if (currMonth < 0) {
                currYear--;
                currMonth = 11;
            }
        } else {
            currMonth++;
            if (currMonth > 11) {
                currYear++;
                currMonth = 0;
            }
        }
        date = new Date(currYear, currMonth, 1); // Set the date to the 1st day of the month
        currentDateInput.value = date.toLocaleDateString();
        
        // Update the displayed date to the current date
        const selectedDate = date.getDate();
        const selectedMonth = date.getMonth() + 1;
        const selectedYear = date.getFullYear();
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        const selectedMonthName = monthNames[selectedMonth - 1];
        todayTag.textContent = `${selectedMonthName} ${selectedDate}, ${selectedYear}`;
        
        renderCalendar();
        updateTable(eventsData);
    });
});

const currentDateInput = document.getElementById('current-date-input');

daysTag.addEventListener("click", (e) => {
    if (e.target.tagName === "LI" && !e.target.classList.contains("inactive")) {
        let selectedDate = e.target.textContent;
        let selectedMonth = currMonth + 1;
        let selectedYear = currYear;

        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        const selectedMonthName = monthNames[selectedMonth - 1];

        currentDateInput.value = `${selectedYear}-${selectedMonth}-${selectedDate}`;
        todayTag.textContent = `${selectedMonthName} ${selectedDate}, ${selectedYear}`;
        currentDateTag.textContent = `${months[currMonth]} ${currYear}`;

        // Update the currentDate variable in tables.blade.php
        const currentDate = new Date(`${selectedYear}-${selectedMonth}-${selectedDate}`);
        document.getElementById('current-date-input').value = currentDate.toLocaleDateString();

        // Remove the active class from all li elements
        document.querySelectorAll('li.active').forEach(element => element.classList.remove('active'));

        // Remove the displayed-date class from all li elements
        document.querySelectorAll('li.displayed-date').forEach(element => element.classList.remove('displayed-date'));

        const activeDateElement = document.querySelector(`li[data-date="${selectedDate}"]`);
        activeDateElement.classList.add("displayed-date");
        
        console.log("Updating table...");
        updateTable(eventsData);
    }
});

//pak saya mau bunuh diri kalo saya enggak lulus tahun ini