
<?php
session_start();
define("access",true);
include ("classes/controls.php");
$control=new controls();

include ("header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
body {
    line-height: 1.6;
    color: #333;
    background-color: white !important;
    margin: 0;
}
.user-details {
    background-color: white;
    padding: 13px 3px;
    border-radius: 8px;
    width: 100%;
    max-width: 100%;
    text-align: left;
}

.user-details h2 {
    margin-top: 0;
    color: #d32f2f;
    font-weight: 700;
    text-align: center;
}

.details-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    padding: 10px;
    background-color: #f7f7f9;
}

.detail {
    margin-bottom: 0px;
    flex: 1 1 23%;
    display: flex;
    justify-content: space-between;
    padding: 10px;
    border-radius: 4px;
    background-color: white;
    margin: 4px;
    box-sizing: border-box;
    transition: transform 0.5s ease, box-shadow 0.5s ease;

}

.detail label {
    font-weight: 300;
    color: #333;
    flex: 0 0 45%;
}

.detail span {
    font-weight: 500;
    color: #555;
    flex: 1;
    text-align: right;
}
.uk-grid-small label{
    font-weight: 500;
    color: #555;
    font-size: 0.3rem; 
    color:#54bf99;
}
.detail label, .detail span {
    font-size: 0.9rem;
}
.detail:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
.main {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

.main ul {
    display: flex;
}

.main ul li {
    list-style: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 0 40px;
}

.main ul li .icons {
    font-size: 25px;
    color: #54bc9c;
    margin: 0 60px;
}

.main ul li .label {
    font-family: sans-serif;
    letter-spacing: 1px;
    font-size: 14px;
    font-weight: bold;
    color: #54bc9c;
}

.main ul li .step {
    height: 30px;
    width: 30px;
    border-radius: 50%;
    background-color: #d7d7c3;
    margin: 16px 0 10px;
    display: grid;
    place-items: center;
    color: ghostwhite;
    position: relative;
    cursor: pointer;
}

.step::after {
    content: "";
    position: absolute;
    width: 197px;
    height: 3px;
    background-color: #d7d7c3;
    right: 30px;
}

.first::after {
    width: 0;
    height: 0;
}

.main ul li .step .awesome {
    display: none;
}

.main ul li .step p {
    font-size: 18px;
}

.main ul li .active {
    background-color: #54bc9c;
}

.main ul li .active::after {
    background-color: #54bc9c;

}

.main ul li .active p {
    display: none;
}

.main ul li .active .awesome {
    display: flex;
}
.main ul{
    margin: 0 0 2px 0 !important;
}

    .mymain {
        display: flex;
    }


    
.table-wrapper{
    box-shadow: 0px 35px 50px rgba( 0, 0, 0, 0 );
}

.fl-table {
    border-radius: 5px;
    font-size: 12px;
    font-weight: normal;
    border: none;
    border-collapse: collapse;
    width: 100%;
    max-width: 100%;
    white-space: nowrap;
    background-color: white;
}

.fl-table td, .fl-table th {
    text-align: center;
    padding: 8px;
}

.fl-table td {
    border-right: 1px solid #f8f8f8;
    font-size: 12px;
}

.fl-table thead th {
    color: #ffffff;
    background: #4FC3A1;
}


.fl-table thead th:nth-child(odd) {
    color: #ffffff;
    background: #4FC3A1;
}

.fl-table tr:nth-child(even) {
    background: #F8F8F8;
}

/* Responsive */

@media (max-width: 767px) {
    .fl-table {
        display: block;
        width: 100%;
    }
    .table-wrapper:before{
        content: "Scroll horizontally >";
        display: block;
        text-align: right;
        font-size: 11px;
        color: white;
        padding: 0 0 10px;
    }
    .fl-table thead, .fl-table tbody, .fl-table thead th {
        display: block;
    }
    .fl-table thead th:last-child{
        border-bottom: none;
    }
    .fl-table thead {
        float: left;
    }
    .fl-table tbody {
        width: auto;
        position: relative;
        overflow-x: auto;
    }
    .fl-table td, .fl-table th {
        padding: 20px .625em .625em .625em;
        height: 60px;
        vertical-align: middle;
        box-sizing: border-box;
        overflow-x: hidden;
        overflow-y: auto;
        width: 120px;
        font-size: 13px;
        text-overflow: ellipsis;
    }
    .fl-table thead th {
        text-align: left;
        border-bottom: 1px solid #f7f7f9;
    }
    .fl-table tbody tr {
        display: table-cell;
    }
    .fl-table tbody tr:nth-child(odd) {
        background: none;
    }
    .fl-table tr:nth-child(even) {
        background: transparent;
    }
    .fl-table tr td:nth-child(odd) {
        background: #F8F8F8;
        border-right: 1px solid #E6E4E4;
    }
    .fl-table tr td:nth-child(even) {
        border-right: 1px solid #E6E4E4;
    }
    .fl-table tbody td {
        display: block;
        text-align: center;
    }
}
.checkbox-list {
  list-style-type: none;
  padding: 0;
}

.checkbox-list li {
  display: flex;
  align-items: center;
  margin-top: 12px;
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
label .uk-badge{
    background-color: #54bf99 !important;
}
    </style>
</head>
<body>

    <div class="user-details">
<div class="mymain">
<div style="width:40%; padding:10px; border-right:dashed #54bc9c 1px; border-radius:10px">
<ul class="checkbox-list">
    <li>
      <input type="checkbox" id="item1">
      <label for="item1" title="- Does the account reflect: Medical aid details. Provider practice number, service date, tariff codes, billed amounts, proof of payment (if applicable)
"><span class="uk-badge">1</span> Was the detailed account received?</label>
    </li>
    <li>
      <input type="checkbox" id="item2">
      <label for="item2" title="- Take down / Note the reference number for the submission and the expected TAT for processing"><span class="uk-badge">2</span> If yes, then submit to the Medical Aid</label>
    </li>

    <li>
      <input type="checkbox" id="item4">
      <label for="item4"><span class="uk-badge">3</span> Follow up when the processing TAT has lapsed</label>
    </li>
    <li>
      <input type="checkbox" id="item5">
      <label for="item5"><span class="uk-badge">4</span> Request a copy of the medical statement / notification (if not received)</label>
    </li>
    <li>
      <input type="checkbox" id="item6">
      <label for="item6"><span class="uk-badge">5</span> Update the Member and share a copy of the medical statement with the Member.</label>
    </li>
  
  </ul>
        </div>

        <div class="details-container tendai">
        <div class="main">

<ul>
    <li>
        <i class="icons awesome fa-solid fa-hourglass-start"></i>
        <div class="step first">
            <p>1</p>
            <i class="awesome fa-solid fa-check"></i>
        </div>
        <p class="label">Claim Submission</p>
    </li>
    <li>
        <i class="icons awesome fa-solid fa-book"></i>
        <div class="step second">
            <p>2</p>
            <i class="awesome fa-solid fa-check"></i>
        </div>
        <p class="label">Gap Submission</p>
    </li>
    <li>
        <i class="icons awesome fa-solid fa-certificate"></i>
        <div class="step third">
            <p>3</p>
            <i class="awesome fa-solid fa-check"></i>
        </div>
        <p class="label">Validation</p>
    </li>
    <li>
        <i class="icons awesome fa-solid fa-person-circle-check"></i>
        <div class="step fourth">
            <p>4</p>
            <i class="awesome fa-solid fa-check"></i>
        </div>
        <p class="label">Approval</p>
    </li>
    <li>
        <i class="icons awesome fa-solid fa-thumbs-up"></i>
        <div class="step fifth">
            <p>5</p>
            <i class="awesome fa-solid fa-check"></i>
        </div>
        <p class="label">Payment</p>
    </li>
    <li>
        <i class="icons awesome fa-solid fa-power-off"></i>
        <div class="step sixth">
            <p>6</p>
            <i class="awesome fa-solid fa-check"></i>
        </div>
        <p class="label">Finish</p>
    </li>
</ul>
</div>
        <div class="detail">
                <label for="claim-number">Claim</label>
              <span id="claim-number">[S51.0] PMB, Non-Emergency</span>
            </div>
         
            <div class="detail">
                <label for="date-opened-closed">Date Opened/Closed:</label>
                <span id="date-opened-closed">2023-12-12 08:20:09 / 2023-12-12 13:22:07</span>
            </div>
            <div class="detail">
                <label for="incident-date">Incident Date:</label>
                <span id="incident-date">From 2023-08-25 To 2023-08-25</span>
            </div>

            <div class="detail">
                <label for="member-number">User</label>
                <span id="member-number">Tendai Fuma</span>
            </div>
           <div class="detail">
                <label for="claim-number">Claim Number:</label>
                <span id="claim-number">GAP3098397 / 1</span>
            </div>
            <div class="detail">
                <label for="policy-number">Policy Number:</label>
                <span id="policy-number">GAP3098397</span>
            </div>

            <div class="detail">
                <label for="client-name">Client Name:</label>
                <span id="client-name">Zestlife</span>
            </div>
            <div class="detail">
                <label for="created-by">Created By:</label>
                <span id="created-by">zeterryj</span>
            </div>
            <div class="detail">
                <label for="full-name">Full Name:</label>
                <span id="full-name">MICHAEL ANTHONY REED</span>
            </div>
            <div class="detail">
                <label for="id-number">ID Number:</label>
                <span id="id-number"></span>
            </div>
            <div class="detail">
                <label for="email">Email:</label>
                <span id="email">mike@kirjo.co.za</span>
            </div>
            <div class="detail">
                <label for="contact-numbers">Contact:</label>
                <span id="contact-numbers">0824136471 /</span>
            </div>
          
            <div class="detail">
                <label for="patients">Patient:</label>
                <span id="patients">ROMAY ETHNE CLARICE REED [4902180019088]</span>
            </div>
            <div class="detail">
                <label for="scheme-name">Scheme:</label>
                <span id="scheme-name">Discovery Health Medical Scheme</span>
            </div>
            <div class="detail">
                <label for="scheme-option">Scheme Option:</label>
                <span id="scheme-option">Essential Delta Core</span>
            </div>
            <div class="detail">
                <label for="member-number">Member Number:</label>
                <span id="member-number">673327422</span>
            </div>
      
        </div>
    </div>

<div class="table-wrapper">
    <table class="fl-table">
        <thead>
        <tr><th>No.</th><th>CPT4</th><th>Inv.Dat</th><th>Modifier</th><th>Res. Code</th><th>Treat.Date</th><th>PMB?</th><th>Tarif.C</th><th>ICD10</th><th>Chrgd Amt</th>
                        <th>Sch. Amt</th><th>Memb.Port</th><th>GAP</th><th>Calc</th></tr>
        </thead>
        <tbody>
        
        <tr>
        <td><span class="uk-badge">1</span></td>
            <td></td>
            <td></td>
            <td>0000</td>
            <td>9000</td>
            <td>2003-09-09</td>
            <td><span uk-icon="check"></span></td>
            <td>1012</td>
            <td>234.90</td>
            <td>234.90</td>
            <td>234.90</td>
            <td>234.90</td>
            <td>234.90</td>
            <td>234.90</td>
        </tr>
        <tr>
        <td><span class="uk-badge">2</span></td>
            <td></td>
            <td></td>
            <td>0000</td>
            <td>9000</td>
            <td>2003-09-09</td>
            <td><span uk-icon="check"></span></td>
            <td>1012</td>
            <td>234.90</td>
            <td>234.90</td>
            <td>234.90</td>
            <td>234.90</td>
            <td>234.90</td>
            <td>234.90</td>
        </tr>
       
     
        <tbody>
    </table>
</div>
    </div>
    <script>
    const first = document.querySelector(".first");
const second = document.querySelector(".second");
const third = document.querySelector(".third");
const fourth = document.querySelector(".fourth");
const fifth = document.querySelector(".fifth");
const sixth = document.querySelector(".sixth");
const steps = [first, second, third, fourth, fifth,sixth];

function nextStep(currentStep) {
    steps.forEach(step => step.classList.remove("active"));

    steps.forEach((step, index) => {
        if (index <= currentStep) {
            step.classList.add("active");
        } else {
            step.classList.remove("active");
        }
    });
}

steps.forEach((step, index) => {
    step.addEventListener("click", () => {
        nextStep(index);
    });
});
</script>
</body>
</html>
