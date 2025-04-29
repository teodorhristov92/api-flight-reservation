<!DOCTYPE html>
<html lang="en">

@include('layouts.head')

<body>
    @include('layouts.header')

    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-5">
                <div class="book-panel pt-4">
                    <div class="p-4 book-panel-inside">
                        <h1><i class="fa-solid fa-plane-departure fa-beat-fade" style="color: #0B5ED7;"></i> Book you flight</h1>
                        <div>
                            <div class="form-group row">
                                <label for="flightId" class="col-sm-12 col-form-label">Flight ID:</label>
                                <div class="col-sm-12">
                                <input type="number" class="form-control" id="flightId" placeholder="Flight id" oninput="checkInput()" onkeyup="if(value<0) value=0;">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="passengerName" class="col-sm-12 col-form-label">Passenger ID:</label>
                                <div class="col-sm-12">
                                <input type="text" class="form-control" id="passengerName" placeholder="Name" onkeyup="if(value<0) value=0;">
                                </div>
                            </div>

                            <div class="form-group row pt-4">
                                <div class="col-sm-10">
                                    <button type="button" id="mapSeats" class="btn btn-primary" onclick="showSeatMap(true)" disabled><i class="fa-solid fa-map-location-dot"></i> Show seats map</button>
                                </div>
                            </div>
                            
                            <div id="selectedSeat" class="form-group row">
                                <label for="seatNumber" class="col-sm-12 col-form-label">Selected seat:</label>
                                <div class="col-sm-12">
                                <input type="number" class="form-control" id="seatNumber" placeholder="Seat number" value="" disabled>
                                </div>
                            </div>

                            <div id="selectedSeat" class="form-group row pt-2">
                                <label class="w-auto" for="typeSeat">Select seat type</label>
                                <select class="w-auto" name="seatType" id="typeSeat">
                                    <option value="Economy">Economy</option>
                                    <option value="Business">Business</option>
                                    <option value="First">First</option>
                                </select>
                            </div>

                            <div class="form-group row pt-4">
                                <div class="col-sm-10">
                                    <button id="submitBtn" class="btn btn-primary">Sign in</button>
                                </div>
                            </div>
                        </div>
                    </div>   
                </div>
            </div>
            <div class="col-md-12 col-lg-7">
                <div id="imageDiv" class="fade-div" style="">
                    <img class="image-plane" src="{{ asset('images/simon-maage-C9dhUVP-o6w-unsplash.jpg') }}" alt="Airplane Wing" style="border-radius: 15px;">
                </div>
                <div id="planeDiv" class="fade-div">
                    <div class="plane-wrapper plane-color"></div>
                    <div id="planeSeats" class="plane-color" style="display: flex; flex-direction: column; gap: 10px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    @include('layouts.footer')

    <script>
        function checkInput() {
            const inputField = document.getElementById('flightId');
            const showSeatsMapButton = document.getElementById('mapSeats');

            if (inputField.value.trim() === '') {
                showSeatsMapButton.disabled = true;
                showSeatsMapButton.checked;
            } else {
                showSeatsMapButton.disabled = false;
            }
        }

        function showSeatMap(hide) {
            const imageDiv = document.getElementById('imageDiv');
            const planeDiv = document.getElementById('planeDiv');

            if (hide) {
                imageDiv.classList.add('hidden');
                planeDiv.classList.remove('hidden');
                setTimeout(() => {
                    imageDiv.style.display = 'none';
                    planeDiv.style.display = 'block';
                }, 500);
            } else {
                planeDiv.classList.add('hidden');
                imageDiv.classList.remove('hidden');
                setTimeout(() => {
                    planeDiv.style.display = 'none';
                    imageDiv.style.display = 'block';
                }, 500);
            }
        }
        function loadReport() {
            const flightId = document.getElementById("flightId").value;
            fetch(`http://127.0.0.1:8000/api/report/${flightId}`, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                alert('Load Factor Report: ' + JSON.stringify(data));
            })
            .catch(error => {
                alert('Error loading report: ' + error);
            });
        }

        $('#flightId').on('input', function() {
            const flightId = $(this).val();

            if (flightId) {
                $.get(`http://127.0.0.1:8000/api/flightSeats/${flightId}`, function(data) {
                    $('#result').html(`Max Seats: ${data.capacity}`);

                    const maxSeats = data.capacity;
                    const seatsPerSide = 3;
                    const seatsPerRow = seatsPerSide * 2;
                    const rows = Math.ceil(maxSeats / seatsPerRow);
                    let seatNumber = 1;
                    const container = document.getElementById('planeSeats');
                    const inputSelectedSeat = document.getElementById('seatNumber');
                    let previousButton = null;

                    const reservedSeats = data.reservedSeats || [];
                    container.innerHTML = '';

                    for (let row = 0; row < rows; row++) {
                        const rowDiv = document.createElement('div');
                        rowDiv.classList.add('row-container');

                        const seatsLeftDiv = document.createElement('div');
                        seatsLeftDiv.classList.add('seats-left');
                        for (let leftSeat = 0; leftSeat < seatsPerSide; leftSeat++) {
                            if (seatNumber <= maxSeats) {
                                const button = document.createElement('button');
                                button.classList.add('seat');
                                if (reservedSeats.includes(seatNumber.toString())) {;
                                    button.classList.add('reserved');
                                    button.disabled = true;
                                    button.textContent = `R${seatNumber}`;
                                } else {
                                    button.textContent = seatNumber;
                                    button.onclick = function() {
                                        const selectedValue = this.textContent;
                                        inputSelectedSeat.value = selectedValue;
                                        if (previousButton) {
                                            previousButton.classList.remove('clicked');
                                        }
                                        this.classList.add('clicked');
                                        previousButton = this;
                                    }
                                }
                                seatsLeftDiv.appendChild(button);
                                seatNumber++; 
                            }
                        }
                        const aisleDiv = document.createElement('div');
                        aisleDiv.classList.add('aisle');

                        const seatsRightDiv = document.createElement('div');
                        seatsRightDiv.classList.add('seats-right');
                        for (let rightSeat = 0; rightSeat < seatsPerSide; rightSeat++) {
                            if (seatNumber <= maxSeats) {
                                const button = document.createElement('button');
                                button.classList.add('seat');

                                if (reservedSeats.includes(seatNumber.toString())) {
                                    button.classList.add('reserved');
                                    button.disabled = true;
                                    button.textContent = `R${seatNumber}`;
                                } else {
                                    button.textContent = seatNumber;
                                    button.onclick = function() {
                                        const selectedValue = this.textContent;
                                        inputSelectedSeat.value = selectedValue;
                                        if (previousButton) {
                                            previousButton.classList.remove('clicked');
                                        }
                                        this.classList.add('clicked');
                                        previousButton = this;
                                    }
                                }
                                seatsRightDiv.appendChild(button);
                                seatNumber++;
                            }
                        }
                        rowDiv.appendChild(seatsLeftDiv);
                        rowDiv.appendChild(aisleDiv);
                        rowDiv.appendChild(seatsRightDiv);
                        container.appendChild(rowDiv);
                    }
                });
            }
        });

        //Book
        document.getElementById("submitBtn").addEventListener("click", () => {
            const seatData = {
                flight_id: document.getElementById("flightId").value.trim(),
                user_id: document.getElementById("passengerName").value.trim(),
                seat_number: document.getElementById("seatNumber").value.trim(),
                seat_type: document.getElementById("typeSeat").value.trim(),
            };
            if (!flight_id || !user_id || !seat_number || !seat_type) {
                console.error("One or more input fields are missing in the HTML.");
                return;
            }
            fetch('http://127.0.0.1:8000/api/book', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(seatData)
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return res.json();
                })
                .then(data => {
                    console.log("Success:", data);
                })
                .catch(err => {
                    console.error("Fetch error:", err);
            });
        });
    </script>

</body>
</html>