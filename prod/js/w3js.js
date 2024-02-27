 document.getElementsByClassName("tablink")[0].click();

                function openAccess(evt, accType) {
                    var i, x, tablinks;
                    x = document.getElementsByClassName("accessType");
                    for (i = 0; i < x.length; i++) {
                        x[i].style.display = "none";
                    }
                    for (i = 0; i < x.length; i++) {
                    tablinks = document.getElementsByClassName("tablink");
                        tablinks[i].classList.remove("w3-light-grey");
                    }
                    document.getElementById(accType).style.display = "block";
                    evt.currentTarget.classList.add("w3-light-grey");
                }

