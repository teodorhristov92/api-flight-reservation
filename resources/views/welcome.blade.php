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
                        <form>
                            <div class="form-group row">
                                <label for="flightId" class="col-sm-12 col-form-label">Flight ID:</label>
                                <div class="col-sm-12">
                                <input type="number" class="form-control" id="flightId" placeholder="Flight id" oninput="checkInput()" onkeyup="if(value<0) value=0;">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="passengerName" class="col-sm-12 col-form-label">Passenger Name:</label>
                                <div class="col-sm-12">
                                <input type="text" class="form-control" id="passengerName" placeholder="Name">
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

                            <div class="form-group row pt-4">
                                <div class="col-sm-10">
                                    <button class="btn btn-primary" onclick="bookSeat()">Sign in</button>
                                </div>
                            </div>
                        </form>
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

        function getMaxSeats(flightId) {
            fetch(`/flight/${flightId}/max-seats`)
                .then(response => response.json())
                .then(data => {
                    console.log('Max Seats:', data.max_seats);
                })
                .catch(error => {
                    console.error('Error:', error);
            });
        }

        function showSeatMap(hide) {
            const imageDiv = document.getElementById('imageDiv');
            const planeDiv = document.getElementById('planeDiv');
            console.log('hi');
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

        // Click to select a seat
        const seats = document.querySelectorAll('.seat');
        seats.forEach(seat => {
            seat.addEventListener('click', () => {
                seat.classList.toggle('selected');
                console.log("is selected");
            });
        });

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
            console.log(flightId);

            if (flightId) {
                $.get(`http://127.0.0.1:8000/api/report/${flightId}`, function(data) {
                    $('#result').html(`Max Seats: ${data.capacity}`);
                    console.log(`Max Seats: ${data.capacity}`);
                    const maxSeats = data.capacity;
                    const seatsPerSide = 3;
                    const seatsPerRow = seatsPerSide * 2;
                    const rows = Math.ceil(maxSeats / seatsPerRow);
                    let seatNumber = 1;
                    const container = document.getElementById('planeSeats');
                    const inputSelectedSeat = document.getElementById('seatNumber');
                    let previousButton = null;

                    for (let row = 0; row < rows; row++) {
                        const rowDiv = document.createElement('div');
                        rowDiv.classList.add('row-container');

                    //Left seats
                    const seatsLeftDiv = document.createElement('div');
                    seatsLeftDiv.classList.add('seats-left');
                    for (let leftSeat = 0; leftSeat < seatsPerSide; leftSeat++) {
                        if (seatNumber <= maxSeats) {
                            const button = document.createElement('button');
                            button.classList.add('seat');
                            button.textContent = seatNumber;
                            button.onclick = function(){
                                const selectedValue = this.textContent;
                                inputSelectedSeat.value = selectedValue;
                                if (previousButton) {
                                    previousButton.classList.remove('clicked');
                                }
                                this.classList.add('clicked');
                                previousButton = this;
                            }
                            seatsLeftDiv.appendChild(button);
                            seatNumber++;
                        }
                    }

                    //(space between left and right)
                    const aisleDiv = document.createElement('div');
                    aisleDiv.classList.add('aisle');

                    //Right seats
                    const seatsRightDiv = document.createElement('div');
                    seatsRightDiv.classList.add('seats-right');
                    for (let rightSeat = 0; rightSeat < seatsPerSide; rightSeat++) {
                        if (seatNumber <= maxSeats) {
                            const button = document.createElement('button');
                            button.classList.add('seat');
                            button.textContent = seatNumber;
                            button.onclick = function(){
                                const selectedValue = this.textContent;
                                inputSelectedSeat.value = selectedValue;
                                if (previousButton) {
                                    previousButton.classList.remove('clicked');
                                }
                                this.classList.add('clicked');
                                previousButton = this;
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
        function bookSeat() {
            const seatData = {
                flight_id: document.getElementById("flightId").value.trim(),
                user_id: document.getElementById("passengerName").value.trim(),
                seat_number: document.getElementById("passengerSeat").value.trim(),
            };
            

            
            fetch('http://127.0.0.1:8000/api/book', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(seatData)
                })
                .then(async (res) => {
                const text = await res.text();
                if (!res.ok) {
                    throw new Error("Network response was not ok");
                }
                const data = JSON.parse(text);
            })
        }
    </script>

</body>
</html>