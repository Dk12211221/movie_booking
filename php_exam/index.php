 <!DOCTYPE html>
<html>
<head>
    <title>Movie Booking System</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        h2 { background: #333; color: white; padding: 10px; }
        form { background: white; padding: 20px; margin-bottom: 30px; border-radius: 5px; box-shadow: 0 0 5px #ccc; }
        label { display: block; margin-top: 10px; }
        input, select { padding: 8px; width: 100%; margin-top: 5px; }
        button { margin-top: 15px; padding: 10px 20px; }
    </style>
</head>
<body>

<h1>🎬 Movie Booking System - Admin Panel</h1>

<h2>Add User</h2>
<form method="post" action="api/users.php">
    <label>Name:</label>
    <input type="text" name="name" required>
    
    <label>Email:</label>
    <input type="email" name="email" required>

    <button type="submit">Add User</button>
</form>

<h2>Add Movie</h2>
<form method="post" action="api/movies.php">
    <label>Title:</label>
    <input type="text" name="title" required>

    <label>Genre:</label>
    <input type="text" name="genre" required>

    <label>Duration (minutes):</label>
    <input type="number" name="duration" required>

    <button type="submit">Add Movie</button>
</form>

<h2>Add Theater</h2>
<form method="post" action="api/theaters.php">
    <label>Name:</label>
    <input type="text" name="name" required>

    <label>Location:</label>
    <input type="text" name="location" required>

    <button type="submit">Add Theater</button>
</form>

<h2>Add Show</h2>
<form method="post" action="api/shows.php">
    <label>Movie ID:</label>
    <input type="number" name="movie_id" required>

    <label>Theater ID:</label>
    <input type="number" name="theater_id" required>

    <label>Show Time:</label>
    <input type="datetime-local" name="show_time" required>

    <button type="submit">Add Show</button>
</form>

<h2>Add Booking</h2>
<form method="post" action="api/bookings.php">
    <label>User ID:</label>
    <input type="number" name="user_id" required>

    <label>Show ID:</label>
    <input type="number" name="show_id" required>

    <label>Booking Time:</label>
    <input type="datetime-local" name="booking_time" required>

    <button type="submit">Add Booking</button>
</form>

<h2>Add Seat to Booking</h2>
<form method="post" action="api/booking_seats.php">
    <label>Booking ID:</label>
    <input type="number" name="booking_id" required>

    <label>Seat Number:</label>
    <input type="text" name="seat_number" required>

    <button type="submit">Add Seat</button>
</form>

</body>
</html>
