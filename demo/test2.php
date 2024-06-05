<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sticky Side Button</title>
   <style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.sticky-button {
    position: fixed;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 50px;
    padding: 15px 20px;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s, box-shadow 0.3s;
}

.sticky-button:hover {
    background-color: #0056b3;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

   </style>
</head>
<body>
    <button class="sticky-button">Contact Us</button>
</body>
</html>
