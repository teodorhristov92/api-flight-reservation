<!DOCTYPE html>
<html>
@include('layouts.head')
    
<body>
    @include('layouts.header')

    <h1>Flight Booking API Test</h1>

    <div class="pt-4">
        <h2>Book a Seat1</h2>
        <label for="flightId">Flight ID:</label>
        <input type="number" id="flightId" required><br>
        <label for="passengerName">Passenger ID:</label>
        <input type="number" id="passengerName" required><br>
        <label for="passengerName">Passenger Seat:</label>
        <input type="number" id="passengerSeat" required><br>
        <label for="typeSeat">Select seat type</label>
        <select name="seatType" id="typeSeat">
            <option value="Economy">Economy</option>
            <option value="Business">Business</option>
            <option value="First">First</option>
        </select>
        <button id="submitBtn" type="submit">Book Seat</button>
    </div>

    <div class="pt-4">
        <h2>Cancel Booking</h2>
        <label for="bookingId">Booking ID:</label>
        <input type="number" id="bookingId" min="1" onkeyup="if(value<0) value=0;" required><br>
        <button onclick="cancelBooking()">Cancel Booking</button>
    </div>

    <div class="pt-4">
        <h2>Load Factor Report</h2>
        <label for="flightIdReport">Flight ID:</label>
        <input type="number" id="flightIdReport" min="1" onkeyup="if(value<0) value=0;" required><br>
        <button onclick="loadReport()">Get Report</button>
    </div>

    <div>
        <h2>API Response</h2>
        <pre id="apiResponse" style="background-color:#f4f4f4; padding:10px; border:1px solid #ccc;"></pre>
    </div>
</body>

<script>
    function displayResponse(data) {
        document.getElementById("apiResponse").textContent = JSON.stringify(data, null, 2);
    }
    // Function to book a seat
    document.getElementById("submitBtn").addEventListener("click", () => {
        const seatData = {
            flight_id: document.getElementById("flightId").value.trim(),
            user_id: document.getElementById("passengerName").value.trim(),
            seat_number: document.getElementById("passengerSeat").value.trim(),
            seat_type: document.getElementById("typeSeat").value.trim()
        };
        console.log(JSON.stringify(seatData));
        fetch('http://127.0.0.1:8000/api/book', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(seatData)
        })
        .then(res => res.json())
        .then(data => displayResponse(data))
        .catch(error => displayResponse({ error: error.message }));
    });

    // Function to cancel booking
    function cancelBooking() {
        const bookingId = document.getElementById("bookingId");
        const userInput = bookingId.value.trim();
        const url = `http://127.0.0.1:8000/api/cancel/` + encodeURIComponent(userInput);
        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            }
            })
            .then(res => res.json())
            .then(data => displayResponse(data))
            .catch(error => displayResponse({ error: error.message }));
    }

    // Function to load flight load factor report
    function loadReport() {
        const flightId = document.getElementById("flightIdReport").value;
        fetch(`http://127.0.0.1:8000/api/report/${flightId}`, {
            method: 'GET'
        })
        .then(res => res.json())
        .then(data => displayResponse(data))
        .catch(error => displayResponse({ error: error.message }));
    }
</script>
</html>