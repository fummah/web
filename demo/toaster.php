<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toaster Notification</title>
   <style>
   #toaster-container {
    position: fixed;
    top: 20px;
    right: 20px;
    width: 300px;
    z-index: 1000;
}

.toast {
    background-color: #54bc9c;
    color: #fff;
    padding: 16px;
    margin-bottom: 10px;
    border-radius: 4px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    opacity: 0;
    transition: opacity 0.3s, transform 0.3s;
    transform: translateY(-20px);
    position: relative;
}

.toast.show {
    opacity: 1;
    transform: translateY(0);
}

.toast .icon {
    display: inline-block;
    margin-right: 10px;
}

.toast .close {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
}

.toast .progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 4px;
    background-color: #ff9800;
    width: 100%;
    transition: width 3s linear;
}
</style>
</head>
<body>

<div id="toaster-container"></div>

<button onclick="showToast('This is a toaster notification!')">Show Toast</button>

<script>
    function showToast(message) {
    const toasterContainer = document.getElementById('toaster-container');

    // Create a new toast element
    const toast = document.createElement('div');
    toast.className = 'toast';

    // Add icon to the toast
    const icon = document.createElement('span');
    icon.className = 'icon';
    icon.innerHTML = '🔔'; // You can use any icon here
    toast.appendChild(icon);

    // Add message to the toast
    const text = document.createElement('span');
    text.textContent = message;
    toast.appendChild(text);

    // Add close button to the toast
    const closeButton = document.createElement('button');
    closeButton.className = 'close';
    closeButton.innerHTML = '&times;'; // Close icon (×)
    closeButton.onclick = () => {
        toasterContainer.removeChild(toast);
    };
    toast.appendChild(closeButton);

    // Add progress bar to the toast
    const progressBar = document.createElement('div');
    progressBar.className = 'progress';
    toast.appendChild(progressBar);

    // Append the toast to the container
    toasterContainer.appendChild(toast);

    // Trigger reflow for animation
    setTimeout(() => {
        toast.classList.add('show');
        progressBar.style.width = '0%'; // Start the progress bar animation
    }, 10);

    // Remove the toast after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            if (toasterContainer.contains(toast)) {
                toasterContainer.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

</script>
</body>
</html>