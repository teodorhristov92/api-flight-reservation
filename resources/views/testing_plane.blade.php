<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Plane Seat Map</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

</head>
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
                                <input type="number" class="form-control" id="flightId" placeholder="Flight id" oninput="checkInput()">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="passengerName" class="col-sm-12 col-form-label">Passenger Name:</label>
                                <div class="col-sm-12">
                                <input type="text" class="form-control" id="passengerName" placeholder="Name">
                                </div>
                            </div>

                            <fieldset class="form-group">
                                <div class="row">
                                    <legend class="col-form-label pt-1">Select seat option</legend>
                                    <div class="col-sm-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="option1" onclick="toggleDiv(false)">
                                            <label class="form-check-label" for="gridRadios1">
                                                Random seat number:
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="option2" onclick="toggleDiv(true)" disabled>
                                            <label class="form-check-label" for="gridRadios2">
                                                Select a seat ( Select a flight id )
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            
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
            const radio2 = document.getElementById('gridRadios2');

            if (inputField.value.trim() === '') {
                radio2.disabled = true;
                radio2.checked;
            } else {
                radio2.disabled = false;
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

        function toggleDiv(hide) {
            const imageDiv = document.getElementById('imageDiv');
            const planeDiv = document.getElementById('planeDiv');
            const inputField = document.getElementById('flightId');
            const radio1 = document.getElementById('gridRadios1');
            const radio2 = document.getElementById('gridRadios2');
            
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
                    const seatsPerRow = seatsPerSide * 2; // 6 seats per row

                    const rows = Math.ceil(maxSeats / seatsPerRow);
                    let seatNumber = 1;

                    const container = document.getElementById('planeSeats');
                    const inputSelectedSeat = document.getElementById('seatNumber');

                    for (let row = 0; row < rows; row++) {
                        const rowDiv = document.createElement('div');
                        rowDiv.classList.add('row-container');

                    // Left seats
                    const seatsLeftDiv = document.createElement('div');
                    seatsLeftDiv.classList.add('seats-left');
                    for (let leftSeat = 0; leftSeat < seatsPerSide; leftSeat++) {
                        if (seatNumber <= maxSeats) {
                            const button = document.createElement('button');
                            button.classList.add('seat');
                            button.textContent = seatNumber;
                            button.onclick = function(){
                                const selectedValue = this.textContent; // or this.value if you set value
                                console.log("You clicked seat:", selectedValue);

                                // You can also store it somewhere, for example in an array:
                                inputSelectedSeat.value = selectedValue;
                            }
                            seatsLeftDiv.appendChild(button);
                            seatNumber++;
                        }
                    }

                    // Aisle (space between left and right)
                    const aisleDiv = document.createElement('div');
                    aisleDiv.classList.add('aisle');

                    // Right seats
                    const seatsRightDiv = document.createElement('div');
                    seatsRightDiv.classList.add('seats-right');
                    for (let rightSeat = 0; rightSeat < seatsPerSide; rightSeat++) {
                        if (seatNumber <= maxSeats) {
                            const button = document.createElement('button');
                            button.classList.add('seat');
                            button.textContent = seatNumber;
                            seatsRightDiv.appendChild(button);
                            seatNumber++;
                        }
                    }
                    // Append all parts to the row
                    rowDiv.appendChild(seatsLeftDiv);
                    rowDiv.appendChild(aisleDiv);
                    rowDiv.appendChild(seatsRightDiv);

                    // Add row to container
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
                seat_number: document.getElementById("seatNumber").value.trim(),
            };
            
            console.log("Seat Data is " +  JSON.stringify(seatData));
            
            fetch('http://127.0.0.1:8000/api/book', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(seatData)
            })
            .then(async (res) => {
            console.log("Response status:", res.status);
            console.log("Response ok:", res.ok);
            
            const text = await res.text();
            console.log("Response body:", text);

            if (!res.ok) {
                throw new Error("Network response was not ok");
            }

            const data = JSON.parse(text);
            console.log("Success:", data);
            })
        }
    </script>

</body>
</html>