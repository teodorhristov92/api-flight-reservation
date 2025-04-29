<!DOCTYPE html>
<html>
@include('layouts.head')
    
<body>
    @include('layouts.header')

    <h1>Flight Booking API Test</h1>

    <div>
        <form>
            <h2>Book a Seat</h2>
            <label for="flightId">Flight ID:</label>
            <input type="number" id="flightId" min="1" required><br>
            <label for="passengerName">Passenger Name:</label>
            <input type="number" id="passengerName" min="1" required><br>
            <label for="passengerName">Passenger Seat:</label>
            <input type="number" id="passengerSeat" pattern="([0–9]*)" min="1" max="1000" value="1" required><br>
            <button type="submit" onclick="bookSeat(event)">Book Seat</button>
        </form>
    </div>

    <div>
        <h2>Cancel Booking</h2>
        <label for="bookingId">Booking ID:</label>
        <input type="number" id="bookingId" required><br>
        <button onclick="cancelBooking()">Cancel Booking</button>
    </div>

    <div>
        <h2>Load Factor Report</h2>
        <label for="flightIdReport">Flight ID:</label>
        <input type="text" id="flightIdReport" required><br>
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
    function bookSeat(event) {
        event.preventDefault();

        const seatData = {
            flight_id: document.getElementById("flightId").value.trim(),
            user_id: document.getElementById("passengerName").value.trim(),
            seat_number: document.getElementById("passengerSeat").value.trim(),
        };
        if (!seatData.flight_id || !seatData.user_id || !seatData.seat_number) {
            alert('Please fill out all required fields.');
            return;
        }
        console.log("Seat Data is " +  JSON.stringify(seatData));
        
        fetch('http://127.0.0.1:8000/api/book', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(seatData)
        })
        .then(res => res.json())
        .then(data => displayResponse(data))
        .catch(error => displayResponse({ error: error.message }));
    }

    // Function to cancel booking
    function cancelBooking() {
        
        const bookingId = document.getElementById("bookingId");
        const userInput = bookingId.value.trim();

        console.log(userInput);
        const url = `http://127.0.0.1:8000/api/cancel/` + encodeURIComponent(userInput);
        console.log(url);
        console.log("http://127.0.0.1:8000/api/cancel/${encodeURIComponent(userInput)}");
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