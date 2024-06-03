<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkbox List with Line-through Text</title>
  <style>
    /* styles.css */
.checkbox-list {
  list-style-type: none;
  padding: 0;
}

.checkbox-list li {
  display: flex;
  align-items: center;
  margin: 5px 0;
}

.checkbox-list input[type="checkbox"] {
  display: none; /* Hide the checkbox */
}

.checkbox-list input[type="checkbox"] + label {
  cursor: pointer;
  transition: color 0.2s;
}

.checkbox-list input[type="checkbox"]:checked + label {
  text-decoration: line-through; /* Line-through text when checkbox is checked */
  color: gray; /* Optional: Change text color when checked */
}

  </style>
</head>
<body>
  <ul class="checkbox-list">
    <li>
      <input type="checkbox" id="item1">
      <label for="item1">Item 1</label>
    </li>
    <li>
      <input type="checkbox" id="item2">
      <label for="item2">Item 2</label>
    </li>
    <li>
      <input type="checkbox" id="item3">
      <label for="item3">Item 3</label>
    </li>
    <!-- Add more items as needed -->
  </ul>
</body>
</html>
